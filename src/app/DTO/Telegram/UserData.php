<?php

declare(strict_types=1);

namespace App\DTO\Telegram;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class UserData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly bool $isBot,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
        public readonly ?string $username = null
    ) {
    }
}
