<?php

declare(strict_types=1);

namespace App\DTO\Telegram;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class CallbackQuery extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly UserData $from,
        public readonly string $data
    ) {
    }
}
