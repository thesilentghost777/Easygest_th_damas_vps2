<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stagiaire;

class StagiaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
       Stagiaire::factory(50)->create(); // Génère 50 stagiaires
    }
}