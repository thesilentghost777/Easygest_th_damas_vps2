<?php

namespace Database\Factories;

use App\Models\MatiereRecommander;
use App\Models\Produit_Fixes;
use App\Models\Matiere;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatiereRecommanderFactory extends Factory
{
    protected $model = MatiereRecommander::class;

    public function definition(): array
    {
        $unites = ['kg', 'g', 'L', 'mL', 'pièce'];
        
        return [
            'produit' => Produit_Fixes::factory(),
            'matierep' => Matiere::factory(),
            'quantitep' => $this->faker->numberBetween(1, 50),
            'quantite' => $this->faker->randomFloat(3, 0.1, 100),
            'unite' => $this->faker->randomElement($unites),
        ];
    }
}
