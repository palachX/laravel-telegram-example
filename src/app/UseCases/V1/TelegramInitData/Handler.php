<?php

declare(strict_types=1);

namespace App\UseCases\V1\TelegramInitData;

use App\Repositories\UserRepository;
use App\Repositories\UserTokenRepository;
use InvalidArgumentException;
use JsonException;

final readonly class Handler
{
    public function __construct(
        private UserRepository $userRepository,
        private UserTokenRepository $userTokenRepository,
        private string $botToken,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function handle(DataInput $dataInput): DataOutput
    {
        $initData = $dataInput->initData;

        /**
         * @var array{
         *     query_id?: string,
         *     user?: string,
         *     auth_date?: string,
         *     hash?: string,
         *     signature?: string,
         *     chat_instance?: string,
         *     chat_type?: string,
         *     start_param?: string,
         *     can_send_after?: string
         * } $parsed
         */
        $parsed = [];

        parse_str($initData, $parsed);

        if (! isset($parsed['hash'])) {
            throw new InvalidArgumentException('Invalid init data hash', 400);
        }

        /** @var string $hash */
        $hash = $parsed['hash'];

        $this->assertValidHash(
            initData: $initData,
            receivedHash: $hash,
            token: $this->botToken,
        );

        if (! isset($parsed['user'])) {
            throw new InvalidArgumentException('Invalid init data user', 400);
        }

        /** @var string $user */
        $user = $parsed['user'];

        /**
         * @var array{
         *     id: int,
         *     is_bot: bool,
         *     first_name?: string,
         *     last_name?: string,
         *     username?: string
         * } $userDecode
         */
        $userDecode = json_decode($user, true, 512, JSON_THROW_ON_ERROR);
        $user = $this->userRepository->getUserByChatId($userDecode['id']);

        $tokenData = $this->userTokenRepository->getByUserId($user->id);

        if ($tokenData === null) {
            throw new InvalidArgumentException('Invalid init data token', 401);
        }

        return new DataOutput(
            token: $tokenData->token,
            expiresAt: $tokenData->expires_at
        );
    }

    private function assertValidHash(string $initData, string $receivedHash, string $token): void
    {
        /**
         * @var array<string, string> $data
         */
        $data = [];
        parse_str($initData, $data);
        unset($data['hash']);

        ksort($data);

        $checkString = collect($data)
            ->map(function ($value, $key) {
                if (is_array($value)) {
                    $value = json_encode($value, JSON_THROW_ON_ERROR);
                }

                return $key.'='.$value;
            })
            ->implode("\n");

        $secretKey = hash_hmac('sha256', $token, 'WebAppData', true);
        $calculatedHash = bin2hex(hash_hmac('sha256', $checkString, $secretKey, true));

        if (! hash_equals($calculatedHash, $receivedHash)) {
            throw new InvalidArgumentException('Invalid init data hash', 400);
        }
    }
}
