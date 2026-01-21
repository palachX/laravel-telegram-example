<?php

declare(strict_types=1);

namespace App\Telegram\Interfaces;

use Spatie\LaravelData\Data;

interface BaseHandler
{
    public function handle(int $chatId, Data $data): void;
}
