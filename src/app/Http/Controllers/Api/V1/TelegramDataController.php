<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\UseCases\V1\TelegramInitData\DataInput;
use App\UseCases\V1\TelegramInitData\Handler;
use Illuminate\Http\JsonResponse;
use JsonException;

class TelegramDataController extends Controller
{
    /**
     * @throws JsonException
     */
    public function handle(DataInput $data, Handler $handler): JsonResponse
    {
        return new JsonResponse(['data' => $handler->handle($data)]);
    }
}
