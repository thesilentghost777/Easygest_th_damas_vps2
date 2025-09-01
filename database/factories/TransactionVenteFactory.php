<?php

namespace Database\Factories;

use App\Models\TransactionVente;
use App\Models\Produit_fixes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionVenteFactory extends Factory
{
    protected $model = TransactionVente::class;

    public function definition(): array
    {
        $types = ['comptoir', 'livraison', 'emporter'];
        $monnaies = ['FCFA', 'EUR', 'USD'];
        
        return [
            'produit' => Produit_fixes::factory(),
            'serveur' => User::factory(),
            'quantite' => $this->faker->numberBetween(1, 20),
            'prix' => $this->faker->numberBetween(500, 25000),
            'date_vente' => $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'type' => $this->faker->randomElement($types),
            'monnaie' => $this->faker->randomElement($monnaies),
        ];
    }
}
