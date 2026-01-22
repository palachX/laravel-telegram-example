<?php

declare(strict_types=1);

namespace App\Telegram\Factories\CommandHandlerFactory;

use App\Exceptions\UnknownCommandException;
use App\Factories\Interfaces\HandlerFactory;
use App\Telegram\CommandHandlers\CommandHandlerDataInput;
use App\Telegram\Interfaces\BaseHandler;
use App\UseCases\V1\TelegramWebhook\DataInput as TelegramWebhookDataInput;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;
use Spatie\LaravelData\Data;

final readonly class CommandsHandlersFactory implements HandlerFactory
{
    /**
     * @param  array<string, array{handler: class-string<BaseHandler>}>  $commands
     */
    public function __construct(
        private array $commands,
    ) {
    }

    /**
     * @param  DataInput  $data
     *
     * @throws UnknownCommandException
     * @throws BindingResolutionException
     */
    public function make(Data $data): BaseHandler
    {
        $text = $data->message;

        $commandName = $this->getCommandName($text);

        if (! isset($this->commands[$commandName])) {
            throw new UnknownCommandException($commandName);
        }

        /** @var class-string<BaseHandler> $handlerClass */
        $handlerClass = $this->commands[$commandName]['handler'];

        if (! class_exists($handlerClass)) {
            throw new UnknownCommandException($commandName, "Command class {$handlerClass} does not exist");
        }

        /**
         * @var BaseHandler $handler
         */
        $handler = App::make($handlerClass);

        return $handler;
    }

    public function createHandlerDataInput(TelegramWebhookDataInput $data): Data
    {
        if (empty($data->message) || empty($data->message->text)) {
            throw new InvalidArgumentException(__('Invalid message'));
        }

        return new CommandHandlerDataInput(
            messageId: $data->message->messageId,
            from: $data->message->from,
            text: $data->message->text,
        );
    }

    private function getCommandName(string $text): string
    {
        $parts = explode(' ', $text);

        return strtolower(str_replace('/', '', $parts[0]));
    }
}
