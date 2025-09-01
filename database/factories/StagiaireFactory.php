<?php

namespace Database\Factories;

use App\Models\Stagiaire;
use Illuminate\Database\Eloquent\Factories\Factory;

class StagiaireFactory extends Factory
{
    protected $model = Stagiaire::class;

    public function definition(): array
    {
        $dateDebut = $this->faker->dateTimeBetween("-6 months", "now");
        $dateFin = $this->faker->dateTimeBetween($dateDebut, "+6 months");
        
        return [
            "nom" => $this->faker->lastName(),
            "prenom" => $this->faker->firstName(),
            "email" => $this->faker->unique()->safeEmail(),
            "telephone" => $this->faker->phoneNumber(),
            "ecole" => $this->faker->company(),
            "niveau_etude" => $this->faker->randomElement(["Licence", "Master", "BTS", "DUT", "Doctorat"]),
            "filiere" => $this->faker->randomElement(["Informatique", "Gestion", "Marketing", "Comptabilité", "RH"]),
            "date_debut" => $dateDebut->format("Y-m-d"),
            "date_fin" => $dateFin->format("Y-m-d"),
            "departement" => $this->faker->randomElement(["IT", "RH", "Comptabilité", "Marketing", "Production"]),
            "nature_travail" => $this->faker->paragraph(),
            "remuneration" => $this->faker->randomFloat(2, 0, 100000),
            "appreciation" => $this->faker->optional()->paragraph(),
            "type_stage" => $this->faker->randomElement(["academique", "professionnel"]),
            "rapport_genere" => $this->faker->boolean(),
        ];
    }
}
