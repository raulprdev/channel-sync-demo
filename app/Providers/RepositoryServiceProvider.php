<?php

namespace App\Providers;

use App\Repositories\ChannelRepository;
use App\Repositories\Contracts\ChannelRepositoryInterface;
use App\Repositories\Contracts\PropertyRepositoryInterface;
use App\Repositories\Contracts\ReservationRepositoryInterface;
use App\Repositories\PropertyRepository;
use App\Repositories\ReservationRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ChannelRepositoryInterface::class, ChannelRepository::class);
        $this->app->bind(PropertyRepositoryInterface::class, PropertyRepository::class);
        $this->app->bind(ReservationRepositoryInterface::class, ReservationRepository::class);
    }
}