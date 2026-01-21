<?php

declare(strict_types=1);

namespace App\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class UserCode extends Data
{
    public function __construct(
        public readonly string $userId,
        public readonly string $phone,
        public readonly int $chatId,
        public readonly int $code,
    ) {
    }
}
