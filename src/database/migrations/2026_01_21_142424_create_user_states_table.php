<?php

declare(strict_types=1);

use App\Enums\UserStateEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_states', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->unique()
                ->comment('Идентификатор пользователя')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->enum('state', [UserStateEnum::AWAITING_PHONE, UserStateEnum::AWAITING_CODE, UserStateEnum::AWAITING_TEST]);
            $table->timestamps();

            $table->comment('Таблица для хранения состояний пользователей');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_states');
    }
};
