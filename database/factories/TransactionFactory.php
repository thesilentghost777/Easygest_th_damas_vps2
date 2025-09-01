<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            "type" => $this->faker->randomElement(["income", "outcome"]),
            "category_id" => Category::factory(),
            "amount" => $this->faker->randomFloat(2, 100, 1000000),
            "date" => $this->faker->dateTime(),
            "description" => $this->faker->optional()->paragraph(),
        ];
    }

    public function income(): static
    {
        return $this->state(fn (array $attributes) => [
            "type" => "income",
        ]);
    }

    public function outcome(): static
    {
        return $this->state(fn (array $attributes) => [
            "type" => "outcome",
        ]);
    }
}
