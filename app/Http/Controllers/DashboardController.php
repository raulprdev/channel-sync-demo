<?php

namespace App\Http\Controllers;

use App\Enums\ReservationStatus;
use App\Repositories\Contracts\ChannelRepositoryInterface;
use App\Repositories\Contracts\PropertyRepositoryInterface;
use App\Repositories\Contracts\ReservationRepositoryInterface;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepository,
        private ChannelRepositoryInterface $channelRepository,
        private PropertyRepositoryInterface $propertyRepository,
    ) {}

    public function index(): View
    {
        return view('dashboard', [
            'stats' => $this->getStats(),
            'channels' => $this->channelRepository->getActive(),
            'reservations' => $this->reservationRepository->getRecentPaginated(15),
        ]);
    }

    private function getStats(): array
    {
        return [
            'properties' => $this->propertyRepository->count(),
            'reservations' => $this->reservationRepository->countByStatus(ReservationStatus::Confirmed),
            'conflicts' => $this->reservationRepository->countByStatus(ReservationStatus::Conflict),
        ];
    }
}