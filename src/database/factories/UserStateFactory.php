<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\UserState;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserStateFactory extends Factory
{
    protected $model = UserState::class;

    public function definition()
    {
        return [
            'user_id' => $this->faker->word(),
            'state' => $this->faker->randomElement([
                UserState::AWAITING_PHONE,
                UserState::AWAITING_CODE,
                UserState::AWAITING_TEST,
            ]),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
