<?php

declare(strict_types=1);

namespace App\Telegram\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class WebAppInfo
{
    public function __construct(
        public string $url
    ) {
    }
}
