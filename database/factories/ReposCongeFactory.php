<?php

namespace Database\Factories;

use App\Models\ReposConge;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReposCongeFactory extends Factory
{
    protected $model = ReposConge::class;

    public function definition(): array
    {
        $hasConge = $this->faker->boolean(30);
        
        return [
            "employe_id" => User::factory(),
            "jour" => $this->faker->randomElement(["lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi", "dimanche"]),
            "conges" => $hasConge ? $this->faker->numberBetween(1, 30) : null,
            "debut_c" => $hasConge ? $this->faker->date() : null,
            "raison_c" => $hasConge ? $this->faker->randomElement(["maladie", "evenement", "accouchement", "autre"]) : null,
            "autre_raison" => $hasConge && $this->faker->boolean(20) ? $this->faker->sentence() : null,
        ];
    }
}
