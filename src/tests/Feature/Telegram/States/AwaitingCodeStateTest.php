<?php

declare(strict_types=1);

namespace Telegram\States;

use App\DTO\UserCode;
use App\Enums\UserStateEnum;
use App\Models\User;
use App\Models\UserState;
use Cache;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\ApiTestCase;
use Tests\TelegramSetup;

final class AwaitingCodeStateTest extends ApiTestCase
{
    use TelegramSetup;

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
                    'text' => '1234',
                ],
            ],
        ];
    }

    #[DataProvider('dataSuccessProvider')]
    public function testSuccessCommand(array $data): void
    {
        $phone = '+79058889966';

        $user = User::factory([
            'telegram_id' => $data['message']['from']['id'],
            'phone' => null,
        ])->createOne();

        UserState::factory([
            'state' => UserStateEnum::AWAITING_CODE,
        ])->for($user)->createOne();

        $userCode = new UserCode(
            userId: $user->id,
            phone: $phone,
            chatId: $user->telegram_id,
            code: 1234
        );

        Cache::put('user_code:'.md5($user->id.$user->telegram_id), $userCode);

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
            'phone' => $phone,
        ]);

        $this->assertDatabaseHas('user_states', [
            'user_id' => $user->id,
            'state' => UserStateEnum::AWAITING_TEST,
        ]);

        $this->assertDatabaseHas('user_tokens', [
            'user_id' => $user->id,
            'expires_at' => Carbon::now()->addMonth(),
        ]);
    }
}
