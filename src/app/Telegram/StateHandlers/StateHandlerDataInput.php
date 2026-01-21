<?php

declare(strict_types=1);

namespace App\Telegram\StateHandlers;

use App\DTO\Telegram\UserData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class StateHandlerDataInput extends Data
{
    public function __construct(
        public readonly int $messageId,
        public readonly UserData $from,
        public readonly string $text,
    ) {
    }
}
