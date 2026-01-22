<?php

declare(strict_types=1);

namespace App\Telegram\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * @phpstan-type InlineKeyboardRows array<
 *     int,
 *     array<int, InlineKeyboardButton>
 * >
 */
#[MapName(SnakeCaseMapper::class)]
final class InlineKeyboardMarkup extends Data
{
    /**
     * @param  InlineKeyboardRows  $inlineKeyboard
     */
    public function __construct(
        public array $inlineKeyboard
    ) {
    }
}
