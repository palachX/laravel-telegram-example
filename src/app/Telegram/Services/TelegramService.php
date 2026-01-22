<?php

declare(strict_types=1);

namespace App\Telegram\Services;

use App\Telegram\DTO\MessagePayload;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use JsonException;

final readonly class TelegramService
{
    private const string SEND_MESSAGE_URL = '/sendMessage';

    public function __construct(
        private string $apiUrl,
        private string $botToken,
    ) {
    }

    /**
     * @throws ConnectionException
     * @throws JsonException
     */
    public function sendMessage(MessagePayload $request): Response
    {
        $data = $request->toClearData();

        return Http::post("$this->apiUrl/bot$this->botToken".self::SEND_MESSAGE_URL, $data);
    }
}
