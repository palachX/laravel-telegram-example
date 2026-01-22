<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Repositories\UserStateRepository;
use App\Repositories\UserTokenRepository;
use App\Telegram\Factories\CommandHandlerFactory\CommandsHandlersFactory;
use App\Telegram\Factories\StateHandlerFactory\StatesHandlersFactory;
use App\Telegram\Interfaces\BaseHandler;
use App\Telegram\Services\TelegramService;
use App\UseCases\V1\TelegramInitData\Handler as InitDataHandler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(TelegramService::class, function () {
            /** @var string $apiUrl */
            $apiUrl = config('telegram-bot.telegram_url');
            /** @var string $botToken */
            $botToken = config('telegram-bot.bot_token');

            return new TelegramService($apiUrl, $botToken);
        });

        $this->app->bind(StatesHandlersFactory::class, function () {
            /** @var array<array-key, array{handler: class-string<BaseHandler>}> $states */
            $states = config('telegram-bot.states');

            return new StatesHandlersFactory(app(UserStateRepository::class), $states);
        });

        $this->app->bind(CommandsHandlersFactory::class, function () {
            /** @var array<array-key, array{handler: class-string<BaseHandler>}> $commands */
            $commands = config('telegram-bot.commands');

            return new CommandsHandlersFactory($commands);
        });

        $this->app->bind(InitDataHandler::class, function () {
            /** @var string $botToken */
            $botToken = config('telegram-bot.bot_token');

            return new InitDataHandler(app(UserRepository::class), app(UserTokenRepository::class), $botToken);
        });
    }
}
