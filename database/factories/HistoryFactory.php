<?php

namespace Database\Factories;

use App\Models\History;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoryFactory extends Factory
{
    protected $model = History::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'action_type' => $this->faker->randomElement([
                'verificateur_vol_cp',
                'login',
                'logout',
                'create_product',
                'update_product',
                'delete_product'
            ]),
            'description' => $this->faker->sentence(),
            'ip_address' => $this->faker->ipv4(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }

    public function theftSuspicion()
    {
        return $this->state(function (array $attributes) {
            return [
                'action_type' => 'verificateur_vol_cp',
                'description' => 'Soupçon de vol détecté - ' . $this->faker->sentence(),
            ];
        });
    }
}
