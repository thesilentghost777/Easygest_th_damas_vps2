<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\TransactionVente;
use App\Models\ProduitFixe;
use App\Models\Utilisation;
use Carbon\Carbon;

class ProductAnalysisService
{
    /**
     * Collect product performance data for AI analysis
     */
    public function collectProductPerformanceData($dateDebut, $dateFin)
    {
        Log::info('Collecting product performance data', [
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin
        ]);
        
        try {
            // Récupération des données via AnalyseProduitController
            $analyseController = app(\App\Http\Controllers\AnalyseProduitController::class);
            
            $produitsAnalyse = $analyseController->analyseProduitsComplete($dateDebut, $dateFin);
            $topParJour = $analyseController->getTopProduitParJour();
            $evolutionVentes = $analyseController->getEvolutionVentes();
            $recapitulatifMois = $analyseController->getRecapitulatifMois();
            $produitsPopulairesNonRentables = $analyseController->getProduitsPopulairesNonRentables($dateDebut, $dateFin);
            $produitsLessMoinsVendus = $analyseController->getProduitsLesMoinsVendus($dateDebut, $dateFin);
            $meilleuresVentesParJour = $analyseController->getMeilleuresVentesParJour();
            
            $data = [
                'produits_analyse' => $this->sanitizeForLogging($produitsAnalyse),
                'top_par_jour' => $topParJour,
                'evolution_ventes' => $evolutionVentes,
                'recapitulatif_mois' => $recapitulatifMois,
                'produits_populaires_non_rentables' => $produitsPopulairesNonRentables,
                'produits_moins_vendus' => $produitsLessMoinsVendus,
                'meilleures_ventes_par_jour' => $meilleuresVentesParJour
            ];
            
            Log::info('Product performance data collected successfully', [
                'data_size' => strlen(json_encode($data))
            ]);
            
            return $data;
        } catch (\Exception $e) {
            Log::error('Error collecting product performance data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Erreur lors de la collecte des données de performance produit: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Sanitize large data sets for logging
     */
    private function sanitizeForLogging($data)
    {
        if (is_array($data) && count($data) > 10) {
            return [
                'sample' => array_slice($data, 0, 3),
                'total_count' => count($data),
                'note' => 'Data truncated for logging'
            ];
        }
        
        return $data;
    }
}
