<?php

declare(strict_types=1);

namespace App\Factories\HandlerAbstract;

use App;
use App\Telegram\Factories\CommandHandlerFactory\CommandsHandlersFactory;
use App\Telegram\Factories\CommandHandlerFactory\DataInput as CommandHandlerFactoryDataInput;
use App\Telegram\Factories\StateHandlerFactory\DataInput as StateHandlerFactoryDataInput;
use App\Telegram\Factories\StateHandlerFactory\StatesHandlersFactory;
use App\Telegram\Interfaces\HandlerFactory;
use App\UseCases\V1\TelegramWebhook\DataInput as TelegramWebhookDataInput;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Spatie\LaravelData\Data;

final readonly class HandlerAbstractFactory
{
    /**
     * @throws BindingResolutionException
     */
    public function make(DataInput $data): HandlerFactory
    {
        $text = $data->text ?: $data->callbackData;

        if ($text === null) {
            throw new \LogicException(__('Telegram text message null'));
        }

        if (str_starts_with($text, '/')) {
            /**
             * @var CommandsHandlersFactory $factory
             */
            $factory = App::make(CommandsHandlersFactory::class);

            return $factory;
        }

        /**
         * @var StatesHandlersFactory $factory
         */
        $factory = App::make(StatesHandlersFactory::class);

        return $factory;
    }

    /**
     * @throws Exception
     */
    public function createFactoryDataInput(HandlerFactory $factory, TelegramWebhookDataInput $dataInput): Data
    {
        $message = $dataInput->message;

        return match (get_class($factory)) {
            StatesHandlersFactory::class => StateHandlerFactoryDataInput::createFromMessage($dataInput->message),
            CommandsHandlersFactory::class => CommandHandlerFactoryDataInput::createFromMessage($dataInput->message),
            default => throw new Exception('Unexpected match value'),
        };
    }
}
