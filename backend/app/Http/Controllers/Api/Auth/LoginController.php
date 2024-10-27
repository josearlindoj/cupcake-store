<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        // Attempt to authenticate the user
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login credentials.',
            ], 401);
        }

        // Retrieve the authenticated user
        $user = Auth::user();

        // Set token expiration time (30 minutes from now)
        $expiration = Carbon::now()->addMinutes(30);

        // Generate an access token with expiration
        $token = $user->createToken('auth_token', ['*'], $expiration)->plainTextToken;

        // Return success response
        return response()->json([
            'message' => 'User logged in successfully.',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => $expiration->toDateTimeString(),
            ],
        ], 200);
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();

        // Revoke current token
        $user->currentAccessToken()->delete();

        // Set new token expiration time
        $expiration = Carbon::now()->addMinutes(30);

        // Generate a new access token
        $token = $user->createToken('auth_token', ['*'], $expiration)->plainTextToken;

        // Return success response
        return response()->json([
            'message' => 'Token refreshed successfully.',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => $expiration->toDateTimeString(),
            ],
        ], 200);
    }

    public function logout(Request $request)
    {
        // Revoke the token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logged out successfully.',
        ], 200);
    }
}
