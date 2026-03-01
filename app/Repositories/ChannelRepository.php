<?php

namespace App\Repositories;

use App\Enums\ChannelCode;
use App\Models\Channel;
use App\Repositories\Contracts\ChannelRepositoryInterface;

class ChannelRepository implements ChannelRepositoryInterface
{
    public function findByCode(ChannelCode $code): ?Channel
    {
        return Channel::where('code', $code)->first();
    }
}