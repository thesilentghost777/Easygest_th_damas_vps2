<?php

namespace Database\Factories;

use App\Models\Salaire;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalaireFactory extends Factory
{
    protected $model = Salaire::class;

    public function definition(): array
    {
        return [
            'id_employe' => User::factory(),
            'somme' => $this->faker->randomFloat(2, 50000, 500000),
            'flag' => $this->faker->boolean(70),
            'retrait_demande' => $this->faker->boolean(40),
            'retrait_valide' => $this->faker->boolean(30),
            'mois_salaire' => $this->faker->dateTimeBetween('-12 months', 'now')->format('Y-m-d'),
        ];
    }
}
