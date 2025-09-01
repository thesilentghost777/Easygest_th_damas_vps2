<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'utilisateur développeur spécifique
        User::create([
            'name' => 'ghost',
            'email' => 'ghost@shadow.com',
            'date_naissance' => Carbon::now()->subYears(19)->format('Y-m-d'), // 19 ans
            'code_secret' => 7777, // Code secret pour le développeur
            'secteur' => 'Informatique',
            'role' => 'developper',
            'num_tel' => '650706128',
            'annee_debut_service' => Carbon::now()->year, // Commencé il y a 2 ans
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('ghost'), // Mot de passe par défaut
        ]);

        // Optionnel : Créer d'autres utilisateurs avec Faker
      
    }
}