<?php

declare(strict_types=1);

namespace Telegram\States;

use App\Enums\UserStateEnum;
use App\Models\User;
use App\Models\UserState;
use Cache;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\ApiTestCase;
use Tests\TelegramAsserts;

final class AwaitingPhoneStateTest extends ApiTestCase
{
    use TelegramAsserts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->telegramSetup();
        Cache::flush();
    }

    public static function dataSuccessProvider(): iterable
    {
        yield [
            'data' => [
                'update_id' => 100000001,
                'message' => [
                    'message_id' => 1,
                    'from' => [
                        'id' => 123456789,
                        'is_bot' => false,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'username' => 'johndoe',
                        'language_code' => 'ru',
                    ],
                    'chat' => [
                        'id' => 123456789,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'username' => 'johndoe',
                        'type' => 'private',
                    ],
                    'date' => time(),
                    'text' => '+79995556644',
                ],
            ],
        ];
    }

    #[DataProvider('dataSuccessProvider')]
    public function testSuccessCommand(array $data): void
    {
        $user = User::factory([
            'telegram_id' => $data['message']['from']['id'],
            'phone' => null,
        ])->createOne();

        UserState::factory([
            'state' => UserStateEnum::AWAITING_PHONE,
        ])->for($user)->createOne();

        $this->httpFake(self::URL_TG_SEND_MESSAGE, [
            'ok' => true,
            'result' => [
                'message_id' => 123,
                'chat' => ['id' => 123456789],
            ],
        ]);

        $response = $this->postJson(self::URL_TELEGRAM_WEBHOOK, $data);
        $response->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'phone' => null,
        ]);

        $this->assertDatabaseHas('user_states', [
            'user_id' => $user->id,
            'state' => UserStateEnum::AWAITING_CODE,
        ]);
    }
}
