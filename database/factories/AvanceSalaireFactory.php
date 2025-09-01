<?php

namespace Database\Factories;

use App\Models\AvanceSalaire;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvanceSalaireFactory extends Factory
{
    protected $model = AvanceSalaire::class;

    public function definition(): array
    {
        return [
            'id_employe' => User::factory(),
            'sommeAs' => $this->faker->randomFloat(2, 10000, 200000),
            'flag' => $this->faker->boolean(30),
            'retrait_demande' => $this->faker->boolean(20),
            'retrait_valide' => $this->faker->boolean(10),
            'mois_as' => $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
        ];
    }
}
