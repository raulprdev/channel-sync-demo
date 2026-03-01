<?php

namespace App\Repositories;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Repositories\Contracts\ReservationRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function save(Reservation $reservation): Reservation
    {
        $reservation->save();

        return $reservation;
    }

    public function findByExternalId(string $externalId, int $channelId): ?Reservation
    {
        return Reservation::where('external_id', $externalId)
            ->where('channel_id', $channelId)
            ->first();
    }

    public function findOverlapping(int $propertyId, string $checkIn, string $checkOut): Collection
    {
        return Reservation::where('property_id', $propertyId)
            ->where('check_in', '<', $checkOut)
            ->where('check_out', '>', $checkIn)
            ->get();
    }

    public function updateStatus(int $id, ReservationStatus $status): void
    {
        Reservation::where('id', $id)->update(['status' => $status]);
    }

    public function deleteOlderThan(Carbon $cutoffDate): int
    {
        return Reservation::where('check_out', '<', $cutoffDate)->delete();
    }

    public function getRecent(int $limit = 50): Collection
    {
        return Reservation::with(['channel', 'property'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function countByStatus(ReservationStatus $status): int
    {
        return Reservation::where('status', $status)->count();
    }
}