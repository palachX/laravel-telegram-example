<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\DTO\Telegram\InlineKeyboardMarkup;
use App\DTO\Telegram\ReplyKeyboardMarkup;
use JsonException;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class MessagePayload extends Data
{
    public function __construct(
        public readonly int $chatId,
        public readonly string $text,
        public readonly InlineKeyboardMarkup|ReplyKeyboardMarkup|null $replyMarkup = null,
        public readonly string $parseMode = 'HTML',
    ) {
    }

    /**
     * @return array<string, mixed>
     *
     * @throws JsonException
     */
    public function toClearData(): array
    {
        $json = $this->toJson();

        /** @var array<string, mixed> $data */
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return $this->removeNullValuesRecursive($data);
    }

    /**
     * @param  array<string, mixed>  $array
     * @return array<string, mixed>
     */
    private function removeNullValuesRecursive(array $array): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                /**
                 * @var array<string, mixed> $value
                 */
                $processed = $this->removeNullValuesRecursive($value);

                if (! empty($processed)) {
                    $result[$key] = $processed;
                }
            } elseif ($value !== null) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
