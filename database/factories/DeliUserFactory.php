<?php

namespace Database\Factories;

use App\Models\DeliUser;
use App\Models\Deli;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliUserFactory extends Factory
{
    protected $model = DeliUser::class;

    public function definition(): array
    {
        return [
            "deli_id" => Deli::factory(),
            "user_id" => User::factory(),
            "date_incident" => $this->faker->date(),
        ];
    }
}
