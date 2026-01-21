<?php

declare(strict_types=1);

namespace App\Factories\Telegram\StateHandlerFactory;

use App\Exceptions\UnknownStateException;
use App\Factories\Interfaces\HandlerFactory;
use App\Repositories\UserStateRepository;
use App\Telegram\Interfaces\BaseHandler;
use App\Telegram\StateHandlers\StateHandlerDataInput;
use App\UseCases\V1\TelegramWebhook\DataInput as TelegramWebhookDataInput;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;
use Spatie\LaravelData\Data;

final readonly class StatesHandlersFactory implements HandlerFactory
{
    /**
     * @param  array<array-key, array{handler: class-string<BaseHandler>}>  $handlers
     */
    public function __construct(
        private UserStateRepository $repository,
        private array $handlers
    ) {
    }

    /**
     * @param  DataInput  $data
     *
     * @throws UnknownStateException
     * @throws BindingResolutionException
     */
    public function make(Data $data): BaseHandler
    {
        $state = $this->repository->getStateByChatId($data->userId);

        $stateValue = $state->state->value;

        $stateConfig = $this->handlers[$stateValue] ?? null;

        if (! isset($stateConfig)) {
            throw new UnknownStateException($stateValue);
        }

        /** @var class-string<BaseHandler> $handlerClass */
        $handlerClass = $stateConfig['handler'];

        return App::make($handlerClass);
    }

    public function createHandlerDataInput(TelegramWebhookDataInput $data): Data
    {
        if (empty($data->message) || empty($data->message->text)) {
            throw new InvalidArgumentException(__('telegram.invalid_message'));
        }

        return new StateHandlerDataInput(
            messageId: $data->message->messageId,
            from: $data->message->from,
            text: $data->message->text,
        );
    }
}
