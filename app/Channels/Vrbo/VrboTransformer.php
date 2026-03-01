<?php

namespace App\Channels\Vrbo;

use App\Channels\Contracts\ChannelTransformerInterface;
use App\DataTransferObjects\ReservationData;
use App\Enums\ChannelCode;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class VrboTransformer implements ChannelTransformerInterface
{
    public function transform(array $payload): ReservationData
    {
        return new ReservationData(
            externalId: $payload['confirmationId'],
            channelCode: ChannelCode::Vrbo,
            propertyExternalId: $payload['propertyId'],
            guestName: $payload['traveler']['name'],
            guestEmail: Arr::get($payload, 'traveler.email'),
            checkIn: Carbon::parse($payload['arrival'])->toDateString(),
            checkOut: Carbon::parse($payload['departure'])->toDateString(),
            guests: Arr::get($payload, 'guestCount', 1),
            totalAmount: Arr::get($payload, 'totalAmount'),
            rawPayload: $payload,
        );
    }
}