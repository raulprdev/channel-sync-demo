<?php

use App\Http\Controllers\AirbnbWebhookController;
use App\Http\Controllers\BookingWebhookController;
use App\Http\Controllers\VrboWebhookController;
use App\Http\Middleware\VerifyAirbnbSignature;
use App\Http\Middleware\VerifyBookingSignature;
use App\Http\Middleware\VerifyVrboSignature;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/airbnb', AirbnbWebhookController::class)
    ->middleware(VerifyAirbnbSignature::class)
    ->name('webhooks.airbnb');

Route::post('/webhooks/vrbo', VrboWebhookController::class)
    ->middleware(VerifyVrboSignature::class)
    ->name('webhooks.vrbo');

Route::post('/webhooks/booking', BookingWebhookController::class)
    ->middleware(VerifyBookingSignature::class)
    ->name('webhooks.booking');
