<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'phone' => '+79058889977',
            'telegram_id' => $this->faker->numberBetween(100, 500),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
