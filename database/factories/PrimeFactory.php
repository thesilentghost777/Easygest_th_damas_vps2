<?php

namespace Database\Factories;

use App\Models\Prime;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrimeFactory extends Factory
{
    protected $model = Prime::class;

    public function definition(): array
    {
        $libelles = ['Prime de performance', 'Prime de ponctualité', 'Prime exceptionnelle', 'Prime de productivité'];
        
        return [
            'id_employe' => User::factory(),
            'libelle' => $this->faker->randomElement($libelles),
            'montant' => $this->faker->numberBetween(5000, 100000),
        ];
    }
}
