<?php

namespace Database\Factories;

use App\Models\DailyAssignment;
use App\Models\User;
use App\Models\Produit_Fixes;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailyAssignmentFactory extends Factory
{
    protected $model = DailyAssignment::class;

    public function definition(): array
    {
        return [
            'chef_production' => User::factory(),
            'producteur' => User::factory(),
            'produit' => Produit_Fixes::factory(),
            'expected_quantity' => $this->faker->numberBetween(10, 200),
            'assignment_date' => $this->faker->dateTimeBetween('-1 month', '+1 week')->format('Y-m-d'),
            'status' => $this->faker->numberBetween(0, 3),
        ];
    }
}
