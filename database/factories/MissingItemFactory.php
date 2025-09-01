<?php

namespace Database\Factories;

use App\Models\MissingItem;
use App\Models\MissingCalculation;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class MissingItemFactory extends Factory
{
    protected $model = MissingItem::class;

    public function definition(): array
    {
        $expectedQuantity = $this->faker->numberBetween(1, 100);
        $actualQuantity = $this->faker->numberBetween(0, $expectedQuantity);
        $missingQuantity = $expectedQuantity - $actualQuantity;
        $unitPrice = $this->faker->randomFloat(2, 100, 10000);
        
        return [
            "missing_calculation_id" => MissingCalculation::factory(),
            "product_id" => Product::factory(),
            "expected_quantity" => $expectedQuantity,
            "actual_quantity" => $actualQuantity,
            "missing_quantity" => $missingQuantity,
            "amount" => $missingQuantity * $unitPrice,
        ];
    }
}
