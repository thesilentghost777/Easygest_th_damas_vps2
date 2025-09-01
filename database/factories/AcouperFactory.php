<?php

namespace Database\Factories;

use App\Models\Acouper;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcouperFactory extends Factory
{
    protected $model = Acouper::class;

    public function definition(): array
    {
        return [
            'id_employe' => User::factory(),
            'manquants' => $this->faker->numberBetween(0, 100000),
            'remboursement' => $this->faker->numberBetween(0, 50000),
            'pret' => $this->faker->numberBetween(0, 200000),
            'caisse_sociale' => $this->faker->numberBetween(0, 10000),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        ];
    }
}
