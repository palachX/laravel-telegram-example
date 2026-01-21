<?php

declare(strict_types=1);

namespace App\UseCases\V1\TelegramInitData;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Resource;

#[MapName(SnakeCaseMapper::class)]
final class DataOutput extends Resource
{
    public function __construct(
        public readonly string $token,
        public readonly ?Carbon $expiresAt,
    ) {
    }
}
