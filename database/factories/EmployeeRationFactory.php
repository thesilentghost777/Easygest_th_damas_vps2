<?php

namespace Database\Factories;

use App\Models\EmployeeRation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeRationFactory extends Factory
{
    protected $model = EmployeeRation::class;

    public function definition()
    {
        return [
            'employee_id' => User::factory(), // Crée automatiquement un utilisateur associé
            'montant' => $this->faker->randomFloat(2, 1000, 5000), // montant entre 1000 et 5000
            'personnalise' => $this->faker->boolean(), // true ou false
        ];
    }
}
