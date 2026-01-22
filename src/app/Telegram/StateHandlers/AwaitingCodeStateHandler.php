<?php

declare(strict_types=1);

namespace App\Telegram\StateHandlers;

use App\Enums\UserStateEnum;
use App\Models\UserState;
use App\Repositories\UserCodePhoneRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserStateRepository;
use App\Repositories\UserTokenRepository;
use App\Telegram\DTO\MessagePayload;
use App\Telegram\Interfaces\BaseHandler;
use App\Telegram\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use JsonException;
use Spatie\LaravelData\Data;
use Throwable;

//TODO тут вы можете реализовать свою авторизацию пользователя при подтверждении кода
final readonly class AwaitingCodeStateHandler implements BaseHandler
{
    public function __construct(
        private UserCodePhoneRepository $codePhoneRepository,
        private UserRepository $userRepository,
        private UserStateRepository $userStateRepository,
        private TelegramService $telegramService,
        private UserTokenRepository $userTokenRepository
    ) {
    }

    /**
     * @param  StateHandlerDataInput  $data
     *
     * @throws JsonException
     * @throws ConnectionException
     * @throws Throwable
     */
    public function handle(int $chatId, Data $data): void
    {
        DB::transaction(function () use ($data, $chatId) {
            $user = $this->userRepository->getUserByChatId($chatId);

            $this->checkState($user->state);

            $userCode = $this->codePhoneRepository->getCode($user->id, $chatId);

            $this->verificationAttempt((int) $data->text, $userCode->code, $user->id);

            $this->userStateRepository->store($user->id, UserStateEnum::AWAITING_TEST);

            $user->updatePhoneNumber($userCode->phone);

            $this->telegramService->sendMessage(new MessagePayload(
                chatId: $chatId,
                text: 'Код успешно подтверждён, токен создан можете открывать mini-app'
            ));
        });
    }

    private function checkState(?UserState $userState): void
    {
        if (is_null($userState) || $userState->state !== UserStateEnum::AWAITING_CODE) {
            throw new InvalidArgumentException('Error state user code');
        }
    }

    private function verificationAttempt(int $messageCode, int $cacheCode, string $userId): void
    {
        if ($cacheCode !== $messageCode) {
            throw new InvalidArgumentException('Code not attempt');
        }

        $this->userTokenRepository->updateOrCreate(
            userId: $userId,
            token: Str::random(60),
            expiresAt: Carbon::now()->addMonth()
        );
    }
}
