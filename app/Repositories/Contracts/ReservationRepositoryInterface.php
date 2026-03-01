<?php

namespace App\Repositories\Contracts;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ReservationRepositoryInterface
{
    public function save(Reservation $reservation): Reservation;

    public function findByExternalId(string $externalId, int $channelId): ?Reservation;

    public function findOverlapping(int $propertyId, string $checkIn, string $checkOut): Collection;

    public function updateStatus(int $id, ReservationStatus $status): void;

    public function deleteOlderThan(Carbon $cutoffDate): int;

    public function getRecent(int $limit = 50): Collection;

    public function getRecentPaginated(int $perPage = 15): LengthAwarePaginator;

    public function countByStatus(ReservationStatus $status): int;
}