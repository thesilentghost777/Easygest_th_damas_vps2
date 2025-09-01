<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\TransactionVente;
use App\Models\Utilisation;
use Carbon\Carbon;

class IceCreamSectorService
{
    /**
     * Collect ice cream sector data for AI analysis
     */
    public function collectIceCreamData($month, $year)
    {
        Log::info('Collecting ice cream sector data', [
            'month' => $month,
            'year' => $year
        ]);
        
        try {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
            // Trouver les utilisateurs du secteur glace
            $usersGlace = User::where('secteur', 'glace')
                ->where('role', 'glace')
                ->select('id', 'name')
                ->get();
                
            $userIds = $usersGlace->pluck('id')->toArray();
            
            // Analyser la production de glaces
            $productionGlace = Utilisation::join('Produit_fixes', 'Utilisation.produit', '=', 'Produit_fixes.code_produit')
                ->whereIn('Utilisation.producteur', $userIds)
                ->whereBetween('Utilisation.created_at', [$startDate, $endDate])
                ->select(
                    'Produit_fixes.code_produit',
                    'Produit_fixes.nom',
                    DB::raw('SUM(Utilisation.quantite_produit) as quantite_totale'),
                    'Utilisation.producteur',
                    DB::raw('COUNT(DISTINCT Utilisation.id_lot) as nombre_lots')
                )
                ->groupBy('Produit_fixes.code_produit', 'Produit_fixes.nom', 'Utilisation.producteur')
                ->get();
                
            // Analyser les ventes de glaces
            $ventesGlace = TransactionVente::join('Produit_fixes', 'transaction_ventes.produit', '=', 'Produit_fixes.code_produit')
                ->join('users', 'transaction_ventes.serveur', '=', 'users.id')
                ->where(function($query) {
                    $query->where('users.secteur', 'glace')
                          ->where('users.role', 'glace');
                })
                ->whereBetween('date_vente', [$startDate, $endDate])
                ->select(
                    'Produit_fixes.code_produit',
                    'Produit_fixes.nom',
                    DB::raw('SUM(transaction_ventes.quantite) as quantite_vendue'),
                    DB::raw('SUM(transaction_ventes.quantite * transaction_ventes.prix) as chiffre_affaires'),
                    DB::raw('COUNT(DISTINCT transaction_ventes.serveur) as nombre_vendeurs')
                )
                ->groupBy('Produit_fixes.code_produit', 'Produit_fixes.nom')
                ->get();
                
            // Tendance mensuelle
            $tendanceMensuelle = $this->getIceCreamMonthlyTrend();
            
            // Personnel du secteur glace
            $personnelGlace = $usersGlace->map(function($user) use ($startDate, $endDate) {
                // Horaires et présence
                $presenceDonnees = DB::table('Horaire')
                    ->where('employe', $user->id)
                    ->whereBetween('arrive', [$startDate, $endDate])
                    ->select(
                        DB::raw('COUNT(*) as jours_presence'),
                        DB::raw('SUM(TIMESTAMPDIFF(HOUR, arrive, depart)) as heures_travaillees')
                    )
                    ->first();
                
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'presence' => [
                        'jours' => $presenceDonnees->jours_presence ?? 0,
                        'heures' => $presenceDonnees->heures_travaillees ?? 0
                    ]
                ];
            });
                
            $data = [
                'production' => [
                    'total_produits' => $productionGlace->count(),
                    'quantite_totale' => $productionGlace->sum('quantite_totale'),
                    'nombre_lots' => $productionGlace->sum('nombre_lots'),
                    'detail_par_produit' => $productionGlace
                ],
                'ventes' => [
                    'total_produits' => $ventesGlace->count(),
                    'chiffre_affaires' => $ventesGlace->sum('chiffre_affaires'),
                    'quantite_vendue' => $ventesGlace->sum('quantite_vendue'),
                    'detail_par_produit' => $ventesGlace
                ],
                'tendance' => $tendanceMensuelle,
                'personnel' => $personnelGlace
            ];
            
            Log::info('Ice cream sector data collected successfully', [
                'data_size' => strlen(json_encode($data))
            ]);
            
            return $data;
        } catch (\Exception $e) {
            Log::error('Error collecting ice cream sector data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Erreur lors de la collecte des données du secteur glace: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get ice cream monthly trend
     */
    private function getIceCreamMonthlyTrend()
    {
        $trends = [];
        $currentDate = Carbon::now();
        
        // Analyse des 6 derniers mois
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = $currentDate->copy()->subMonths($i);
            $startOfMonth = $monthDate->copy()->startOfMonth();
            $endOfMonth = $monthDate->copy()->endOfMonth();
            
            // Utilisateurs du secteur glace
            $userIds = User::where('secteur', 'glace')
                ->orWhere('role', 'glace')
                ->pluck('id')
                ->toArray();
            
            // Ventes du mois pour le secteur glace
            $ventesData = TransactionVente::join('users', 'transaction_ventes.serveur', '=', 'users.id')
                ->where(function($query) {
                    $query->where('users.secteur', 'glace')
                          ->orWhere('users.role', 'glace');
                })
                ->whereBetween('date_vente', [$startOfMonth, $endOfMonth])
                ->selectRaw('SUM(transaction_ventes.quantite * transaction_ventes.prix) as total_ventes, SUM(transaction_ventes.quantite) as quantite')
                ->first();
                
            // Production du mois pour le secteur glace
            $productionData = Utilisation::whereIn('producteur', $userIds)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->selectRaw('SUM(quantite_produit) as total_production')
                ->first();
                
            $trends[] = [
                'mois' => $monthDate->locale('fr')->isoFormat('MMM YYYY'),
                'ventes' => $ventesData->total_ventes ?? 0,
                'quantite_vendue' => $ventesData->quantite ?? 0,
                'production' => $productionData->total_production ?? 0
            ];
        }
        
        return $trends;
    }
}
