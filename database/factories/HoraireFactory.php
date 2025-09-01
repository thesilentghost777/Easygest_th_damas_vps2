<?php

namespace Database\Factories;

use App\Models\Horaire;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HoraireFactory extends Factory
{
    protected $model = Horaire::class;

    public function definition(): array
    {
        $arrive = $this->faker->dateTimeBetween('-1 month', 'now');
        $depart = $this->faker->boolean(80) ? 
            $this->faker->dateTimeBetween($arrive, $arrive->format('Y-m-d') . ' 23:59:59') : 
            null;
            
        return [
            'employe' => User::factory(),
            'arrive' => $arrive,
            'depart' => $depart,
        ];
    }
}
