<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Telegram\UserData;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;

final class UserRepository
{
    public function firstOrCreate(UserData $userData): User
    {
        return User::query()->firstOrCreate(['telegram_id' => $userData->id, 'username' => $userData->username], [
            'first_name' => $userData->firstName,
            'last_name' => $userData->lastName,
        ]);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function setPhoneNumber(int $chatId, string $phone): bool
    {
        return User::query()->whereTelegramId($chatId)->firstOrFail()->update([
            'phone' => $phone,
        ]);
    }

    public function getUserByChatId(int $chatId, bool $validatePhone = false): User
    {
        $user = User::query()->with('state')->whereTelegramId($chatId)->first();

        if (is_null($user) || (is_null($user->phone) && $validatePhone)) {
            throw new InvalidArgumentException('User without phone');
        }

        return $user;
    }

    public function getUserByPhone(string $phone): User
    {
        return User::query()->wherePhone($phone)->firstOrFail();
    }

    public function findById(string $userId): User
    {
        return User::query()->where('id', $userId)->firstOrFail();
    }
}
