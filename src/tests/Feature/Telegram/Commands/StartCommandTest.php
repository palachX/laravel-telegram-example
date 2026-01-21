<?php

declare(strict_types=1);

namespace Telegram\Commands;

use App\Enums\UserStateEnum;
use App\Models\User;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\ApiTestCase;

final class StartCommandTest extends ApiTestCase
{
    private const string URL_TELEGRAM_WEBHOOK = '/api/v1/telegram/webhook/';

    private string $tgBotUrl;

    private string $tgBotToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tgBotUrl = config('telegram-bot.telegram_url');
        $this->tgBotToken = config('telegram-bot.bot_token');
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
                    'text' => '/start',
                ],
            ],
        ];
    }

    #[DataProvider('dataSuccessProvider')]
    public function testSuccessCommand(array $data): void
    {
        $response = $this->postJson(self::URL_TELEGRAM_WEBHOOK, $data);
        $response->assertOk();

        $this->assertDatabaseHas('users', [
            'telegram_id' => $data['message']['from']['id'],
            'first_name' => $data['message']['from']['first_name'],
            'last_name' => $data['message']['from']['last_name'],
            'username' => $data['message']['from']['username'],
        ]);

        $this->assertDatabaseHas('user_states', [
            'user_id' => User::query()->where('telegram_id', $data['message']['from']['id'])->first()->id,
            'state' => UserStateEnum::AWAITING_PHONE,
        ]);
    }
}
