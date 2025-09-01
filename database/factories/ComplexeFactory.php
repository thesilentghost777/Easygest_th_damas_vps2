<?php

namespace Database\Factories;

use App\Models\Complexe;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComplexeFactory extends Factory
{
    protected $model = Complexe::class;

    public function definition(): array
    {
        return [
            'id_comp' => $this->faker->unique()->numberBetween(1, 9999),
            'nom' => $this->faker->company(),
            'localisation' => $this->faker->city(),
            'revenu_mensuel' => $this->faker->numberBetween(100000, 10000000),
            'revenu_annuel' => $this->faker->numberBetween(1000000, 100000000),
            'solde' => $this->faker->numberBetween(-500000, 5000000),
            'caisse_sociale' => $this->faker->numberBetween(0, 1000000),
            'valeur_caisse_sociale' => $this->faker->numberBetween(0, 2000000),
        ];
    }
}
