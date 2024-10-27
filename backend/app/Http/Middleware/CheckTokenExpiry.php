<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CheckTokenExpiry
{
    public function handle(Request $request, Closure $next)
    {
        // Get the authenticated user
        $user = $request->user();

        if ($user) {
            // Get the current access token
            $accessToken = $user->currentAccessToken();
            $verifyExpiredAt = Carbon::now()->greaterThan($accessToken->expires_at);

            // Check if token is expired
            if ($accessToken && $verifyExpiredAt) {
                // Revoke the token
                $accessToken->delete();

                return response()->json([
                    'message' => 'Unauthenticated. Token has expired.',
                ], 401);
            }
        }

        return $next($request);
    }
}
