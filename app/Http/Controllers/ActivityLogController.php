<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\ReservationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function __invoke(Request $request, ReservationRepositoryInterface $reservationRepository): View
    {
        return view('partials.activity-log', [
            'reservations' => $reservationRepository->getRecentPaginated(15),
        ]);
    }
}