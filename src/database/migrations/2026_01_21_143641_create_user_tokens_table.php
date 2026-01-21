<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->unique()
                ->comment('Идентификатор пользователя')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->text('token')->comment('Засекреченный токен');
            $table->dateTime('expires_at')->nullable()->comment('Время истечения токена');
            $table->timestamps();

            $table->comment('Таблица для хранения токенов пользователей');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_tokens');
    }
};
