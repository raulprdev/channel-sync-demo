<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyVrboSignature
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
        $secret = config('services.vrbo.webhook_secret');

        if (empty($secret)) {
            return true;
        }

        $signature = $request->header('X-Vrbo-Signature');
        $timestamp = $request->header('X-Vrbo-Timestamp');

        if (empty($signature) || empty($timestamp)) {
            return false;
        }

        $payload = $timestamp . '.' . $request->getContent();
        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $signature);
    }
}