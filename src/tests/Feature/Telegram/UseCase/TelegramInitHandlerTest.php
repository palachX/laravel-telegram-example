<?php

declare(strict_types=1);

namespace Telegram\UseCase;

use App\Models\User;
use App\Models\UserToken;
use Tests\ApiTestCase;
use Tests\TelegramSetup;

final class TelegramInitHandlerTest extends ApiTestCase
{
    use TelegramSetup;

    private const string URL_DATA = '/api/v1/telegram/init-data';

    protected function setUp(): void
    {
        parent::setUp();

        $this->telegramSetup();
    }

    public function testSuccess(): void
    {
        $user = User::factory()->createOne();

        UserToken::factory()->for($user)->createOne();

        $authDate = now()->timestamp;

        $payload = [
            'query_id' => 'AAHdF6IQAAAAAN0XohDhrOrc',
            'user' => [
                'id' => $user->telegram_id,
                'is_bot' => false,
                'username' => $user->username,
            ],
            'auth_date' => $authDate,
        ];
        ksort($payload);

        $dataForHash = collect($payload)
            ->map(function ($value, $key) {
                if (is_array($value)) {
                    $value = json_encode($value, JSON_THROW_ON_ERROR);
                }

                return $key.'='.$value;
            })
            ->implode("\n");

        $secretKey = hash_hmac('sha256', $this->tgBotToken, 'WebAppData', true);
        $hash = bin2hex(hash_hmac('sha256', $dataForHash, $secretKey, true));

        $initDataBase = http_build_query([
            'query_id' => $payload['query_id'],
            'user' => json_encode($payload['user'], JSON_THROW_ON_ERROR),
            'auth_date' => $payload['auth_date'],
        ]);

        $initData = $initDataBase.'&hash='.$hash;

        $this->postJson(self::URL_DATA, [
            'init_data' => $initData,
        ])->assertOk();
    }
}
