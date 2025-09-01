<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Commande;
use App\Models\ProduitFixe;
use Carbon\Carbon;

class OrderAnalysisService
{
    /**
     * Collect order analysis data for AI analysis
     */
    public function collectOrderData($month, $year)
    {
        Log::info('Collecting order analysis data', [
            'month' => $month,
            'year' => $year
        ]);
        
        try {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
            // Commandes du mois
            $commandesMois = Commande::with('produitRelation')
                ->whereBetween('date_commande', [$startDate, $endDate])
                ->orderBy('date_commande', 'desc')
                ->get();
                
            // Statistiques des commandes
            $statsCommandes = [
                'total' => $commandesMois->count(),
                'validees' => $commandesMois->where('valider', true)->count(),
                'en_attente' => $commandesMois->where('valider', false)->count(),
            ];
            
            // Commandes par catégorie
            $commandesParCategorie = $commandesMois
                ->groupBy('categorie')
                ->map(function($items, $categorie) {
                    return [
                        'categorie' => $categorie,
                        'nombre' => count($items),
                        'validees' => $items->where('valider', true)->count(),
                        'en_attente' => $items->where('valider', false)->count()
                    ];
                })
                ->values()
                ->all();
                
            // Tendances mensuelles (sur les 6 derniers mois)
            $tendancesMensuelles = $this->getOrderTrends();
                
            $data = [
                'stats' => $statsCommandes,
                'commandes_par_categorie' => $commandesParCategorie,
                'tendances' => $tendancesMensuelles,
                'commandes_recentes' => $commandesMois->take(20)->map(function($commande) {
                    return [
                        'id' => $commande->id,
                        'libelle' => $commande->libelle,
                        'date' => $commande->date_commande,
                        'produit' => $commande->produitRelation ? $commande->produitRelation->nom : 'N/A',
                        'quantite' => $commande->quantite,
                        'categorie' => $commande->categorie,
                        'valider' => $commande->valider
                    ];
                })
            ];
            
            Log::info('Order analysis data collected successfully', [
                'data_size' => strlen(json_encode($data))
            ]);
            
            return $data;
        } catch (\Exception $e) {
            Log::error('Error collecting order analysis data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Erreur lors de la collecte des données de commandes: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get order trends over the last 6 months
     */
    private function getOrderTrends()
    {
        $trends = [];
        $currentDate = Carbon::now();
        
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = $currentDate->copy()->subMonths($i);
            $startOfMonth = $monthDate->copy()->startOfMonth();
            $endOfMonth = $monthDate->copy()->endOfMonth();
            
            $commandesData = Commande::whereBetween('date_commande', [$startOfMonth, $endOfMonth])
                ->selectRaw('COUNT(*) as total, SUM(CASE WHEN valider = 1 THEN 1 ELSE 0 END) as validees')
                ->first();
                
            $trends[] = [
                'mois' => $monthDate->locale('fr')->isoFormat('MMM YYYY'),
                'total' => $commandesData->total ?? 0,
                'validees' => $commandesData->validees ?? 0,
                'en_attente' => ($commandesData->total ?? 0) - ($commandesData->validees ?? 0)
            ];
        }
        
        return $trends;
    }
}
