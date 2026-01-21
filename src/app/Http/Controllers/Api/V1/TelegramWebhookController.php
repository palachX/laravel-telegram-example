<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\UseCases\V1\TelegramWebhook\DataInput;
use App\UseCases\V1\TelegramWebhook\Handler;
use Illuminate\Http\JsonResponse;

class TelegramWebhookController extends Controller
{
    public function handle(DataInput $data, Handler $handler): JsonResponse
    {
        $handler->handle($data);

        return new JsonResponse(['status' => 'ok']);
    }
}
