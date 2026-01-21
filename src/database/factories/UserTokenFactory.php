<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\UserToken;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UserTokenFactory extends Factory
{
    protected $model = UserToken::class;

    public function definition()
    {
        return [
            'token' => Str::random(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }
}
