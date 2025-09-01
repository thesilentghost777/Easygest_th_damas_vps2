<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Matiere;
use App\Models\MatiereRecommander;
use App\Models\Utilisation;
use App\Models\Produit_fixes;

class MaterialUsageService
{
    /**
     * Collect material usage data for AI analysis
     */
    public function collectMaterialUsageData()
    {
        Log::info('Collecting material usage data');
        
        try {
            // Recommended material usage vs actual
            $materialUsageComparison = $this->compareMaterialUsage();
            
            // Stock levels and waste
            $stockLevels = $this->getStockLevels();
            
            $data = [
                'material_usage_comparison' => $materialUsageComparison,
                'stock_levels' => $stockLevels
            ];
            
            Log::info('Material usage data collected successfully', [
                'data_size' => strlen(json_encode($data))
            ]);
            
            return $data;
        } catch (\Exception $e) {
            Log::error('Error collecting material usage data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Erreur lors de la collecte des données d\'utilisation des matières: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Compare recommended vs actual material usage
     */
    private function compareMaterialUsage()
    {
        // Obtenir les recommandations de matières
        $recommendations = MatiereRecommander::join('Produit_fixes', 'Matiere_recommander.produit', '=', 'Produit_fixes.code_produit')
            ->join('Matiere', 'Matiere_recommander.matierep', '=', 'Matiere.id')
            ->select(
                'Matiere_recommander.produit',
                'Produit_fixes.nom as nom_produit',
                'Matiere_recommander.matierep',
                'Matiere.nom as nom_matiere',
                'Matiere_recommander.quantitep',
                'Matiere_recommander.quantite',
                'Matiere_recommander.unite',
                'Matiere.prix_par_unite_minimale'
            )
            ->get();
            
        // Obtenir l'utilisation réelle des derniers mois
        $utilisations = Utilisation::select(
                'produit',
                'matierep',
                DB::raw('SUM(quantite_produit) as total_quantite_produit'),
                DB::raw('SUM(quantite_matiere) as total_quantite_matiere')
            )
            ->whereDate('created_at', '>=', now()->subMonths(3))
            ->groupBy('produit', 'matierep')
            ->get();
            
        // Comparer les recommandations avec l'utilisation réelle
        $comparaison = [];
        foreach ($recommendations as $rec) {
            $utilisation = $utilisations->where('produit', $rec->produit)
                                      ->where('matierep', $rec->matierep)
                                      ->first();
                                      
            if ($utilisation) {
                // Calculer le ratio d'utilisation réel vs recommandé
                $ratioRecommande = $rec->quantite / $rec->quantitep;
                $ratioReel = $utilisation->total_quantite_matiere / $utilisation->total_quantite_produit;
                
                // Calculer la différence en pourcentage
                $difference = $ratioReel - $ratioRecommande;
                $pourcentageDifference = $ratioRecommande > 0 ? ($difference / $ratioRecommande) * 100 : 0;
                
                // Calculer le coût supplémentaire par unité de produit
                $coutSupplementaire = $difference > 0 ? $difference * $rec->prix_par_unite_minimale : 0;
                
                // Calculer l'impact financier sur la période analysée
                $impactFinancier = $coutSupplementaire * $utilisation->total_quantite_produit;
                
                // Ajouter à la comparaison si la différence est significative (> 5%)
                if (abs($pourcentageDifference) > 5) {
                    $comparaison[] = [
                        'produit_id' => $rec->produit,
                        'produit_nom' => $rec->nom_produit,
                        'matiere_id' => $rec->matierep,
                        'matiere_nom' => $rec->nom_matiere,
                        'ratio_recommande' => $ratioRecommande,
                        'ratio_reel' => $ratioReel,
                        'difference' => $difference,
                        'pourcentage_difference' => $pourcentageDifference,
                        'cout_supplementaire_par_unite' => $coutSupplementaire,
                        'impact_financier' => $impactFinancier,
                        'quantite_produit_periode' => $utilisation->total_quantite_produit,
                        'unite' => $rec->unite
                    ];
                }
            }
        }
        
        return $comparaison;
    }
    
    /**
     * Get stock levels and waste
     */
    private function getStockLevels()
    {
        return Matiere::select(
                'id',
                'nom',
                'unite_minimale',
                'unite_classique',
                'quantite_par_unite',
                'quantite',
                'prix_unitaire',
                'prix_par_unite_minimale'
            )
            ->get();
    }
}
