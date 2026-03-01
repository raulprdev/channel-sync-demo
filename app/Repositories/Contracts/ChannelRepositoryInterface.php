<?php

namespace App\Repositories\Contracts;

use App\Enums\ChannelCode;
use App\Models\Channel;

interface ChannelRepositoryInterface
{
    public function findByCode(ChannelCode $code): ?Channel;
}