<?php

namespace Database\Factories;

use App\Models\CashWithdrawal;
use App\Models\CashierSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashWithdrawalFactory extends Factory
{
    protected $model = CashWithdrawal::class;

    public function definition(): array
    {
        return [
            "cashier_session_id" => CashierSession::factory(),
            "amount" => $this->faker->randomFloat(2, 1000, 50000),
            "reason" => $this->faker->randomElement(["Achat fournitures", "Transport", "Urgence", "Maintenance", "Autre"]),
            "withdrawn_by" => $this->faker->name(),
            "created_at" => $this->faker->dateTime(),
        ];
    }
}
