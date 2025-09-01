<?php

namespace Database\Factories;

use App\Models\MissingCalculation;
use App\Models\ProductGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MissingCalculationFactory extends Factory
{
    protected $model = MissingCalculation::class;

    public function definition(): array
    {
        return [
            "product_group_id" => ProductGroup::factory(),
            "user_id" => User::factory(),
            "date" => $this->faker->date(),
            "title" => $this->faker->sentence(4),
            "status" => $this->faker->randomElement(["open", "closed"]),
            "total_amount" => $this->faker->randomFloat(2, 0, 1000000),
        ];
    }

    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            "status" => "open",
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            "status" => "closed",
        ]);
    }
}
