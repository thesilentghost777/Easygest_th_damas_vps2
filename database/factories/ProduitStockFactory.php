<?php

namespace Database\Factories;

use App\Models\ProduitStock;
use App\Models\Produit_fixes;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitStockFactory extends Factory
{
    protected $model = ProduitStock::class;

    public function definition(): array
    {
        return [
            "id_produit" => Produit_fixes::factory(),
            "quantite_en_stock" => $this->faker->numberBetween(0, 1000),
            "quantite_invendu" => $this->faker->numberBetween(0, 100),
            "quantite_avarie" => $this->faker->numberBetween(0, 50),
        ];
    }
}
