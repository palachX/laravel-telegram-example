<?php

declare(strict_types=1);

namespace App\Telegram\Factories\StateHandlerFactory;

use App\Telegram\DTO\TelegramMessage;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class DataInput extends Data
{
    public function __construct(
        public readonly int $userId
    ) {
    }

    public static function createFromMessage(?TelegramMessage $data): self
    {
        if ($data === null) {
            throw new \LogicException('Message cannot be null for State factory');
        }

        return new self(
            userId: $data->from->id
        );
    }
}
