<?php

declare(strict_types=1);

namespace App\UseCases\V1\TelegramWebhook;

use App\Exceptions\UnknownCommandException;
use App\Factories\HandlerAbstract\DataInput as AbstractFactoryDataInput;
use App\Factories\HandlerAbstract\HandlerAbstractFactory;
use App\Services\Telegram\MessagePayload;
use App\Services\Telegram\TelegramService;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

final readonly class Handler
{
    public function __construct(
        private HandlerAbstractFactory $abstractFactory,
        private TelegramService $telegramService,
    ) {
    }

    public function handle(DataInput $data): void
    {
        try {
            $factory = $this->abstractFactory->make(new AbstractFactoryDataInput(
                $data->chatId,
                $data->message?->text,
                $data->callbackQuery?->data,
                $data->message?->contact
            ));

            $dtoHandlerFactory = $this->abstractFactory->createFactoryDataInput($factory, $data);
            $handler = $factory->make($dtoHandlerFactory);

            $handler->handle($data->chatId, $factory->createHandlerDataInput($data));
        } catch (UnknownCommandException $e) {
            $this->handleUnknownCommandException($data, $e);
        } catch (InvalidArgumentException $e) {
            $this->handleValidationException($data, $e);
        } catch (Throwable $e) {
            $this->handleUnexpectedException($data, $e);
        }
    }

    private function handleUnknownCommandException(DataInput $data, UnknownCommandException $e): void
    {
        if (! $data->message || ! $data->message->text) {
            Log::error('Telegram message not found', [
                'user_id' => $data->chatId,
                'error' => $e->getMessage(),
            ]);

            return;
        }

        Log::error('Telegram command not found', [
            'user_id' => $data->chatId,
            'error' => $e->getMessage(),
            'command' => $e->getCommandName(),
        ]);

        $this->safeSendMessage($data->chatId, $e->getMessage());
    }

    private function handleValidationException(DataInput $data, Throwable $e): void
    {
        $this->safeSendMessage($data->chatId, $e->getMessage());
    }

    private function handleUnexpectedException(DataInput $data, \Throwable $e): void
    {
        Log::error('Response handling error', [
            'user_id' => $data->chatId,
            'error' => $e->getMessage(),
        ]);

        $this->safeSendMessage($data->chatId, 'Error message');
    }

    private function safeSendMessage(int $chatId, string $message): void
    {
        try {
            $this->telegramService->sendMessage(new MessagePayload(
                chatId: $chatId,
                text: $message,
            ));
        } catch (Throwable $e) {
            Log::warning('Failed to send Telegram message', [
                'user_id' => $chatId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
