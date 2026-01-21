<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\UserCode;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use JsonException;

final class UserCodePhoneRepository
{
    private const int TTL = 3600;

    private const string KEY = 'user_code:';

    /**
     * @throws JsonException
     */
    public function setCode(UserCode $userCode): bool
    {
        return Cache::set($this->getCacheKey($userCode->userId, $userCode->chatId), $userCode, self::TTL);
    }

    /**
     * @throws JsonException
     */
    public function getCode(string $userId, int $chatId): UserCode
    {
        /** @var ?UserCode $data */
        $data = Cache::get($this->getCacheKey($userId, $chatId));

        if (is_null($data)) {
            throw new InvalidArgumentException('Not found code');
        }

        return $data;
    }

    /**
     * @throws JsonException
     */
    private function getCacheKey(string $userId, int $chatId): string
    {
        return self::KEY.md5($userId.$chatId);
    }
}
