<?php

declare(strict_types=1);

namespace App\DTO\Telegram;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class Contact extends Data
{
    public function __construct(
        public readonly string $phoneNumber,
        public readonly string $firstName,
        public readonly ?string $lastName = null,
        public readonly ?int $userId = null,
    ) {
    }
}
