<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use Illuminate\Support\Carbon;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasFactory;

    // Add an accessor to check if the token is expired
    public function isExpired()
    {
        if ($this->expires_at) {
            return Carbon::now()->greaterThan($this->expires_at);
        }
        return false;
    }

    public static function createToken($tokenable, string $name, array $abilities = ['*'], $expiresAt = null)
    {
        $token = $tokenable->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => $abilities,
            'expires_at' => $expiresAt,
        ]);

        return new NewAccessToken($token, $plainTextToken);
    }
}
