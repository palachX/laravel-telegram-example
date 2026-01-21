<?php

declare(strict_types=1);

namespace App\Factories\Interfaces;

use App\Telegram\Interfaces\BaseHandler;
use App\UseCases\V1\TelegramWebhook\DataInput;
use Spatie\LaravelData\Data;

interface HandlerFactory
{
    public function make(Data $data): BaseHandler;

    public function createHandlerDataInput(DataInput $data): Data;
}
