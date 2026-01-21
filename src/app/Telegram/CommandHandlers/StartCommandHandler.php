<?php

declare(strict_types=1);

namespace App\Telegram\CommandHandlers;

use App\Enums\UserStateEnum;
use App\Repositories\UserRepository;
use App\Repositories\UserStateRepository;
use App\Telegram\Interfaces\BaseHandler;
use Spatie\LaravelData\Data;

final readonly class StartCommandHandler implements BaseHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private UserStateRepository $userStateRepository,
    ) {
    }

    /**
     * @param  CommandHandlerDataInput  $data
     */
    public function handle(int $chatId, Data $data): void
    {
        $user = $this->userRepository->firstOrCreate($data->from);

        $this->userStateRepository->store($user->id, UserStateEnum::AWAITING_PHONE);
    }
}
