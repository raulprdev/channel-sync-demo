<?php

namespace App\Channels\Booking;

use App\Channels\Contracts\ChannelTransformerInterface;
use App\DataTransferObjects\ReservationData;
use App\Enums\ChannelCode;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BookingTransformer implements ChannelTransformerInterface
{
    public function transform(array $payload): ReservationData
    {
        $reservation = $payload['reservation'];

        return new ReservationData(
            externalId: $reservation['id'],
            channelCode: ChannelCode::Booking,
            propertyExternalId: $reservation['hotel_id'],
            guestName: $this->parseGuestName($reservation['guest_name']),
            guestEmail: Arr::get($reservation, 'guest_email'),
            checkIn: Carbon::createFromFormat('d-m-Y', $reservation['checkin'])->toDateString(),
            checkOut: Carbon::createFromFormat('d-m-Y', $reservation['checkout'])->toDateString(),
            guests: Arr::get($reservation, 'adults', 1),
            totalAmount: Arr::get($payload, 'price.total'),
            rawPayload: $payload,
        );
    }

    private function parseGuestName(string $name): string
    {
        if (! Str::contains($name, ',')) {
            return $name;
        }

        return Str::of($name)
            ->explode(',')
            ->map(fn ($part) => trim($part))
            ->reverse()
            ->implode(' ');
    }
}