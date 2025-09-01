<?php

namespace Database\Factories;

use App\Models\BagTransaction;
use App\Models\Bag;
use Illuminate\Database\Eloquent\Factories\Factory;

class BagTransactionFactory extends Factory
{
    protected $model = BagTransaction::class;

    public function definition(): array
    {
        return [
            'bag_id' => Bag::factory(),
            'type' => $this->faker->randomElement(['received', 'sold']),
            'quantity' => $this->faker->numberBetween(1, 100),
            'transaction_date' => $this->faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
        ];
    }
}
