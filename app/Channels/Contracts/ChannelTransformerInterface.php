<?php

namespace App\Channels\Contracts;

use App\DataTransferObjects\ReservationData;

interface ChannelTransformerInterface
{
    public function transform(array $payload): ReservationData;
}