<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class VerifyTurnstile
{
    public function handle(Request $request, Closure $next): Response
    {
        $secretKey = config('services.turnstile.secret_key');
        $token = $request->input('cf_turnstile_response')
            ?? $request->header('X-Turnstile-Token');

        if (blank($secretKey)) {
            return $next($request);
        }

        if (blank($token)) {
            return response()->json([
                'errors' => [
                    'cf_turnstile_response' => ['The security check is required.'],
                ],
            ], 422);
        }

        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => $secretKey,
            'response' => $token,
            'remoteip' => $request->ip(),
        ]);

        if (! $response->successful() || ! (bool) $response->json('success')) {
            return response()->json([
                'errors' => [
                    'cf_turnstile_response' => ['The security check failed. Please try again.'],
                ],
            ], 422);
        }

        return $next($request);
    }
}
