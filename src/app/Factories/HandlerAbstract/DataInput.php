<?php

declare(strict_types=1);

namespace App\Factories\HandlerAbstract;

use App\Telegram\DTO\Contact;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class DataInput extends Data
{
    public function __construct(
        public readonly int $userId,
        public readonly ?string $text = null,
        public readonly ?string $callbackData = null,
        public readonly ?Contact $contact = null
    ) {
    }
}
