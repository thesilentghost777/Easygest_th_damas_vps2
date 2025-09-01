<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            "name" => $this->faker->word(),
            "type" => $this->faker->randomElement(["alimentaire", "boisson", "materiel", "autre"]),
            "price" => $this->faker->randomFloat(2, 100, 50000),
            "product_group_id" => ProductGroup::factory(),
        ];
    }
}
