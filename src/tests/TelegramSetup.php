<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Http;

trait TelegramSetup
{
    private const string URL_TELEGRAM_WEBHOOK = '/api/v1/telegram/webhook/';

    private const string URL_TG_SEND_MESSAGE = 'sendMessage';

    private string $tgBotUrl;

    private string $tgBotToken;

    public function telegramSetup(): void
    {
        $this->tgBotUrl = config('telegram-bot.telegram_url');
        $this->tgBotToken = config('telegram-bot.bot_token');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function httpFake(string $url, array $data, ?int $status = 200): void
    {
        Http::fake([
            $this->tgBotUrl."/bot$this->tgBotToken/".$url => Http::response($data, $status),
        ]);
    }
}
