<?php

namespace Database\Factories;

use App\Models\HistoriqueSoldeCP;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HistoriqueSoldeCP>
 */
class HistoriqueSoldeCPFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HistoriqueSoldeCP::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $soldeAvant = fake()->randomFloat(2, 0, 50000);
        $montant = fake()->randomFloat(2, 100, 10000);
        $typeOperation = fake()->randomElement(['versement', 'depense', 'ajustement']);
        
        $soldeApres = match($typeOperation) {
            'versement' => $soldeAvant + $montant,
            'depense' => $soldeAvant - $montant,
            'ajustement' => fake()->randomFloat(2, 0, 60000),
        };
        
        return [
            'montant' => $montant,
            'type_operation' => $typeOperation,
            'operation_id' => fake()->optional()->numberBetween(1, 1000),
            'solde_avant' => $soldeAvant,
            'solde_apres' => max(0, $soldeApres),
            'user_id' => User::factory(),
            'description' => fake()->optional()->sentence(),
        ];
    }
}