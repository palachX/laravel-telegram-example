<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\TelegramDataController;
use App\Http\Controllers\Api\V1\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('telegram')->group(function () {
        Route::post('webhook', [TelegramWebhookController::class, 'handle']);
        Route::post('init-data', [TelegramDataController::class, 'handle']);
    });
});
