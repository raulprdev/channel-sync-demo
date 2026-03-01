<?php

namespace App\Http\Controllers;

use App\Enums\ChannelCode;
use App\Services\SyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingWebhookController extends Controller
{
    public function __invoke(Request $request, SyncService $syncService): JsonResponse
    {
        $reservation = $syncService->processWebhook(ChannelCode::Booking, $request->all());

        return response()->json([
            'success' => true,
            'reservation_id' => $reservation->id,
            'status' => $reservation->status->value,
        ]);
    }
}