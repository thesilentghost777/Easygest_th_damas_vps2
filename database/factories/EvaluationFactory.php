<?php

namespace Database\Factories;

use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluationFactory extends Factory
{
    protected $model = Evaluation::class;

    public function definition(): array
    {
        return [
            "user_id" => User::factory(),
            "note" => $this->faker->randomFloat(2, 0, 20),
            "appreciation" => $this->faker->paragraph(),
        ];
    }
}
