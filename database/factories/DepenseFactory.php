<?php

namespace Database\Factories;

use App\Models\Depense;
use App\Models\User;
use App\Models\Matiere;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepenseFactory extends Factory
{
    protected $model = Depense::class;

    public function definition(): array
    {
        $types = ['achat_matiere', 'livraison_matiere', 'reparation', 'depense_fiscale', 'autre'];
        
        return [
            'auteur' => User::factory(),
            'nom' => $this->faker->sentence(3),
            'prix' => $this->faker->randomFloat(2, 1000, 1000000),
            'type' => $this->faker->randomElement($types),
            'idm' => $this->faker->boolean(60) ? Matiere::factory() : null,
            'date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'valider' => $this->faker->boolean(80),
        ];
    }
}
