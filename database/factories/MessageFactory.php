<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        $types = ['info', 'warning', 'error', 'success'];
        
        return [
            'message' => $this->faker->paragraph(),
            'type' => $this->faker->randomElement($types),
            'date_message' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'name' => $this->faker->name(),
            'read' => $this->faker->boolean(40),
        ];
    }
}
