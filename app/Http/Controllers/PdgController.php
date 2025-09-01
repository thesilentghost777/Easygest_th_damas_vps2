<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionVente;
use App\Models\Deli;
use App\Models\ManquantTemporaire;
use App\Models\CashDistribution;
use App\Models\Complexe;
use Carbon\Carbon;

class PdgController extends Controller
{
    
    public function dashboard(Request $request)
    {
        // Get date range from request or use defaults
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::today();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::today()->endOfDay();
        $periodType = $request->input('period_type', 'day'); // day, week, month, custom
        
        // Adjust dates based on period type
        if ($periodType === 'week') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek()->endOfDay();
        } elseif ($periodType === 'month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth()->endOfDay();
        } elseif ($periodType === 'year') {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear()->endOfDay();
        }
        
        // Format for display
        $periodLabel = $this->getPeriodLabel($startDate, $endDate, $periodType);
        
        // Effectif total
        $totalEmployees = User::count();
        
        // Répartition par secteur
        $employeesBySector = User::select('secteur', DB::raw('count(*) as count'))
            ->whereNotNull('secteur')
            ->groupBy('secteur')
            ->get()
            ->map(function($item) {
                return [
                    'sector' => $item->secteur ?: 'Non défini',
                    'count' => $item->count,
                ];
            });
            
        // Chiffre d'affaires (transactions entrantes) - Filtré par période
        $totalRevenue = Transaction::where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
        
        // Total dépenses (transactions sortantes) - Filtré par période
        $totalExpenses = Transaction::where('type', 'outcome')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
        
        // Évolution du CA par mois (derniers 12 mois)
        $revenueByMonth = $this->getRevenueByMonth();
        
        // Production de la période
        // Production de la période (sans doublons)
$productionValue = DB::table('Utilisation')
    ->join('Produit_fixes', 'Utilisation.produit', '=', 'Produit_fixes.code_produit')
    ->whereBetween('Utilisation.created_at', [$startDate, $endDate])
    ->select(
        'Utilisation.id_lot',
        'Utilisation.quantite_produit',
        'Produit_fixes.prix',
        DB::raw('(Utilisation.quantite_produit * Produit_fixes.prix) as production_value')
    )
    ->groupBy('Utilisation.id_lot', 'Utilisation.quantite_produit', 'Produit_fixes.prix')
    ->get()
    ->sum('production_value');

            
        $productionValuePeriod = $productionValue ? $productionValue : 0;
        
        // Dépenses matières premières pour la période
        $rawMaterialCost = DB::table('Utilisation')
            ->join('Matiere', 'Utilisation.matierep', '=', 'Matiere.id')
            ->whereBetween('Utilisation.created_at', [$startDate, $endDate])
            ->select(
                DB::raw('SUM(Utilisation.quantite_matiere * Matiere.prix_par_unite_minimale) as total_cost')
            )
            ->first();
            
        $rawMaterialCostPeriod = $rawMaterialCost ? $rawMaterialCost->total_cost : 0;
        
        // Ventes de la période
        $salesPeriod = TransactionVente::whereBetween('date_vente', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->sum(DB::raw('quantite * prix'));
            
        // Évolution des ventes par mois
        $salesByMonth = $this->getSalesByMonth();
        
        // Versements alimentation (caissière)
        $cashierDeposits = DB::table('Versement_chef')
            ->join('users', 'Versement_chef.verseur', '=', 'users.id')
            ->where('users.role', 'caissiere')
            ->where('Versement_chef.status', 1) // Validés uniquement
            ->whereBetween('Versement_chef.date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->sum('montant');
            
        // Versements glace
        $iceDeposits = DB::table('Versement_chef')
            ->join('users', 'Versement_chef.verseur', '=', 'users.id')
            ->where('users.role', 'glace')
            ->where('Versement_chef.status', 1) // Validés uniquement
            ->whereBetween('Versement_chef.date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->sum('montant');
            
        // Versements ventes (vendeuses)
        $salesDeposits = DB::table('Versement_chef')
            ->join('users', 'Versement_chef.verseur', '=', 'users.id')
            ->where('users.secteur', 'vente')
            ->where('Versement_chef.status', 1) // Validés uniquement
            ->whereBetween('Versement_chef.date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->sum('montant');
            
        // Incidents (infractions)
        $incidentsCount = DB::table('deli_user')
            ->whereBetween('date_incident', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->count();
            
        $incidentsAmount = DB::table('deli_user')
            ->join('delis', 'deli_user.deli_id', '=', 'delis.id')
            ->whereBetween('deli_user.date_incident', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->sum('delis.montant');
            
        $recentIncidents = DB::table('deli_user')
            ->join('delis', 'deli_user.deli_id', '=', 'delis.id')
            ->join('users', 'deli_user.user_id', '=', 'users.id')
            ->select('delis.nom as incident', 'users.name as employee', 'deli_user.date_incident', 'delis.montant')
            ->whereBetween('deli_user.date_incident', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('deli_user.date_incident', 'desc')
            ->limit(5)
            ->get();
            
        // Profit/Bénéfice (Chiffre d'affaires - Dépenses)
        $profit = $totalRevenue - $totalExpenses;
        $profitMargin = $totalRevenue > 0 ? ($profit / $totalRevenue) * 100 : 0;
       
      

        return view('pages.pdg.pdg_dashboard', compact(
            'totalEmployees',
            'employeesBySector',
            'totalRevenue',
            'totalExpenses',
            'revenueByMonth',
            'productionValuePeriod',
            'rawMaterialCostPeriod',
            'salesPeriod',
            'salesByMonth',
            'cashierDeposits',
            'iceDeposits',
            'salesDeposits',
            'incidentsCount',
            'incidentsAmount',
            'recentIncidents',
            'profit',
            'profitMargin',
            'startDate',
            'endDate',
            'periodType',
            'periodLabel',
        ));
    }
    
    private function getPeriodLabel($startDate, $endDate, $periodType)
    {
        if ($periodType === 'day') {
            return $startDate->format('d/m/Y');
        } elseif ($periodType === 'week') {
            return 'Semaine du ' . $startDate->format('d/m/Y') . ' au ' . $endDate->format('d/m/Y');
        } elseif ($periodType === 'month') {
            return $startDate->format('F Y');
        } elseif ($periodType === 'year') {
            return $startDate->format('Y');
        } else {
            return 'Du ' . $startDate->format('d/m/Y') . ' au ' . $endDate->format('d/m/Y');
        }
    }
    
    private function getRevenueByMonth()
    {
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        
        return Transaction::where('type', 'income')
            ->where('date', '>=', $startDate)
            ->select(
                DB::raw("DATE_FORMAT(date, '%Y-%m') as month"),
                DB::raw("SUM(amount) as total")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                return [
                    'month' => Carbon::parse($item->month . '-01')->format('M Y'),
                    'total' => $item->total,
                ];
            });
    }
    
    private function getSalesByMonth()
    {
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        
        return TransactionVente::where('date_vente', '>=', $startDate->format('Y-m-d'))
            ->select(
                DB::raw("DATE_FORMAT(date_vente, '%Y-%m') as month"),
                DB::raw("SUM(quantite * prix) as total")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                return [
                    'month' => Carbon::parse($item->month . '-01')->format('M Y'),
                    'total' => $item->total,
                ];
            });
    }
}