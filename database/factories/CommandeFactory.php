<?php

namespace Database\Factories;

use App\Models\Commande;
use App\Models\Produit_fixes;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommandeFactory extends Factory
{
    protected $model = Commande::class;

    public function definition(): array
    {
        $categories = ['Boisson', 'Plat', 'Dessert', 'Entrée', 'Snack'];
        
        return [
            'libelle' => $this->faker->sentence(3),
            'date_commande' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'produit' => Produit_fixes::factory(),
            'quantite' => $this->faker->numberBetween(1, 50),
            'categorie' => $this->faker->randomElement($categories),
            'valider' => $this->faker->boolean(70),
        ];
    }
}
