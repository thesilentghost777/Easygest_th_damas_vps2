<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Depense;
use App\Models\SoldeCP;
use App\Models\CashWithdrawal;
use App\Models\ManquantTemporaire;
use App\Models\User;
use App\Models\CashierSession;
use App\Models\AvanceSalaire;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DgController extends Controller
{
  
   public function index()
{
    $user = Auth::user();
    $nom = $user->name;
    // Mois actuel et mois précédent
    $currentMonth = Carbon::now();
    $lastMonth = Carbon::now()->subMonth();

    // Chiffre d'affaires
    $currentRevenue = Transaction::where('type', 'income')
        ->whereMonth('date', $currentMonth->month)
        ->whereYear('date', $currentMonth->year)
        ->sum('amount');

    $lastMonthRevenue = Transaction::where('type', 'income')
        ->whereMonth('date', $lastMonth->month)
        ->whereYear('date', $lastMonth->year)
        ->sum('amount');

    $revenueGrowth = $this->calculateGrowth($currentRevenue, $lastMonthRevenue);

    // Dépenses
    $currentExpenses = Transaction::where('type', 'outcome')
        ->whereMonth('date', $currentMonth->month)
        ->whereYear('date', $currentMonth->year)
        ->sum('amount');

    $lastMonthExpenses = Transaction::where('type', 'outcome')
        ->whereMonth('date', $lastMonth->month)
        ->whereYear('date', $lastMonth->year)
        ->sum('amount');

    $expensesGrowth = $this->calculateGrowth($currentExpenses, $lastMonthExpenses);

    // Bénéfice net
    $currentProfit = $currentRevenue - $currentExpenses;
    $lastMonthProfit = $lastMonthRevenue - $lastMonthExpenses;
    $profitGrowth = $this->calculateGrowth($currentProfit, $lastMonthProfit);

    // Effectif total et stabilité
    $currentStaff = DB::table('users')->count();
    $lastMonthStaff = DB::table('users')
        ->where('created_at', '<', $lastMonth->endOfMonth())
        ->count();

    $staffStability = $currentStaff === $lastMonthStaff ? 'Stable' : 'Instable';

    // Données pour le graphique des revenus (année courante uniquement)
    $currentYear = Carbon::now()->year;
    $revenueChart = Transaction::where('type', 'income')
        ->whereYear('date', $currentYear)
        ->groupBy(DB::raw('MONTH(date)'))
        ->select(
            DB::raw('SUM(amount) as total'),
            DB::raw('MONTH(date) as month')
        )
        ->orderBy('month')
        ->get();

    // Données pour le graphique des dépenses (année courante uniquement)
    $expensesChart = Transaction::where('type', 'outcome')
        ->whereYear('date', $currentYear)
        ->groupBy(DB::raw('MONTH(date)'))
        ->select(
            DB::raw('SUM(amount) as total'),
            DB::raw('MONTH(date) as month')
        )
        ->orderBy('month')
        ->get();

    // Préparer les données pour le graphique combiné
    $chartData = $this->prepareChartDataCurrentYear($revenueChart, $expensesChart, $currentYear);

    // Demandes d'avance de salaire en attente
    $pendingRequests = AvanceSalaire::where('flag', false)
        ->with('employe')
        ->orderBy('created_at', 'desc')
        ->where('sommeAs', '>', 0)
        ->take(5)
        ->get();

    return view('dashboard.index', [
        'revenue' => [
            'current' => $currentRevenue,
            'growth' => $revenueGrowth
        ],
        'profit' => [
            'current' => $currentProfit,
            'growth' => $profitGrowth
        ],
        'expenses' => [
            'current' => $currentExpenses,
            'growth' => $expensesGrowth
        ],
        'staff' => [
            'total' => $currentStaff,
            'stability' => $staffStability,
            'nom' => $nom
        ],
        'chartData' => $chartData,
        'pendingRequests' => $pendingRequests
    ]);
}

private function calculateGrowth($current, $previous)
{
    if ($previous == 0) return 100;
    return round((($current - $previous) / $previous) * 100, 2);
}

private function prepareChartDataCurrentYear($revenueData, $expensesData, $currentYear)
{
    // Créer un tableau pour tous les mois de l'année courante (1-12)
    $months = [];
    $revenues = [];
    $expenses = [];
    $profits = [];
    
    // Noms des mois en français
    $monthNames = [
        1 => 'Jan', 2 => 'Fév', 3 => 'Mar', 4 => 'Avr', 5 => 'Mai', 6 => 'Juin',
        7 => 'Juil', 8 => 'Août', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc'
    ];
    
    // Initialiser tous les mois de l'année courante
    for ($month = 1; $month <= 12; $month++) {
        $months[] = $monthNames[$month] . ' ' . $currentYear;
        $revenues[$month] = 0;
        $expenses[$month] = 0;
    }
    
    // Remplir les données de revenus
    foreach ($revenueData as $item) {
        if ($item->month >= 1 && $item->month <= 12) {
            $revenues[$item->month] = (float) $item->total;
        }
    }
    
    // Remplir les données de dépenses
    foreach ($expensesData as $item) {
        if ($item->month >= 1 && $item->month <= 12) {
            $expenses[$item->month] = (float) $item->total;
        }
    }
    
    // Calculer les bénéfices pour chaque mois
    for ($month = 1; $month <= 12; $month++) {
        $profits[] = $revenues[$month] - $expenses[$month];
    }
    
    // Convertir les tableaux associatifs en tableaux indexés
    $revenuesArray = [];
    $expensesArray = [];
    
    for ($month = 1; $month <= 12; $month++) {
        $revenuesArray[] = $revenues[$month];
        $expensesArray[] = $expenses[$month];
    }
    
    return [
        'labels' => $months,
        'revenues' => $revenuesArray,
        'expenses' => $expensesArray,
        'profits' => $profits
    ];
}

    public function depenses(Request $request)
    {
        // Filtres pour les dépenses
        $dateDebut = $request->input('date_debut', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->input('date_fin', Carbon::now()->format('Y-m-d'));
        $type = $request->input('type');
        $cp = $request->input('cp');
        
        // Récupérer les CPs (utilisateurs avec rôle CP)
        $cps = User::where('role', 'chef_production')->get();
        
        // Construire la requête des dépenses
        $query = Depense::with('auteurRelation', 'matiere')
            ->whereBetween('date', [$dateDebut, $dateFin]);
            
        if ($type) {
            $query->where('type', $type);
        }
        
        if ($cp) {
            $query->where('auteur', $cp);
        }
        
        $depenses = $query->orderBy('date', 'desc')->paginate(15);
        
        // Obtenir le solde CP actuel
        $soldeCp = SoldeCP::latest()->first();
        
        // Calculer le total des dépenses selon les filtres
        $totalDepenses = $query->sum('prix');
        
        // Statistiques par type de dépense
        $statsByType = Depense::whereBetween('date', [$dateDebut, $dateFin])
            ->select('type', DB::raw('sum(prix) as total'))
            ->groupBy('type')
            ->get();
            
        // Statistiques par CP
        $statsByCp = Depense::whereBetween('date', [$dateDebut, $dateFin])
            ->select('auteur', DB::raw('sum(prix) as total'))
            ->groupBy('auteur')
            ->with('auteurRelation')
            ->get();
        
        return view('dg.depenses', compact(
            'depenses', 
            'soldeCp', 
            'dateDebut', 
            'dateFin', 
            'type', 
            'cp',
            'cps',
            'totalDepenses',
            'statsByType',
            'statsByCp'
        ));
    }
    
    /**
     * Affiche les sessions des caissières
     */
    public function sessions(Request $request)
    {
        // Filtres pour les sessions
        $dateDebut = $request->input('date_debut', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $dateFin = $request->input('date_fin', Carbon::now()->format('Y-m-d'));
        $caissiere = $request->input('caissiere');
        $status = $request->input('status');
        
        // Récupérer les caissières
        $caissieres = User::where('role', 'caissiere')
            ->orWhere('secteur', 'caisse')
            ->get();
        
        // Construire la requête des sessions
        $query = CashierSession::with(['user', 'cashWithdrawals'])
            ->whereBetween('start_time', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59']);
            
        if ($caissiere) {
            $query->where('user_id', $caissiere);
        }
        
        if ($status == 'active') {
            $query->whereNull('end_time');
        } elseif ($status == 'closed') {
            $query->whereNotNull('end_time');
        }
        
        $sessions = $query->orderBy('start_time', 'desc')->paginate(15);
        
        // Statistiques
        // Nombre de sessions par caissière
        $sessionsByUser = CashierSession::whereBetween('start_time', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
            ->select('user_id', DB::raw('count(*) as total_sessions'))
            ->groupBy('user_id')
            ->with('user')
            ->get();
            
        // Total des écarts (discrepancy) par caissière
        $discrepanciesByUser = CashierSession::whereBetween('start_time', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
            ->whereNotNull('discrepancy')
            ->select('user_id', DB::raw('sum(discrepancy) as total_discrepancy'))
            ->groupBy('user_id')
            ->with('user')
            ->get();
        
        return view('dg.sessions', compact(
            'sessions', 
            'dateDebut', 
            'dateFin', 
            'caissiere', 
            'status',
            'caissieres',
            'sessionsByUser',
            'discrepanciesByUser'
        ));
    }
    
    /**
     * Calcule le manquant pour une session
     */
    public function calculateMissing(Request $request, CashierSession $session)
    {
        $request->validate([
            'vente_alimentation' => 'required|numeric|min:0',
        ]);
        
        $venteAlimentation = $request->input('vente_alimentation');
        
        // Calcul du manquant
        // montant initial mobile money + montant initial des ventes + versement - montant prix a la caissiere par les membres de l'administration - montant des ventes d'alimentation

        $totalWithdrawals = $session->getTotalWithdrawals();
        $ecart_caisse = $session->final_mobile_balance - $session->initial_mobile_balance;
        $vente_en_espece = $venteAlimentation - $ecart_caisse;
        $somme_attendu = $vente_en_espece
                        +$session->initial_cash +
                        +$session->initial_change
                        -$session->final_change
                        -$totalWithdrawals;
        $manquantt = $somme_attendu-($session->cash_remitted ?? 0); 
        if ($manquantt > 0) {
            $manquant = $manquantt;
        }else{
            $manquant = $manquantt;
        }
        return response()->json([
            'manquant' => $manquant,
            'details' => [
                'initial_mobile' => $session->initial_mobile_balance,
                'initial_cash' => $session->initial_cash,
                'cash_remitted' => $session->cash_remitted ?? 0,
                'total_withdrawals' => $totalWithdrawals,
                'vente_alimentation' => $venteAlimentation,
            ]
        ]);
    }
    
    /**
     * Valide un manquant temporaire
     */
    public function validateMissing(Request $request, CashierSession $session)
    {
        $request->validate([
            'montant' => 'required|numeric',
            'explication' => 'nullable|string|max:500',
        ]);
        
        $manquantTemporaire = ManquantTemporaire::create([
            'employe_id' => $session->user_id,
            'montant' => $request->input('montant'),
            'explication' => $request->input('explication'),
            'statut' => 'en_attente',
            'valide_par' => auth()->id(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Manquant temporaire créé avec succès',
            'manquant_id' => $manquantTemporaire->id
        ]);
    }
    
    /**
     * Récupère les détails d'une session
     */
    public function getSessionDetails(CashierSession $session)
    {
        $session->load(['user', 'cashWithdrawals']);
        
        return response()->json([
            'session' => $session,
            'withdrawals' => $session->cashWithdrawals,
            'total_withdrawals' => $session->getTotalWithdrawals(),
        ]);
    }
}
