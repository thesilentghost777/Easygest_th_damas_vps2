<?php

namespace Database\Factories;

use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitFactory extends Factory
{
    protected $model = Produit::class;

    public function definition(): array
    {
        return [
            "nom" => $this->faker->word(),
            "reference" => $this->faker->unique()->regexify("[A-Z]{2}[0-9]{4}"),
            "type" => $this->faker->randomElement(["magasin", "boisson"]),
            "quantite" => $this->faker->numberBetween(0, 1000),
            "prix_unitaire" => $this->faker->randomFloat(2, 100, 10000),
            "seuil_alerte" => $this->faker->numberBetween(1, 20),
        ];
    }

    public function magasin(): static
    {
        return $this->state(fn (array $attributes) => [
            "type" => "magasin",
        ]);
    }

    public function boisson(): static
    {
        return $this->state(fn (array $attributes) => [
            "type" => "boisson",
        ]);
    }
}
