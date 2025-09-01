<?php

namespace Database\Factories;

use App\Models\CashierSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashierSessionFactory extends Factory
{
    protected $model = CashierSession::class;

    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween("-1 month", "now");
        $endTime = $this->faker->boolean(70) ? $this->faker->dateTimeBetween($startTime, "now") : null;
        
        $initialCash = $this->faker->randomFloat(2, 10000, 500000);
        $initialChange = $this->faker->randomFloat(2, 5000, 100000);
        $initialMobile = $this->faker->randomFloat(2, 0, 200000);
        
        return [
            "user_id" => User::factory(),
            "start_time" => $startTime,
            "end_time" => $endTime,
            "initial_cash" => $initialCash,
            "initial_change" => $initialChange,
            "initial_mobile_balance" => $initialMobile,
            "final_cash" => $endTime ? $this->faker->randomFloat(2, 0, $initialCash * 2) : null,
            "final_change" => $endTime ? $this->faker->randomFloat(2, 0, $initialChange * 2) : null,
            "final_mobile_balance" => $endTime ? $this->faker->randomFloat(2, 0, $initialMobile * 2) : null,
            "cash_remitted" => $endTime ? $this->faker->randomFloat(2, 0, 1000000) : null,
            "total_withdrawals" => $this->faker->randomFloat(2, 0, 100000),
            "discrepancy" => $endTime ? $this->faker->randomFloat(2, -50000, 50000) : null,
            "notes" => $this->faker->optional()->paragraph(),
            "end_notes" => $endTime ? $this->faker->optional()->paragraph() : null,
        ];
    }
}
