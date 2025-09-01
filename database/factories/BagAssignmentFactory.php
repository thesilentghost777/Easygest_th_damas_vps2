<?php

namespace Database\Factories;

use App\Models\BagAssignment;
use App\Models\Bag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BagAssignmentFactory extends Factory
{
    protected $model = BagAssignment::class;

    public function definition(): array
    {
        return [
            "bag_id" => Bag::factory(),
            "user_id" => User::factory(),
            "quantity_assigned" => $this->faker->numberBetween(1, 100),
            "notes" => $this->faker->optional()->sentence(),
        ];
    }
}
