<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserStateEnum;
use App\Models\UserState;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserStateFactory extends Factory
{
    protected $model = UserState::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->word(),
            'state' => $this->faker->randomElement([
                UserStateEnum::AWAITING_PHONE,
                UserStateEnum::AWAITING_CODE,
                UserStateEnum::AWAITING_TEST,
            ]),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
