<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\TransactionVente;
use App\Models\Evaluation;
use App\Models\History;
use Carbon\Carbon;

class EmployeePerformanceService
{
    /**
     * Collect employee performance data for AI analysis
     */
    public function collectEmployeePerformanceData($dateDebut, $dateFin)
    {
        Log::info('Collecting employee performance data', [
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin
        ]);
        
        try {
            // Données des vendeurs
            $vendeurPerformance = $this->getVendeurPerformance($dateDebut, $dateFin);
            
            // Données des producteurs
            $producteurPerformance = $this->getProducteurPerformance($dateDebut, $dateFin);
            
            // Évaluations
            $evaluations = $this->getEmployeeEvaluations();
            
            // Soupçons de vol pour les chefs de production
            $cpTheftSuspicions = $this->getChefProductionTheftSuspicions();
            
            $data = [
                'vendeur_performance' => $vendeurPerformance,
                'producteur_performance' => $producteurPerformance,
                'evaluations' => $evaluations,
                'cp_theft_suspicions' => $cpTheftSuspicions
            ];
            
            Log::info('Employee performance data collected successfully', [
                'data_size' => strlen(json_encode($data))
            ]);
            
            return $data;
        } catch (\Exception $e) {
            Log::error('Error collecting employee performance data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Erreur lors de la collecte des données de performance employé: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get vendeur performance data
     */
    private function getVendeurPerformance($dateDebut, $dateFin)
    {
        $ventesParVendeur = TransactionVente::join('users', 'transaction_ventes.serveur', '=', 'users.id')
            ->join('Produit_fixes', 'transaction_ventes.produit', '=', 'Produit_fixes.code_produit')
            ->where('type', 'Vente')
            ->whereBetween('date_vente', [$dateDebut, $dateFin])
            ->select(
                'users.id',
                'users.name',
                DB::raw('SUM(transaction_ventes.quantite * transaction_ventes.prix) as chiffre_affaires'),
                DB::raw('SUM(transaction_ventes.quantite) as quantite_totale'),
                DB::raw('COUNT(transaction_ventes.id) as nombre_ventes'),
                DB::raw('COUNT(DISTINCT transaction_ventes.produit) as diversite_produits')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('chiffre_affaires', 'desc')
            ->get();
            
        // Calcul des performances par critère
        $meilleurCA = $ventesParVendeur->max('chiffre_affaires') ?: 1;
        $meilleurQuantite = $ventesParVendeur->max('quantite_totale') ?: 1;
        $meilleurNbVentes = $ventesParVendeur->max('nombre_ventes') ?: 1;
        $meilleurDiversite = $ventesParVendeur->max('diversite_produits') ?: 1;
        
        foreach ($ventesParVendeur as $vendeur) {
            // Score sur 100 points pour chaque critère
            $scoreCA = ($vendeur->chiffre_affaires / $meilleurCA) * 100;
            $scoreQuantite = ($vendeur->quantite_totale / $meilleurQuantite) * 100;
            $scoreNbVentes = ($vendeur->nombre_ventes / $meilleurNbVentes) * 100;
            $scoreDiversite = ($vendeur->diversite_produits / $meilleurDiversite) * 100;
            
            // Score global (moyenne pondérée)
            $vendeur->score_global = 
                ($scoreCA * 0.4) +            // 40% basé sur le CA
                ($scoreQuantite * 0.3) +      // 30% basé sur la quantité
                ($scoreNbVentes * 0.2) +      // 20% basé sur le nombre de ventes
                ($scoreDiversite * 0.1);      // 10% basé sur la diversité
                
            // Scores détaillés
            $vendeur->scores = [
                'ca' => round($scoreCA),
                'quantite' => round($scoreQuantite),
                'nb_ventes' => round($scoreNbVentes),
                'diversite' => round($scoreDiversite),
            ];
        }
        
        return $ventesParVendeur->sortByDesc('score_global')->values()->all();
    }
    
    /**
     * Get producteur performance data
     */
    private function getProducteurPerformance($dateDebut, $dateFin)
    {
        $comparisonService = app(\App\Services\ProducteurComparisonService::class);
        
        // Obtenir les résultats pour les deux critères
        $resultatsEfficacite = $comparisonService->compareProducteurs('efficacite', 'custom', $dateDebut, $dateFin);
        $resultatsBenefice = $comparisonService->compareProducteurs('benefice', 'custom', $dateDebut, $dateFin);
        
        return [
            'efficacite' => $resultatsEfficacite,
            'benefice' => $resultatsBenefice
        ];
    }
    
    /**
     * Get employee evaluations
     */
    private function getEmployeeEvaluations()
    {
        return Evaluation::with('user:id,name,role,secteur')
            ->select('id', 'user_id', 'note', 'appreciation', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get()
            ->map(function($eval) {
                return [
                    'id' => $eval->id,
                    'user' => $eval->user ? [
                        'id' => $eval->user->id,
                        'name' => $eval->user->name,
                        'role' => $eval->user->role,
                        'secteur' => $eval->user->secteur,
                    ] : null,
                    'note' => $eval->note,
                    'appreciation' => $eval->appreciation,
                    'date' => $eval->created_at->format('Y-m-d')
                ];
            });
    }
    
    /**
     * Get chef production theft suspicions
     */
    private function getChefProductionTheftSuspicions()
    {
        return History::where('action_type', 'verificateur_vol_cp')
            ->with('user:id,name,role,secteur')
            ->select('id', 'description', 'user_id', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'description' => $item->description,
                    'user' => $item->user ? [
                        'id' => $item->user->id,
                        'name' => $item->user->name,
                        'role' => $item->user->role,
                        'secteur' => $item->user->secteur
                    ] : null,
                    'date' => $item->created_at->format('Y-m-d H:i:s')
                ];
            });
    }
}
