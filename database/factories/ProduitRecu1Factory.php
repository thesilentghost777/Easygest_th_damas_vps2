<?php

namespace Database\Factories;

use App\Models\ProduitRecu1;
use App\Models\Produit_fixes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitRecu1Factory extends Factory
{
    protected $model = ProduitRecu1::class;

    public function definition(): array
    {
        return [
            "produit_id" => Produit_fixes::factory(),
            "quantite" => $this->faker->numberBetween(1, 1000),
            "producteur_id" => User::factory(),
            "pointeur_id" => User::factory(),
            "date_reception" => $this->faker->dateTime(),
            "remarques" => $this->faker->optional()->paragraph(),
        ];
    }
}
