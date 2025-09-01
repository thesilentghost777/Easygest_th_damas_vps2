<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WasteAnalysisService
{
    /**
     * Collect waste analysis data for AI analysis
     */
    public function collectWasteData()
    {
        Log::info('Collecting waste analysis data');
        
        try {
            // Récupération des données via GaspillageController
            $gaspillageController = app(\App\Http\Controllers\GaspillageController::class);
            
            $statsGlobales = $gaspillageController->getStatsGlobales();
            $topProductionsGaspillage = $gaspillageController->getTopProductionsGaspillage();
            $topProduitsGaspillage = $gaspillageController->getTopProduitsGaspillage();
            $topMatieresGaspillage = $gaspillageController->getTopMatieresGaspillage();
            
            $data = [
                'stats_globales' => $statsGlobales,
                'top_productions_gaspillage' => $topProductionsGaspillage,
                'top_produits_gaspillage' => $topProduitsGaspillage,
                'top_matieres_gaspillage' => $topMatieresGaspillage,
            ];
            
            Log::info('Waste data collected successfully', [
                'data_size' => strlen(json_encode($data))
            ]);
            
            return $data;
        } catch (\Exception $e) {
            Log::error('Error collecting waste data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Erreur lors de la collecte des données de gaspillage: ' . $e->getMessage()
            ];
        }
    }
}