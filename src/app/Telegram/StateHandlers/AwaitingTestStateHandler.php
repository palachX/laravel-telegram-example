<?php

declare(strict_types=1);

namespace App\Telegram\StateHandlers;

use App\Enums\UserStateEnum;
use App\Models\UserState;
use App\Repositories\UserRepository;
use App\Services\Telegram\MessagePayload;
use App\Services\Telegram\TelegramService;
use App\Telegram\Interfaces\BaseHandler;
use Illuminate\Http\Client\ConnectionException;
use InvalidArgumentException;
use JsonException;
use Spatie\LaravelData\Data;

final readonly class AwaitingTestStateHandler implements BaseHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private TelegramService $telegramService
    ) {
    }

    /**
     * @param  StateHandlerDataInput  $data
     *
     * @throws ConnectionException
     * @throws JsonException
     */
    public function handle(int $chatId, Data $data): void
    {
        $user = $this->userRepository->getUserByChatId($chatId);
        $this->checkState($user->state);

        $this->telegramService->sendMessage(new MessagePayload(
            chatId: $chatId,
            text: 'Тестовое состояние можете обработать как вам хочется'
        ));
    }

    private function checkState(?UserState $userState): void
    {
        if (is_null($userState) || $userState->state !== UserStateEnum::AWAITING_TEST) {
            throw new InvalidArgumentException('Error state user code');
        }
    }
}
