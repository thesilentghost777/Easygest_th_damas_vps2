<?php

namespace Database\Factories;

use App\Models\CashDistribution;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashDistributionFactory extends Factory
{
    protected $model = CashDistribution::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(["en_cours", "cloture"]);
        $billAmount = $this->faker->randomFloat(2, 50000, 1000000);
        $initialCoinAmount = $this->faker->randomFloat(2, 10000, 200000);
        $salesAmount = $this->faker->randomFloat(2, 0, $billAmount + $initialCoinAmount);
        
        return [
            "user_id" => User::factory(),
            "date" => $this->faker->date(),
            "bill_amount" => $billAmount,
            "initial_coin_amount" => $initialCoinAmount,
            "final_coin_amount" => $status === "cloture" ? $this->faker->randomFloat(2, 0, $initialCoinAmount) : null,
            "deposited_amount" => $status === "cloture" ? $this->faker->randomFloat(2, 0, $salesAmount) : null,
            "sales_amount" => $salesAmount,
            "missing_amount" => $status === "cloture" ? $this->faker->randomFloat(2, -50000, 100000) : null,
            "status" => $status,
            "notes" => $this->faker->optional()->paragraph(),
            "closed_by" => $status === "cloture" ? User::factory() : null,
            "closed_at" => $status === "cloture" ? $this->faker->dateTime() : null,
        ];
    }

    public function enCours(): static
    {
        return $this->state(fn (array $attributes) => [
            "status" => "en_cours",
            "final_coin_amount" => null,
            "deposited_amount" => null,
            "missing_amount" => null,
            "closed_by" => null,
            "closed_at" => null,
        ]);
    }

    public function cloture(): static
    {
        return $this->state(fn (array $attributes) => [
            "status" => "cloture",
            "final_coin_amount" => $this->faker->randomFloat(2, 0, 200000),
            "deposited_amount" => $this->faker->randomFloat(2, 0, 1000000),
            "missing_amount" => $this->faker->randomFloat(2, -50000, 100000),
            "closed_by" => User::factory(),
            "closed_at" => $this->faker->dateTime(),
        ]);
    }
}
