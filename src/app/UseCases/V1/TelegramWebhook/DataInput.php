<?php

declare(strict_types=1);

namespace App\UseCases\V1\TelegramWebhook;

use AllowDynamicProperties;
use App\Telegram\DTO\CallbackQuery;
use App\Telegram\DTO\TelegramMessage;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * @property int $chatId
 */
#[AllowDynamicProperties]
#[MapName(SnakeCaseMapper::class)]
final class DataInput extends Data
{
    public function __construct(
        public readonly int $updateId,
        public ?TelegramMessage $message = null,
        public ?CallbackQuery $callbackQuery = null
    ) {
        $this->chatId = match (true) {
            $message !== null => $message->from->id,
            $callbackQuery !== null => $callbackQuery->from->id,
            default => throw new \InvalidArgumentException(
                'Either message or callbackQuery must be provided'
            ),
        };
    }
}
