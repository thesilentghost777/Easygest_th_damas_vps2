<?php

namespace Database\Factories;

use App\Models\BagReception;
use App\Models\BagAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

class BagReceptionFactory extends Factory
{
    protected $model = BagReception::class;

    public function definition(): array
    {
        return [
            "bag_assignment_id" => BagAssignment::factory(),
            "quantity_received" => $this->faker->numberBetween(1, 100),
            "notes" => $this->faker->optional()->sentence(),
        ];
    }
}
