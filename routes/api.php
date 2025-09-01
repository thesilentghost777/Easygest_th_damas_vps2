<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeApiController;
use App\Http\Controllers\Api\FinancialController;

// Supprimer le middleware 'guest' (incompatible avec Sanctum)
// Utiliser Sanctum pour gérer les sessions stateful
Route::post('/api/login', [AuthController::class, 'login'])->middleware('web');
Route::post('/api/register', [AuthController::class, 'register']);

// Protéger les routes avec Sanctum (stateless)
    Route::post('/api/logout', [AuthController::class, 'logout']);
    Route::get('/api/user', [AuthController::class, 'user']);

    // Routes API pour les données financières
    Route::prefix('api/financial')->group(function () {
        // Récupérer le solde de cette structure
        Route::get('/solde', [FinancialController::class, 'getSolde']);
        
        // Récupérer le revenu mensuel de cette structure
        Route::get('/revenu-mensuel', [FinancialController::class, 'getRevenuMensuel']);
        
        // Récupérer le revenu annuel de cette structure
        Route::get('/revenu-annuel', [FinancialController::class, 'getRevenuAnnuel']);
        
        // Récupérer le chiffre d'affaires de cette structure
        Route::get('/chiffre-affaires', [FinancialController::class, 'getChiffreAffaires']);
        
        // Récupérer toutes les données financières de cette structure
        Route::get('/all', [FinancialController::class, 'getAllFinancialData']);
        
        // Récupérer les informations de cette structure
        Route::get('/info', [FinancialController::class, 'getComplexeInfo']);
    });