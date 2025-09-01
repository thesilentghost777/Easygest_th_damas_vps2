<?php

namespace Database\Factories;

use App\Models\ObjectiveProgress;
use App\Models\Objective;
use Illuminate\Database\Eloquent\Factories\Factory;

class ObjectiveProgressFactory extends Factory
{
    protected $model = ObjectiveProgress::class;

    public function definition(): array
    {
        return [
            'objective_id' => Objective::factory(),
            'date' => $this->faker->date(),
            'current_amount' => $this->faker->randomFloat(2, 1000, 100000),
            'expenses' => $this->faker->randomFloat(2, 100, 10000),
            'profit' => $this->faker->randomFloat(2, 500, 50000),
            'progress_percentage' => $this->faker->randomFloat(2, 0, 100),
            'transactions' => $this->faker->randomElements(['TXN001', 'TXN002', 'TXN003'], 2),
        ];
    }
}
