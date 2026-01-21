<?php

declare(strict_types=1);

use App\Telegram\CommandHandlers\StartCommandHandler;
use App\Telegram\StateHandlers\AwaitingCodeStateHandler;
use App\Telegram\StateHandlers\AwaitingPhoneStateHandler;
use App\Telegram\StateHandlers\AwaitingTestStateHandler;

return [

    'telegram_url' => env('TG_URL'),

    'bot_token' => env('TG_TOKEN'),

    'commands' => [
        'start' => [
            'description' => 'Команда /start',
            'handler' => StartCommandHandler::class,
        ],
    ],

    'states' => [
        'awaiting_phone' => [
            'description' => 'Ожидание номера телефона',
            'handler' => AwaitingPhoneStateHandler::class,
        ],
        'awaiting_code' => [
            'description' => 'Ожидание кода подтверждения',
            'handler' => AwaitingCodeStateHandler::class,
        ],
        'awaiting_test' => [
            'description' => 'Тестовое состояние',
            'handler' => AwaitingTestStateHandler::class,
        ],
    ],

];
