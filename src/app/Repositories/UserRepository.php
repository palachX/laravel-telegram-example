<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Telegram\UserData;
use App\Models\User;

final class UserRepository
{
    public function firstOrCreate(UserData $userData): User
    {
        return User::query()->firstOrCreate(['telegram_id' => $userData->id, 'username' => $userData->username], [
            'first_name' => $userData->firstName,
            'last_name' => $userData->lastName,
        ]);
    }

    public function getUserByChatId(int $chatId): User
    {
        return User::query()->with('state')->whereTelegramId($chatId)->firstOrFail();
    }
}
