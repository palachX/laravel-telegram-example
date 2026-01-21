<?php

declare(strict_types=1);

namespace App\Factories\Telegram\CommandHandlerFactory;

use App\DTO\Telegram\TelegramMessage;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class DataInput extends Data
{
    public function __construct(
        public readonly string $message,
        public readonly int $userId
    ) {
    }

    public static function createFromMessage(?TelegramMessage $data): self
    {
        if (is_null($data) || is_null($data->text)) {
            throw new \LogicException('Message cannot be null for command factory');
        }

        return new self(
            message: $data->text,
            userId: $data->from->id
        );
    }
}
