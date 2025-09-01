<?php

namespace Database\Factories;

use App\Models\ReservationMp;
use App\Models\User;
use App\Models\Matiere;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationMpFactory extends Factory
{
    protected $model = ReservationMp::class;

    public function definition(): array
    {
        $unites = ['kg', 'g', 'L', 'mL', 'pièce'];
        $statuts = ['en_attente', 'approuvee', 'refusee'];
        
        return [
            'producteur_id' => User::factory(),
            'matiere_id' => Matiere::factory(),  
            'quantite_demandee' => $this->faker->randomFloat(3, 0.1, 50),
            'unite_demandee' => $this->faker->randomElement($unites),
            'statut' => $this->faker->randomElement($statuts),
            'commentaire' => $this->faker->boolean(60) ? $this->faker->sentence() : null,
        ];
    }
}
