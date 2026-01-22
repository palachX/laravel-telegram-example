<?php

declare(strict_types=1);

namespace App\Telegram\StateHandlers;

use App\DTO\UserCode;
use App\Enums\UserStateEnum;
use App\Repositories\UserCodePhoneRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserStateRepository;
use App\Telegram\Interfaces\BaseHandler;
use InvalidArgumentException;
use Spatie\LaravelData\Data;

//TODO тут вы можете реализовать свою отправку кода через удобный для вас сервис
final readonly class AwaitingPhoneStateHandler implements BaseHandler
{
    private const string CODE = '+7';

    private const int MIN_LENGTH = 10;

    private const int MAX_LENGTH = 11;

    public function __construct(
        private UserCodePhoneRepository $codePhoneRepository,
        private UserRepository $userRepository,
        private UserStateRepository $userStateRepository,
    ) {
    }

    /**
     * @param  StateHandlerDataInput  $data
     */
    public function handle(int $chatId, Data $data): void
    {
        $user = $this->userRepository->getUserByChatId($chatId);
        $phone = $this->normalizePhone($data->text);
        /**
         * Test code
         */
        $code = 1234;

        $this->codePhoneRepository->setCode(new UserCode(
            userId: $user->id,
            phone: $phone,
            chatId: $chatId,
            code: $code
        ));

        $this->userStateRepository->store($user->id, UserStateEnum::AWAITING_CODE);
    }

    private function normalizePhone(string $phone): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        if ($cleaned === null) {
            throw new InvalidArgumentException('Invalid phone');
        }

        $length = mb_strlen($cleaned);
        if ($length < self::MIN_LENGTH || $length > self::MAX_LENGTH) {
            throw new InvalidArgumentException('Invalid phone');
        }

        if (($length === 11) && (str_starts_with($cleaned, '7') || str_starts_with($cleaned, '8'))) {
            return self::CODE.mb_substr($cleaned, 1);
        }

        if ($length === 10 && preg_match('/^9[0-9]{9}$/', $cleaned)) {
            return self::CODE.$cleaned;
        }

        throw new InvalidArgumentException('Invalid phone');
    }
}
