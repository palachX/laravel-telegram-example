<?php

declare(strict_types=1);

namespace App\Telegram\StateHandlers;

use App\Telegram\Interfaces\BaseHandler;
use Spatie\LaravelData\Data;

final class AwaitingCodeStateHandler implements BaseHandler
{
    /**
     * @param  StateHandlerDataInput  $data
     */
    public function handle(int $chatId, Data $data): void
    {
        // TODO: Implement handle() method.
    }
}
