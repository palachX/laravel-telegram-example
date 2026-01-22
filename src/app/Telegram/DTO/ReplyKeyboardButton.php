<?php

declare(strict_types=1);

namespace App\Telegram\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class ReplyKeyboardButton extends Data
{
    public function __construct(
        public string $text,
        public bool $requestContact = false,
        public bool $requestLocation = false,
    ) {
        $requestCount = (int) $this->requestContact + (int) $this->requestLocation;

        if ($requestCount > 1) {
            throw new \InvalidArgumentException(
                'ReplyKeyboardButton can have only one of: requestContact, requestLocation, or requestPoll'
            );
        }
    }
}
