<?php

namespace Database\Factories;

use App\Models\BagSale;
use App\Models\BagReception;
use Illuminate\Database\Eloquent\Factories\Factory;

class BagSaleFactory extends Factory
{
    protected $model = BagSale::class;

    public function definition(): array
    {
        $quantityReceived = $this->faker->numberBetween(10, 100);
        $quantitySold = $this->faker->numberBetween(0, $quantityReceived);
        
        return [
            "bag_reception_id" => BagReception::factory(),
            "quantity_sold" => $quantitySold,
            "quantity_unsold" => $quantityReceived - $quantitySold,
            "notes" => $this->faker->optional()->sentence(),
            "is_recovered" => $this->faker->boolean(),
        ];
    }
}
