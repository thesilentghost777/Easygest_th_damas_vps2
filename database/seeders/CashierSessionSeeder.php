<?php

namespace Database\Seeders;

use App\Models\CashierSession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashierSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CashierSession::factory(100)->create();
    }
}
