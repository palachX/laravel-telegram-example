<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')
                ->primary();

            $table->char('phone', 12)
                ->nullable()
                ->index()
                ->comment('Номер телефона пользователя');

            $table->id('telegram_id')
                ->index()
                ->comment('ID пользователя в Telegram');

            $table->string('username')
                ->index()
                ->nullable()
                ->comment('Ник пользователя в телеграме');

            $table->string('first_name')
                ->nullable()->comment('Имя пользователя в телеграме');

            $table->string('last_name')
                ->nullable()->comment('Фамилия пользователя в телеграме');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
