<?php

namespace App\Services;

use App\DataTransferObjects\ReservationData;
use App\Enums\ChannelCode;
use App\Enums\ReservationStatus;
use App\Models\Property;
use App\Models\Reservation;
use App\Repositories\Contracts\ChannelRepositoryInterface;
use App\Repositories\Contracts\PropertyRepositoryInterface;
use App\Repositories\Contracts\ReservationRepositoryInterface;

class SyncService
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepository,
        private ChannelRepositoryInterface $channelRepository,
        private PropertyRepositoryInterface $propertyRepository,
        private ConflictDetectorService $conflictDetector,
    ) {}

    public function processWebhook(ChannelCode $channelCode, array $payload): Reservation
    {
        $data = $this->transformPayload($channelCode, $payload);
        $channelId = $this->channelRepository->findByCode($channelCode)->id;
        $property = $this->resolveProperty($data, $channelId);

        return $this->findOrCreateReservation($data, $channelId, $property);
    }

    private function transformPayload(ChannelCode $channelCode, array $payload): ReservationData
    {
        return $channelCode->transformer()->transform($payload);
    }

    private function resolveProperty(ReservationData $data, int $channelId): Property
    {
        return $this->propertyRepository->findOrCreate(
            $data->propertyExternalId,
            $channelId,
            ['name' => 'Property ' . $data->propertyExternalId]
        );
    }

    private function findOrCreateReservation(ReservationData $data, int $channelId, Property $property): Reservation
    {
        $existing = $this->reservationRepository->findByExternalId($data->externalId, $channelId);

        if ($existing) {
            return $existing;
        }

        return $this->createReservation($data, $channelId, $property);
    }

    private function createReservation(ReservationData $data, int $channelId, Property $property): Reservation
    {
        $status = $this->determineStatus($property->id, $data->checkIn, $data->checkOut);
        $reservation = Reservation::fromReservationData($data, $channelId, $property->id, $status);

        return $this->reservationRepository->save($reservation);
    }

    private function determineStatus(int $propertyId, string $checkIn, string $checkOut): ReservationStatus
    {
        $overlapping = $this->conflictDetector->detectConflicts($propertyId, $checkIn, $checkOut);

        if ($overlapping->isEmpty()) {
            return ReservationStatus::Confirmed;
        }

        $this->conflictDetector->markAllAsConflict($overlapping);

        return ReservationStatus::Conflict;
    }
}