<?php

namespace Database\Factories;

use App\Models\AssignationMatiere;
use App\Models\User;
use App\Models\Matiere;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignationMatiereFactory extends Factory
{
    protected $model = AssignationMatiere::class;

    public function definition(): array
    {
        $unites = ['kg', 'g', 'L', 'mL', 'pièce'];
        $quantite_assignee = $this->faker->randomFloat(3, 1, 100);
        
        return [
            'producteur_id' => User::factory(),
            'matiere_id' => Matiere::factory(),
            'quantite_assignee' => $quantite_assignee,
            'unite_assignee' => $this->faker->randomElement($unites),
            'quantite_restante' => $this->faker->randomFloat(3, 0, $quantite_assignee),
            'date_limite_utilisation' => $this->faker->boolean(70) ? 
                $this->faker->dateTimeBetween('now', '+1 month') : null,
        ];
    }
}
