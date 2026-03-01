<?php

namespace App\Repositories\Contracts;

use App\Models\Property;
use Illuminate\Support\Collection;

interface PropertyRepositoryInterface
{
    public function findById(int $id): ?Property;

    public function findByExternalId(string $externalId, int $channelId): ?Property;

    public function findOrCreate(string $externalId, int $channelId, array $attributes): Property;

    public function count(): int;

    public function getAll(): Collection;
}