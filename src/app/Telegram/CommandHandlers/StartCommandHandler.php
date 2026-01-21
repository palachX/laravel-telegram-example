<?php

declare(strict_types=1);

namespace App\Telegram\CommandHandlers;

use App\Enums\UserStateEnum;
use App\Repositories\UserRepository;
use App\Repositories\UserStateRepository;
use App\Services\Telegram\MessagePayload;
use App\Services\Telegram\TelegramService;
use App\Telegram\Interfaces\BaseHandler;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\Data;
use Throwable;

final readonly class StartCommandHandler implements BaseHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private UserStateRepository $userStateRepository,
        private TelegramService $telegramService
    ) {
    }

    /**
     * @param  CommandHandlerDataInput  $data
     *
     * @throws Throwable
     */
    public function handle(int $chatId, Data $data): void
    {
        DB::transaction(function () use ($data, $chatId) {
            $user = $this->userRepository->firstOrCreate($data->from);
            $this->userStateRepository->store($user->id, UserStateEnum::AWAITING_PHONE);
            $this->telegramService->sendMessage(
                new MessagePayload($chatId, 'Ожидаем ваш номер телефона')
            );
        });
    }
}
