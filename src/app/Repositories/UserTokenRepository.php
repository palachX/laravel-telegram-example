<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\UserToken;
use Carbon\Carbon;

final class UserTokenRepository
{
    public function updateOrCreate(string $userId, string $token, ?Carbon $expiresAt): UserToken
    {
        return UserToken::query()->updateOrCreate(
            ['user_id' => $userId],
            ['token' => $token, 'expires_at' => $expiresAt]
        );
    }

    public function getByUserId(string $userId): ?UserToken
    {
        return UserToken::query()->firstWhere('user_id', $userId);
    }
}
