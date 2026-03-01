<?php

namespace App\Services;

use App\Enums\ReservationStatus;
use App\Repositories\Contracts\ReservationRepositoryInterface;
use Illuminate\Support\Collection;

class ConflictDetectorService
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepository
    ) {}

    public function detectConflicts(int $propertyId, string $checkIn, string $checkOut): Collection
    {
        return $this->reservationRepository->findOverlapping($propertyId, $checkIn, $checkOut);
    }

    public function markAsConflict(int $reservationId): void
    {
        $this->reservationRepository->updateStatus($reservationId, ReservationStatus::Conflict);
    }

    public function markAllAsConflict(Collection $reservations): void
    {
        $reservations->each(fn ($reservation) => $this->markAsConflict($reservation->id));
    }
}