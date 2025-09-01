<?php

namespace Database\Factories;

use App\Models\MouvementStock;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MouvementStockFactory extends Factory
{
    protected $model = MouvementStock::class;

    public function definition(): array
    {
        return [
            "produit_id" => Produit::factory(),
            "type" => $this->faker->randomElement(["entree", "sortie"]),
            "quantite" => $this->faker->numberBetween(1, 100),
            "user_id" => User::factory(),
            "motif" => $this->faker->sentence(),
        ];
    }

    public function entree(): static
    {
        return $this->state(fn (array $attributes) => [
            "type" => "entree",
            "motif" => "Réapprovisionnement stock",
        ]);
    }

    public function sortie(): static
    {
        return $this->state(fn (array $attributes) => [
            "type" => "sortie",
            "motif" => "Vente produit",
        ]);
    }
}
