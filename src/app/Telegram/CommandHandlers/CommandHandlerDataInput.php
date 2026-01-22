<?php

declare(strict_types=1);

namespace App\Telegram\CommandHandlers;

use App\Telegram\DTO\UserData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class CommandHandlerDataInput extends Data
{
    public function __construct(
        public readonly int $messageId,
        public readonly UserData $from,
        public readonly string $text,
    ) {
    }
}
