<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyBookingSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->isValidSignature($request)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        return $next($request);
    }

    private function isValidSignature(Request $request): bool
    {
        $secret = config('services.booking.webhook_secret');

        if (empty($secret)) {
            return true;
        }

        $authHeader = $request->header('Authorization');

        if (empty($authHeader) || ! str_starts_with($authHeader, 'Booking-Signature ')) {
            return false;
        }

        $signature = substr($authHeader, strlen('Booking-Signature '));
        $payload = $request->getContent();
        $expected = base64_encode(hash_hmac('sha256', $payload, $secret, true));

        return hash_equals($expected, $signature);
    }
}