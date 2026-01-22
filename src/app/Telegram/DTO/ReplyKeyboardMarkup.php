<?php

declare(strict_types=1);

namespace App\Telegram\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * @phpstan-type ReplyKeyboardRows array<
 *     int,
 *     array<int, ReplyKeyboardButton>
 * >
 */
#[MapName(SnakeCaseMapper::class)]
final class ReplyKeyboardMarkup extends Data
{
    /**
     * @param  ReplyKeyboardRows  $keyboard
     */
    public function __construct(
        public array $keyboard,
        public bool $resizeKeyboard = true,
        public bool $oneTimeKeyboard = true,
        public ?bool $selective = null,
    ) {
    }
}
