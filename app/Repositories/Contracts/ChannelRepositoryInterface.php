<?php

namespace App\Repositories\Contracts;

use App\Enums\ChannelCode;
use App\Models\Channel;
use Illuminate\Support\Collection;

interface ChannelRepositoryInterface
{
    public function findByCode(ChannelCode $code): ?Channel;

    public function getActive(): Collection;
}