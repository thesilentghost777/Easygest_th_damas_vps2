<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ProduitStock;
use App\Models\ProduitFixe;
use Carbon\Carbon;

class SpoilageAnalysisService
{
    /**
     * Collect spoilage data for AI analysis
     */
    public function collectSpoilageData()
    {
        Log::info('Collecting spoilage data');
        
        try {
            // Obtenir les produits et leurs stocks
            $produitStocks = ProduitStock::join('Produit_fixes', 'produit_stocks.id_produit', '=', 'Produit_fixes.code_produit')
                ->select(
                    'produit_stocks.id',
                    'produit_stocks.id_produit',
                    'Produit_fixes.nom as produit_nom',
                    'Produit_fixes.prix',
                    'produit_stocks.quantite_en_stock',
                    'produit_stocks.quantite_invendu',
                    'produit_stocks.quantite_avarie',
                    'produit_stocks.updated_at'
                )
                ->get();
                
            // Calculer les statistiques
            $totalProduits = $produitStocks->count();
            $produitsAvecAvaries = $produitStocks->where('quantite_avarie', '>', 0)->count();
            $totalAvaries = $produitStocks->sum('quantite_avarie');
            $valeurAvaries = $produitStocks->sum(function ($item) {
                return $item->quantite_avarie * $item->prix;
            });
            $totalInvendus = $produitStocks->sum('quantite_invendu');
            $valeurInvendus = $produitStocks->sum(function ($item) {
                return $item->quantite_invendu * $item->prix;
            });
            
            // Top produits avec plus d'avaries
            $topAvaries = $produitStocks->where('quantite_avarie', '>', 0)
                ->sortByDesc(function ($item) {
                    return $item->quantite_avarie * $item->prix;
                })
                ->take(10)
                ->values()
                ->all();
                
            // Top produits avec plus d'invendus
            $topInvendus = $produitStocks->where('quantite_invendu', '>', 0)
                ->sortByDesc(function ($item) {
                    return $item->quantite_invendu * $item->prix;
                })
                ->take(10)
                ->values()
                ->all();
                
            // Tendance sur les 3 derniers mois
            $tendance = $this->getTendanceAvaries();
            
            $data = [
                'stats' => [
                    'total_produits' => $totalProduits,
                    'produits_avec_avaries' => $produitsAvecAvaries,
                    'pourcentage_avaries' => $totalProduits > 0 ? ($produitsAvecAvaries / $totalProduits) * 100 : 0,
                    'total_avaries' => $totalAvaries,
                    'valeur_avaries' => $valeurAvaries,
                    'total_invendus' => $totalInvendus,
                    'valeur_invendus' => $valeurInvendus,
                ],
                'top_avaries' => $topAvaries,
                'top_invendus' => $topInvendus,
                'tendance' => $tendance
            ];
            
            Log::info('Spoilage data collected successfully', [
                'data_size' => strlen(json_encode($data))
            ]);
            
            return $data;
        } catch (\Exception $e) {
            Log::error('Error collecting spoilage data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Erreur lors de la collecte des données d\'avaries: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get spoilage trends for the last 3 months
     */
    private function getTendanceAvaries()
    {
        $months = [];
        $currentMonth = Carbon::now();
        
        for ($i = 2; $i >= 0; $i--) {
            $month = $currentMonth->copy()->subMonths($i);
            $months[] = [
                'month' => $month->format('Y-m'),
                'month_name' => $month->locale('fr')->isoFormat('MMMM YYYY')
            ];
        }
        
        $tendance = [];
        
        foreach ($months as $month) {
            $startDate = Carbon::createFromFormat('Y-m', $month['month'])->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $month['month'])->endOfMonth();
            
            $stockHistory = DB::table('produit_stocks')
                ->join('Produit_fixes', 'produit_stocks.id_produit', '=', 'Produit_fixes.code_produit')
                ->whereBetween('produit_stocks.updated_at', [$startDate, $endDate])
                ->select(
                    DB::raw('SUM(produit_stocks.quantite_avarie) as total_avaries'),
                    DB::raw('SUM(produit_stocks.quantite_avarie * Produit_fixes.prix) as valeur_avaries'),
                    DB::raw('SUM(produit_stocks.quantite_invendu) as total_invendus'),
                    DB::raw('SUM(produit_stocks.quantite_invendu * Produit_fixes.prix) as valeur_invendus')
                )
                ->first();
                
            $tendance[] = [
                'month' => $month['month_name'],
                'total_avaries' => $stockHistory->total_avaries ?? 0,
                'valeur_avaries' => $stockHistory->valeur_avaries ?? 0,
                'total_invendus' => $stockHistory->total_invendus ?? 0,
                'valeur_invendus' => $stockHistory->valeur_invendus ?? 0,
            ];
        }
        
        return $tendance;
    }
}
