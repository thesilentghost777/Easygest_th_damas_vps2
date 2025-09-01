<?php

namespace Database\Factories;

use App\Models\ProductionSuggererParJour;
use App\Models\Produit_Fixes;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionSuggererParJourFactory extends Factory
{
    protected $model = ProductionSuggererParJour::class;

    public function definition(): array
    {
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        
        return [
            'produit' => Produit_Fixes::factory(),
            'quantity' => $this->faker->numberBetween(5, 100),
            'day' => $this->faker->randomElement($days),
        ];
    }
}
