<?php

namespace App\DataTransferObjects;

use App\Enums\ChannelCode;
use Spatie\LaravelData\Data;

class ReservationData extends Data
{
    public function __construct(
        public string $externalId,
        public ChannelCode $channelCode,
        public string $propertyExternalId,
        public string $guestName,
        public ?string $guestEmail,
        public string $checkIn,
        public string $checkOut,
        public int $guests,
        public ?float $totalAmount,
        public array $rawPayload,
    ) {}
}