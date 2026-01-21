<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\UserStateEnum;
use App\Models\UserState;

final class UserStateRepository
{
    public function store(string $userId, UserStateEnum $state): UserState
    {
        return UserState::query()->create([
            'user_id' => $userId,
            'state' => $state->value,
        ]);
    }

    public function getStateByChatId(int $telegramId): UserState
    {
        return UserState::query()->select('user_states.*')
            ->join('users', 'user_states.user_id', '=', 'users.id')
            ->where('users.telegram_id', $telegramId)
            ->latest()
            ->firstOrFail();
    }
}
