<?php

namespace Database\Factories;

use App\Models\ManquantTemporaire;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ManquantTemporaireFactory extends Factory
{
    protected $model = ManquantTemporaire::class;

    public function definition(): array
    {
        return [
            "employe_id" => User::factory(),
            "montant" => $this->faker->numberBetween(0, 1000000),
            "explication" => $this->faker->optional()->paragraph(),
            "statut" => $this->faker->randomElement(["en_attente", "ajuste", "valide"]),
            "commentaire_dg" => $this->faker->optional()->paragraph(),
            "valide_par" => $this->faker->boolean(50) ? User::factory() : null,
        ];
    }
}
