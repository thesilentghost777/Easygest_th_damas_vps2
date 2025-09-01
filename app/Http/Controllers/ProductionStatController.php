<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProductionStatController extends Controller
{
    public function index()
    {
        // Récupération des statistiques du jour précédent
        $yesterday = Carbon::yesterday();
        $yesterdayStats = $this->getStatsForDate($yesterday);
        
        // Données pour les graphiques
        $productionChartData = $this->getProductionChartData();
        $materialsChartData = $this->getMaterialsChartData();
        $evolutionData = $this->getEvolutionData();
        
        $user = Auth::user();
        
        return view('production.stats.index', compact(
            'yesterdayStats',
            'productionChartData', 
            'materialsChartData',
            'evolutionData',
            'user'
        ));
    }
    
    public function customStats(Request $request)
    {
        $period = $request->get('period', 'day');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $dateRange = $this->getDateRange($period, $startDate, $endDate);
        $stats = $this->getStatsForPeriod($dateRange['start'], $dateRange['end']);
        
        if ($period === 'day') {
           $stats = $this->getStatsForDate(Carbon::today());
           Log::info("message", ['stats' => $stats]);
           return response()->json($stats);
        }

        
        Log::info('Stats calculées:', $stats);
        return response()->json($stats);
    }
    
    public function productionDetails(Request $request)
    {
        $query = DB::table('Utilisation')
            ->join('Produit_fixes', 'Utilisation.produit', '=', 'Produit_fixes.code_produit')
            ->join('users', 'Utilisation.producteur', '=', 'users.id')
            ->select(
                'Utilisation.id_lot',
                'Utilisation.created_at',
                'Produit_fixes.nom as nom_produit',
                'Produit_fixes.prix as prix_produit',
                'users.name as nom_producteur',
                'Utilisation.quantite_produit'
            )
            ->groupBy('Utilisation.id_lot', 'Utilisation.created_at', 'Produit_fixes.nom', 'Produit_fixes.prix', 'users.name', 'Utilisation.quantite_produit');
        
        // Filtres
        if ($request->filled('period_start') && $request->filled('period_end')) {
            $query->whereBetween('Utilisation.created_at', [
                $request->period_start . ' 00:00:00',
                $request->period_end . ' 23:59:59'
            ]);
        }
        
        if ($request->filled('producteur')) {
            $query->where('Utilisation.producteur', $request->producteur);
        }
        
        if ($request->filled('produit')) {
            $query->where('Utilisation.produit', $request->produit);
        }
        
        if ($request->filled('id_lot')) {
            $query->where('Utilisation.id_lot', 'like', '%' . $request->id_lot . '%');
        }
        
        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $productions = $query->orderBy($sortBy, $sortOrder)->paginate(20);
        
        // Calculer les détails pour chaque production
        foreach ($productions as $production) {
            $details = $this->getProductionDetails($production->id_lot);
            $production->valeur_production = $details['valeur_production'];
            $production->cout_matieres = $details['cout_matieres'];
            $production->benefice = $details['benefice'];
            $production->matieres = $details['matieres'];
        }
        
        // Données pour les filtres
        $producteurs = DB::table('users')->where('secteur', 'production')->orWhere('secteur','glace')->select('id', 'name')->get();
        $produits = DB::table('Produit_fixes')->select('code_produit', 'nom')->get();
        
        return view('production.stats.details', compact('productions', 'producteurs', 'produits'));
    }
    
    private function getStatsForDate($date)
    {
        
      
        $productionValue = DB::table('Utilisation')
            ->join('Produit_fixes', 'Utilisation.produit', '=', 'Produit_fixes.code_produit')
            ->whereDate('Utilisation.created_at', $date)
            ->select(
                'Utilisation.id_lot',
                'Utilisation.quantite_produit',
                'Produit_fixes.prix'
            )
            ->get()
            ->groupBy('id_lot')
            ->sum(function ($lotUtilisations) {
                $premiere = $lotUtilisations->first();
                return $premiere->quantite_produit * $premiere->prix;
            });



        // Valeur des matières assignées avec conversion d'unités
        $assignations = DB::table('assignations_matiere')
        ->join('Matiere', 'assignations_matiere.matiere_id', '=', 'Matiere.id')
        ->whereDate('assignations_matiere.created_at', $date)
        ->select(
            'assignations_matiere.quantite_assignee',
            'assignations_matiere.unite_assignee',
            'Matiere.unite_minimale',
            'Matiere.prix_par_unite_minimale'
        )
        ->get();

        $assignedMaterialsValue = 0;
        $uniteConversionService = new \App\Services\UniteConversionService();

        foreach ($assignations as $assignation) {
            try {
                $quantiteEnUniteMinimale = $uniteConversionService->convertir(
                    $assignation->quantite_assignee,
                    $assignation->unite_assignee,
                    $assignation->unite_minimale
                );
                
                $assignedMaterialsValue += $quantiteEnUniteMinimale * $assignation->prix_par_unite_minimale;
            } catch (\Exception $e) {
                Log::error("Erreur de conversion d'unité: " . $e->getMessage());
            }
        }
        

        // Valeur des matières utilisées
        $usedMaterialsValue = DB::table('Utilisation')
            ->join('Matiere', 'Utilisation.matierep', '=', 'Matiere.id')
            ->whereDate('Utilisation.created_at', $date)
            ->sum(DB::raw('Utilisation.quantite_matiere * Matiere.prix_par_unite_minimale'));
        
        // Production attendue
        $expectedProductionValue = DB::table('Daily_assignments')
            ->join('Produit_fixes', 'Daily_assignments.produit', '=', 'Produit_fixes.code_produit')
            ->whereDate('Daily_assignments.assignment_date', $date)
            ->sum(DB::raw('Daily_assignments.expected_quantity * Produit_fixes.prix'));
        
        $estimatedBenefit = $productionValue - $usedMaterialsValue;
        
        return [
            'production_value' => $productionValue,
            'assigned_materials_value' => $assignedMaterialsValue,
            'used_materials_value' => $usedMaterialsValue,
            'expected_production_value' => $expectedProductionValue,
            'estimated_benefit' => $estimatedBenefit
        ];
    }
    
    private function getStatsForPeriod($startDate, $endDate)
    {
        $startDateTime = Carbon::parse($startDate)->startOfDay();
        $endDateTime = Carbon::parse($endDate)->endOfDay();
        Log::info("message", ['startDate' => $startDate, 'endDate' => $endDate]);
        // Valeur de la production
        $productionValue = DB::table('Utilisation')
            ->join('Produit_fixes', 'Utilisation.produit', '=', 'Produit_fixes.code_produit')
            ->whereBetween('Utilisation.created_at', [$startDateTime, $endDateTime])
            ->select(       
                'Utilisation.id_lot',
                'Utilisation.quantite_produit',
                'Produit_fixes.prix'
            )
            ->get()
            ->groupBy('id_lot')
            ->sum(function ($lotUtilisations) {     
                $premiere = $lotUtilisations->first();
                return $premiere->quantite_produit * $premiere->prix;
            });
        
        // Valeur des matières assignées avec conversion d'unités
        $assignations = DB::table('assignations_matiere')
        ->join('Matiere', 'assignations_matiere.matiere_id', '=', 'Matiere.id')
        ->whereBetween('assignations_matiere.created_at', [$startDateTime, $endDateTime])
        ->select(
            'assignations_matiere.quantite_assignee',
            'assignations_matiere.unite_assignee',
            'Matiere.unite_minimale',
            'Matiere.prix_par_unite_minimale'
        )
        ->get();

        $assignedMaterialsValue = 0;
        $uniteConversionService = new \App\Services\UniteConversionService();

        foreach ($assignations as $assignation) {
        try {
            $quantiteEnUniteMinimale = $uniteConversionService->convertir(
                $assignation->quantite_assignee,
                $assignation->unite_assignee,
                $assignation->unite_minimale
            );
            $assignedMaterialsValue += $quantiteEnUniteMinimale * $assignation->prix_par_unite_minimale;
        } catch (\Exception $e) {
            Log::error("Erreur de conversion d'unité: " . $e->getMessage());
        }
        }


        // Valeur des matières utilisées
        $usedMaterialsValue = DB::table('Utilisation')
            ->join('Matiere', 'Utilisation.matierep', '=', 'Matiere.id')
            ->whereBetween('Utilisation.created_at', [$startDateTime, $endDateTime])
            ->sum(DB::raw('Utilisation.quantite_matiere * Matiere.prix_par_unite_minimale'));
        
        // Production attendue
        $expectedProductionValue = DB::table('Daily_assignments')
            ->join('Produit_fixes', 'Daily_assignments.produit', '=', 'Produit_fixes.code_produit')
            ->whereBetween('Daily_assignments.assignment_date', [$startDateTime, $endDateTime])
            ->sum(DB::raw('Daily_assignments.expected_quantity * Produit_fixes.prix'));
        
        $estimatedBenefit = $productionValue - $usedMaterialsValue;
        
        return [
            'production_value' => $productionValue,
            'assigned_materials_value' => $assignedMaterialsValue,
            'used_materials_value' => $usedMaterialsValue,
            'expected_production_value' => $expectedProductionValue,
            'estimated_benefit' => $estimatedBenefit
        ];
    }
    
    private function getProductionChartData()
    {
        return DB::table('Utilisation')
            ->join('Produit_fixes', 'Utilisation.produit', '=', 'Produit_fixes.code_produit')
            ->select(
                'Produit_fixes.nom',
                DB::raw('SUM(Utilisation.quantite_produit * Produit_fixes.prix) as total_value')
            )
            ->groupBy('Produit_fixes.nom')
            ->orderBy('total_value', 'desc')
            ->get();
    }
    
    private function getMaterialsChartData()
    {
        return DB::table('assignations_matiere')
            ->join('Matiere', 'assignations_matiere.matiere_id', '=', 'Matiere.id')
            ->select(
                'Matiere.nom',
                DB::raw('SUM(assignations_matiere.quantite_assignee * Matiere.prix_par_unite_minimale) as total_value')
            )
            ->groupBy('Matiere.nom')
            ->orderBy('total_value', 'desc')
            ->get();
    }
    
    private function getEvolutionData()
    {
        $last30Days = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $stats = $this->getStatsForDate($date);
            $last30Days->push([
                'date' => $date->format('Y-m-d'),
                'production_value' => $stats['production_value'],
                'assigned_materials_value' => $stats['assigned_materials_value'],
                'estimated_benefit' => $stats['estimated_benefit']
            ]);
        }
        return $last30Days;
    }
    
    private function getProductionDetails($idLot)
    {
        $utilisations = DB::table('Utilisation')
            ->join('Produit_fixes', 'Utilisation.produit', '=', 'Produit_fixes.code_produit')
            ->join('Matiere', 'Utilisation.matierep', '=', 'Matiere.id')
            ->where('Utilisation.id_lot', $idLot)
            ->select(
                'Produit_fixes.prix as prix_produit',
                'Utilisation.quantite_produit',
                'Matiere.nom as nom_matiere',
                'Matiere.prix_par_unite_minimale',
                'Utilisation.quantite_matiere',
                'Utilisation.unite_matiere'
            )
            ->get();
        
        $valeurProduction = 0;
        $coutMatieres = 0;
        $matieres = [];
        
        foreach ($utilisations as $utilisation) {
            if ($valeurProduction == 0) {
                $valeurProduction = $utilisation->quantite_produit * $utilisation->prix_produit;
            }
            
            $coutMatiere = $utilisation->quantite_matiere * $utilisation->prix_par_unite_minimale;
            $coutMatieres += $coutMatiere;
            
            $matieres[] = [
                'nom' => $utilisation->nom_matiere,
                'quantite' => $utilisation->quantite_matiere,
                'unite' => $utilisation->unite_matiere,
                'cout' => $coutMatiere
            ];
        }
        
        return [
            'valeur_production' => $valeurProduction,
            'cout_matieres' => $coutMatieres,
            'benefice' => $valeurProduction - $coutMatieres,
            'matieres' => $matieres
        ];
    }
    
    private function getDateRange($period, $startDate = null, $endDate = null)
    {
        switch ($period) {
            case 'week':
                return [
                    'start' => Carbon::now()->startOfWeek()->format('Y-m-d'),
                    'end' => Carbon::now()->endOfWeek()->format('Y-m-d')
                ];
            case 'month':
                return [
                    'start' => Carbon::now()->startOfMonth()->format('Y-m-d'),
                    'end' => Carbon::now()->endOfMonth()->format('Y-m-d')
                ];
            case 'year':
                return [
                    'start' => Carbon::now()->startOfYear()->format('Y-m-d'),
                    'end' => Carbon::now()->endOfYear()->format('Y-m-d')
                ];
            case 'custom':
                return [
                    'start' => $startDate ?: Carbon::now()->format('Y-m-d'),
                    'end' => $endDate ?: Carbon::now()->format('Y-m-d')
                ];
            default: // day
                return [
                    'start' => Carbon::now()->format('Y-m-d'),
                    'end' => Carbon::now()->format('Y-m-d')
                ];
        }
    }
}
