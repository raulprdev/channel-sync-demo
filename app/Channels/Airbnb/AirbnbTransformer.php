<?php

namespace App\Channels\Airbnb;

use App\Channels\Contracts\ChannelTransformerInterface;
use App\DataTransferObjects\ReservationData;
use App\Enums\ChannelCode;
use Illuminate\Support\Arr;

class AirbnbTransformer implements ChannelTransformerInterface
{
    public function transform(array $payload): ReservationData
    {
        return new ReservationData(
            externalId: $payload['confirmation_code'],
            channelCode: ChannelCode::Airbnb,
            propertyExternalId: $payload['listing_id'],
            guestName: $payload['guest']['first_name'] . ' ' . $payload['guest']['last_name'],
            guestEmail: Arr::get($payload, 'guest.email'),
            checkIn: $payload['check_in'],
            checkOut: $payload['check_out'],
            guests: Arr::get($payload, 'number_of_guests', 1),
            totalAmount: Arr::get($payload, 'total_price.amount'),
            rawPayload: $payload,
        );
    }
}