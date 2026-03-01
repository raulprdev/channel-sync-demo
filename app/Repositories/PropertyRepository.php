<?php

namespace App\Repositories;

use App\Models\Property;
use App\Repositories\Contracts\PropertyRepositoryInterface;
use Illuminate\Support\Collection;

class PropertyRepository implements PropertyRepositoryInterface
{
    public function findById(int $id): ?Property
    {
        return Property::find($id);
    }

    public function findByExternalId(string $externalId, int $channelId): ?Property
    {
        return Property::where('external_id', $externalId)
            ->where('channel_id', $channelId)
            ->first();
    }

    public function findOrCreate(string $externalId, int $channelId, array $attributes): Property
    {
        return Property::firstOrCreate(
            ['external_id' => $externalId, 'channel_id' => $channelId],
            $attributes
        );
    }

    public function count(): int
    {
        return Property::count();
    }

    public function getAll(): Collection
    {
        return Property::with('channel')->get();
    }
}