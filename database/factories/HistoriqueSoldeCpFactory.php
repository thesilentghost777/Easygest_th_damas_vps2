<?php

namespace Database\Factories;

use App\Models\HistoriqueSoldeCp;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoriqueSoldeCpFactory extends Factory
{
    protected $model = HistoriqueSoldeCp::class;

    public function definition(): array
    {
        $soldeAvant = $this->faker->randomFloat(2, 0, 1000000);
        $montant = $this->faker->randomFloat(2, 100, 100000);
        $typeOperation = $this->faker->randomElement(["versement", "depense", "ajustement"]);
        
        $soldeApres = match($typeOperation) {
            "versement" => $soldeAvant + $montant,
            "depense" => $soldeAvant - $montant,
            default => $this->faker->randomFloat(2, 0, 1000000)
        };
        
        return [
            "montant" => $montant,
            "type_operation" => $typeOperation,
            "operation_id" => $this->faker->optional()->randomNumber(),
            "solde_avant" => $soldeAvant,
            "solde_apres" => $soldeApres,
            "user_id" => User::factory(),
            "description" => $this->faker->optional()->paragraph(),
        ];
    }
}
