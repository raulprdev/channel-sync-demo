<?php

namespace App\Enums;

use App\Channels\Airbnb\AirbnbTransformer;
use App\Channels\Booking\BookingTransformer;
use App\Channels\Contracts\ChannelTransformerInterface;
use App\Channels\Vrbo\VrboTransformer;

enum ChannelCode: string
{
    case Airbnb = 'airbnb';
    case Vrbo = 'vrbo';
    case Booking = 'booking';

    public function name(): string
    {
        return match ($this) {
            self::Airbnb => 'Airbnb',
            self::Vrbo => 'Vrbo',
            self::Booking => 'Booking.com',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Airbnb => '#FF5A5F',
            self::Vrbo => '#3D67FF',
            self::Booking => '#003580',
        };
    }

    public function transformer(): ChannelTransformerInterface
    {
        return match ($this) {
            self::Airbnb => new AirbnbTransformer(),
            self::Vrbo => new VrboTransformer(),
            self::Booking => new BookingTransformer(),
        };
    }
}