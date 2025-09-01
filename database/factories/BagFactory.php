<?php

namespace Database\Factories;

use App\Models\Bag;
use Illuminate\Database\Eloquent\Factories\Factory;

class BagFactory extends Factory
{
    protected $model = Bag::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . ' Bag',
            'price' => $this->faker->randomFloat(2, 100, 5000),
            'stock_quantity' => $this->faker->numberBetween(0, 1000),
            'alert_threshold' => $this->faker->numberBetween(50, 200),
        ];
    }
}
