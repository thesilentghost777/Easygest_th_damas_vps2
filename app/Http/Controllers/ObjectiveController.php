<?php

namespace App\Http\Controllers;

use App\Models\Objective;
use App\Models\ObjectiveProgress;
use App\Models\SubObjective;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionVente;
use App\Models\VersementChef;
use App\Models\Produit_fixes;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Traits\HistorisableActions;

class ObjectiveController extends Controller
{
    use HistorisableActions;
    /**
     * Display a listing of the objectives.
     */
    public function index()
    {
        $user = Auth::user();
        if($user->role !='pdg' || $user->role !='dg'){
            $objectives = Objective::where('is_active', true)
            ->orderBy('end_date', 'asc')
            ->get();
        }else{
            $objectives = Objective::where('user_id', $user->id)
            ->where('is_active', true)
            ->orderBy('end_date', 'asc')
            ->get();
        }
        
        
        $activeObjectives = $objectives->take(5); // Prendre les 5 premiers objectifs actifs
        $pastObjectives = Objective::where('user_id', $user->id)
            ->where('is_active', false)
            ->orderBy('end_date', 'desc')
            ->take(5) // Prendre les 5 derniers objectifs passés
            ->get();
        
        // Mettre à jour les progrès pour chaque objectif
        foreach ($activeObjectives as $objective) {
            $this->updateObjectiveProgress($objective);
        }
        
        return view('objectives.index', compact('activeObjectives', 'pastObjectives'));
    }
    /**
     * Show the form for creating a new objective.
     */
    public function create()
    {
        $expenseCategories = Category::all();
        return view('objectives.create', compact('expenseCategories'));
    }

    /**
     * Store a newly created objective in storage.
     */
    public function store(Request $request)
    {
        Log::info('Objective store method called');
        Log::info('Request data:', $request->all());
        
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'target_amount' => 'required|numeric|min:1',
                'period_type' => ['required', Rule::in(['daily', 'weekly', 'monthly', 'yearly'])],
                'start_date' => 'required|date|after_or_equal:' . date('Y-m-d', strtotime('-1 day')),
                'end_date' => 'required|date|after_or_equal:start_date',
                'sector' => ['required', Rule::in(['alimentation', 'boulangerie-patisserie', 'glace', 'global'])],
                'goal_type' => ['required', Rule::in(['revenue', 'profit'])],
                'expense_categories' => 'nullable|array',
                'expense_categories.*' => 'exists:categories,id',
                'use_standard_sources' => 'required|boolean',
                'custom_users' => 'nullable|array',
                'custom_users.*' => 'exists:users,id',
                'custom_categories' => 'nullable|array',
                'custom_categories.*' => 'exists:categories,id',
            ]);
            
            Log::info('Validation passed');
            
            // Calculer automatiquement la date de fin si nécessaire
            if ($request->period_type === 'daily') {
                $validatedData['end_date'] = $request->start_date;
            } elseif ($request->period_type === 'weekly') {
                $validatedData['end_date'] = date('Y-m-d', strtotime($request->start_date . ' + 6 days'));
            } elseif ($request->period_type === 'monthly') {
                $validatedData['end_date'] = date('Y-m-d', strtotime($request->start_date . ' + 1 month - 1 day'));
            } elseif ($request->period_type === 'yearly') {
                $validatedData['end_date'] = date('Y-m-d', strtotime($request->start_date . ' + 1 year - 1 day'));
            }
            
            $user = Auth::user();
            $validatedData['user_id'] = $user->id;
            $validatedData['is_active'] = true;
            $validatedData['is_achieved'] = false;
            $validatedData['is_confirmed'] = false;
            
            // Convertir l'utilisation des sources standard en booléen
            $validatedData['use_standard_sources'] = filter_var($request->use_standard_sources, FILTER_VALIDATE_BOOLEAN);
            
            Log::info('Creating objective with data:', $validatedData);
            $objective = Objective::create($validatedData);
            Log::info('Objective created with ID: ' . $objective->id);
            
            // Initialiser le premier enregistrement de progression
            $this->updateObjectiveProgress($objective);
            //historiser l'action
            $this->historiser("L'utilisateur " . auth()->user()->name . " a créé un objectif avec ID: {$objective->id}", 'create_objective');
            return redirect()->route('objectives.show', $objective->id)
                ->with('success', 'Objectif créé avec succès. Vous pouvez maintenant ajouter des sous-objectifs si nécessaire.');
        } catch (\Exception $e) {
            Log::error('Error creating objective: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return back()->withInput()->withErrors(['error' => 'Une erreur est survenue lors de la création de l\'objectif: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified objective.
     */
    public function show(Objective $objective)
    {
        // S'assurer que l'utilisateur ne peut voir que ses propres objectifs
        if ($objective->user_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }
        
        // Mettre à jour les progrès avant d'afficher
        Log::info("cool1");
        $this->updateObjectiveProgress($objective);
        Log::info("$objective");
        Log::info("cool2");
        // Obtenir l'historique de progression pour le graphique
        $progressHistory = $objective->progress()
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date->format('Y-m-d'),
                    'amount' => $item->current_amount,
                    'percentage' => $item->progress_percentage,
                    'expenses' => $item->expenses,
                    'profit' => $item->profit
                ];
            });
            
        // Obtenir les transactions liées à cet objectif
        $latestProgress = $objective->progress()->latest()->first();
        $transactions = [];
        
        if ($latestProgress && $latestProgress->transactions) {
            $transactionIds = $latestProgress->transactions;
            
            if ($objective->use_standard_sources) {
                // Utiliser les sources standard basées sur le secteur
                if ($objective->sector === 'alimentation') {
                    // Pour l'alimentation, chercher les versements des caissières
                    $transactions = VersementChef::whereIn('code_vc', $transactionIds)
                        ->where('status', true)
                        ->join('users', 'Versement_chef.verseur', '=', 'users.id')
                        ->where('users.role', 'caissiere')
                        ->select('Versement_chef.*', 'users.name as verseur_name')
                        ->get();
                } elseif ($objective->sector === 'boulangerie-patisserie') {
                    // Pour la boulangerie, chercher les versements des CP ou vendeurs
                    $transactions = VersementChef::whereIn('code_vc', $transactionIds)
                        ->where('status', true)
                        ->join('users', 'Versement_chef.verseur', '=', 'users.id')
                        ->where(function($query) {
                            $query->where('users.role', 'chef_production')
                                  ->orWhere('users.secteur', 'vente');
                        })
                        ->select('Versement_chef.*', 'users.name as verseur_name', 'users.role')
                        ->get();
                } elseif ($objective->sector === 'glace') {
                    // Pour les glaces, chercher les versements des responsables glace
                    $transactions = VersementChef::whereIn('code_vc', $transactionIds)
                        ->where('status', true)
                        ->join('users', 'Versement_chef.verseur', '=', 'users.id')
                        ->where('users.role', 'glace')
                        ->select('Versement_chef.*', 'users.name as verseur_name')
                        ->get();
                } elseif ($objective->sector === 'global') {
                    // Pour les objectifs globaux, récupérer les transactions
                    Log::info("Exactement");
                    $transactions = VersementChef::whereIn('code_vc', $transactionIds)
                    ->where('status', true)
                    ->get();
                    Log::info("transaction : $transactions");
                }
            } else {
                // Utiliser les sources personnalisées
                // Si des utilisateurs personnalisés sont définis, chercher leurs versements
                if ($objective->custom_users) {
                    $transactions = VersementChef::whereIn('code_vc', $transactionIds)
                        ->where('status', true)
                        ->whereIn('verseur', $objective->custom_users)
                        ->join('users', 'Versement_chef.verseur', '=', 'users.id')
                        ->select('Versement_chef.*', 'users.name as verseur_name', 'users.role')
                        ->get();
                }
                
                // Si des catégories personnalisées sont définies, chercher les transactions correspondantes
                if ($objective->custom_categories) {
                    $customCategoryTransactions = Transaction::whereIn('id', $transactionIds)
                        ->where('type', 'income')
                        ->whereIn('category_id', $objective->custom_categories)
                        ->get();
                    
                    // Fusionner avec les transactions des utilisateurs personnalisés
                    $transactions = $transactions->merge($customCategoryTransactions);
                }
            }
        }
        
        // Récupérer les produits pour les sous-objectifs (pour objectifs boulangerie-patisserie)
        $products = [];
        if ($objective->sector === 'boulangerie-patisserie' && !$objective->is_confirmed) {
            $products = Produit_fixes::all();
        }
        
        // Récupérer les sous-objectifs existants
        $subObjectives = $objective->subObjectives;
        
        // Vérifier s'il y a des incohérences pour les objectifs de boulangerie-patisserie
        $hasInconsistency = false;
        $inconsistencyAmount = 0;
        
        if ($objective->sector === 'boulangerie-patisserie' && $subObjectives->count() > 0) {
            $hasInconsistency = $objective->has_inconsistency;
            $inconsistencyAmount = $objective->inconsistency_amount;
        }
        
        return view('objectives.show', compact(
            'objective', 
            'progressHistory', 
            'transactions', 
            'products', 
            'subObjectives',
            'hasInconsistency',
            'inconsistencyAmount'
        ));
    }

    /**
     * Show the form for editing the specified objective.
     */
    public function edit(Objective $objective)
    {
        // S'assurer que l'utilisateur ne peut modifier que ses propres objectifs et non confirmés
        if ($objective->user_id !== Auth::id() || $objective->is_confirmed) {
            abort(403, 'Non autorisé');
        }
        
        $expenseCategories = Category::all();
        $allUsers = User::all();
        return view('objectives.edit', compact('objective', 'expenseCategories', 'allUsers'));
    }

    /**
     * Update the specified objective in storage.
     */
    public function update(Request $request, Objective $objective)
    {
        // S'assurer que l'utilisateur ne peut modifier que ses propres objectifs et non confirmés
        if ($objective->user_id !== Auth::id() || $objective->is_confirmed) {
            abort(403, 'Non autorisé');
        }
        
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'target_amount' => 'required|numeric|min:1',
                'period_type' => ['required', Rule::in(['daily', 'weekly', 'monthly', 'yearly'])],
                'start_date' => 'required|date|after_or_equal:' . date('Y-m-d', strtotime('-1 day')),
                'end_date' => 'required|date|after_or_equal:start_date',
                'sector' => ['required', Rule::in(['alimentation', 'boulangerie-patisserie', 'glace', 'global'])],
                'goal_type' => ['required', Rule::in(['revenue', 'profit'])],
                'expense_categories' => 'nullable|array',
                'expense_categories.*' => 'exists:categories,id',
                'use_standard_sources' => 'required|boolean',
                'custom_users' => 'nullable|array',
                'custom_users.*' => 'exists:users,id',
                'custom_categories' => 'nullable|array',
                'custom_categories.*' => 'exists:categories,id',
            ]);
            
            // Convertir l'utilisation des sources standard en booléen
            $validatedData['use_standard_sources'] = filter_var($request->use_standard_sources, FILTER_VALIDATE_BOOLEAN);
            
            // Calculer automatiquement la date de fin si nécessaire
            if ($request->period_type === 'daily') {
                $validatedData['end_date'] = $request->start_date;
            } elseif ($request->period_type === 'weekly') {
                $validatedData['end_date'] = date('Y-m-d', strtotime($request->start_date . ' + 6 days'));
            } elseif ($request->period_type === 'monthly') {
                $validatedData['end_date'] = date('Y-m-d', strtotime($request->start_date . ' + 1 month - 1 day'));
            } elseif ($request->period_type === 'yearly') {
                $validatedData['end_date'] = date('Y-m-d', strtotime($request->start_date . ' + 1 year - 1 day'));
            }
            
            $objective->update($validatedData);
            
            // Mettre à jour la progression après la modification
            $this->updateObjectiveProgress($objective);
            // Historiser l'action
            $this->historiser("L'utilisateur " . auth()->user()->name . " a mis à jour l'objectif avec ID: {$objective->id}", 'update_objective');
            return redirect()->route('objectives.show', $objective->id)
                ->with('success', 'Objectif mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Error updating objective: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour de l\'objectif: ' . $e->getMessage()]);
        }
    }

    /**
     * Confirm an objective, locking it from further modifications.
     */
    public function confirm(Objective $objective)
    {
        // S'assurer que l'utilisateur ne peut confirmer que ses propres objectifs
        if ($objective->user_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }
        
        // Vérifier si les sous-objectifs ne dépassent pas l'objectif principal
        if ($objective->sector === 'boulangerie-patisserie') {
            $totalSubObjectives = $objective->subObjectives()->sum('target_amount');
            if ($totalSubObjectives > $objective->target_amount) {
                return back()->with('error', 'Le montant total des sous-objectifs dépasse l\'objectif principal.');
            }
        }
        
        $objective->update(['is_confirmed' => true]);
        //historiser l'action
        $this->historiser("L'utilisateur " . auth()->user()->name . " a confirmé l'objectif avec ID: {$objective->id}", 'confirm_objective');
        return redirect()->route('objectives.show', $objective->id)
            ->with('success', 'Objectif confirmé. Il ne peut plus être modifié.');
    }

    /**
     * Remove the specified objective from storage.
     */
    public function destroy(Objective $objective)
    {
        // S'assurer que l'utilisateur ne peut supprimer que ses propres objectifs
        if ($objective->user_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }
        
        // Supprimer l'objectif et ses données associées (cascade delete activé dans la migration)
        $objective->delete();
        // Historiser l'action
        $this->historiser("L'utilisateur " . auth()->user()->name . " a supprimé l'objectif avec ID: {$objective->id}", 'delete_objective');
        return redirect()->route('objectives.index')
            ->with('success', 'Objectif supprimé avec succès.');
    }
    
    /**
     * Show the overall dashboard of objectives.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Récupérer les objectifs actifs par secteur
        $alimentationObjectives = Objective::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('sector', 'alimentation')
            ->get();
            
        $boulangerieObjectives = Objective::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('sector', 'boulangerie-patisserie')
            ->get();
            
        $glaceObjectives = Objective::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('sector', 'glace')
            ->get();
            
        $globalObjectives = Objective::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('sector', 'global')
            ->get();
        
        // Mettre à jour les progrès pour tous les objectifs
        foreach ($alimentationObjectives as $objective) {
            $this->updateObjectiveProgress($objective);
        }
        
        foreach ($boulangerieObjectives as $objective) {
            $this->updateObjectiveProgress($objective);
        }
        
        foreach ($glaceObjectives as $objective) {
            $this->updateObjectiveProgress($objective);
        }
        
        foreach ($globalObjectives as $objective) {
            $this->updateObjectiveProgress($objective);
        }
        
        // Statistiques générales
        $completedObjectives = Objective::where('user_id', $user->id)
            ->where('is_achieved', true)
            ->count();
            
        $activeObjectivesCount = Objective::where('user_id', $user->id)
            ->where('is_active', true)
            ->count();
            
        $totalTargetAmount = Objective::where('user_id', $user->id)
            ->where('is_active', true)
            ->sum('target_amount');
            
        // Calculer le montant actuel collecté pour tous les objectifs actifs
        $totalCurrentAmount = 0;
        $objectives = Objective::where('user_id', $user->id)
            ->where('is_active', true)
            ->get();
            
        foreach ($objectives as $objective) {
            $latestProgress = $objective->progress()->latest()->first();
            if ($latestProgress) {
                $totalCurrentAmount += $latestProgress->current_amount;
            }
        }
        
        // Calculer la progression moyenne
        $averageProgress = $activeObjectivesCount > 0 ? 
            (Objective::where('user_id', $user->id)
                ->where('is_active', true)
                ->get()
                ->avg('current_progress')) : 0;
        
        // Générer des données pour les graphiques
        $progressByPeriod = [
            'daily' => Objective::where('user_id', $user->id)
                ->where('is_active', true)
                ->where('period_type', 'daily')
                ->get()
                ->avg('current_progress') ?? 0,
            'weekly' => Objective::where('user_id', $user->id)
                ->where('is_active', true)
                ->where('period_type', 'weekly')
                ->get()
                ->avg('current_progress') ?? 0,
            'monthly' => Objective::where('user_id', $user->id)
                ->where('is_active', true)
                ->where('period_type', 'monthly')
                ->get()
                ->avg('current_progress') ?? 0,
            'yearly' => Objective::where('user_id', $user->id)
                ->where('is_active', true)
                ->where('period_type', 'yearly')
                ->get()
                ->avg('current_progress') ?? 0
        ];
        
        $progressBySector = [
            'alimentation' => Objective::where('user_id', $user->id)
                ->where('is_active', true)
                ->where('sector', 'alimentation')
                ->get()
                ->avg('current_progress') ?? 0,
            'boulangerie' => Objective::where('user_id', $user->id)
                ->where('is_active', true)
                ->where('sector', 'boulangerie-patisserie')
                ->get()
                ->avg('current_progress') ?? 0,
            'glace' => Objective::where('user_id', $user->id)
                ->where('is_active', true)
                ->where('sector', 'glace')
                ->get()
                ->avg('current_progress') ?? 0,
            'global' => Objective::where('user_id', $user->id)
                ->where('is_active', true)
                ->where('sector', 'global')
                ->get()
                ->avg('current_progress') ?? 0
        ];
        
        return view('objectives.dashboard', compact(
            'alimentationObjectives',
            'boulangerieObjectives',
            'glaceObjectives',
            'globalObjectives',
            'completedObjectives',
            'activeObjectivesCount',
            'totalTargetAmount',
            'totalCurrentAmount',
            'averageProgress',
            'progressByPeriod',
            'progressBySector'
        ));
    }
    
    /**
     * Mettre à jour la progression d'un objectif en fonction des données actuelles.
     */
    public function updateObjectiveProgress(Objective $objective)
    {
        Log::info('Début de updateObjectiveProgress', [
            'objective_id' => $objective->id,
            'sector' => $objective->sector,
            'goal_type' => $objective->goal_type
        ]);

      $today = Carbon::today()->endOfDay();
        Log::info('Date du jour', ['today' => $today->format('Y-m-d H:i:s')]);

        // Start date : premier jour à 00h00
        $startDate = Carbon::parse($objective->start_date)->startOfDay();

        // End date : dernier jour à 23h59
        $endDate = Carbon::parse($objective->end_date)->endOfDay();
        Log::info('Dates de l\'objectif', [
            'start_date' => $startDate ? $startDate->format('Y-m-d') : 'Non définie',
            'end_date' => $endDate ? $endDate->format('Y-m-d') : 'Non définie'
        ]);
        
        
        // Récupérer la dernière mise à jour de progression
        $latestProgress = $objective->progress()->latest()->first();
        $lastUpdateDate = $latestProgress ? $latestProgress->date : null;
        
        Log::info('Dernière mise à jour de progression', [
            'latest_progress_exists' => (bool)$latestProgress,
            'last_update_date' => $lastUpdateDate ? $lastUpdateDate->format('Y-m-d') : 'Jamais mise à jour'
        ]);
        
        // Vérification de la mise à jour du jour
        /*if ($lastUpdateDate && $lastUpdateDate->isToday()) {
            Log::info('Mise à jour déjà effectuée aujourd\'hui');
            return;
        }*/
        
        // Initialisation des variables
        $currentAmount = 0;
        $expenses = 0;
        $profit = 0;
        $transactionIds = [];
        
        Log::info('Configuration des sources', [
            'use_standard_sources' => $objective->use_standard_sources,
            'custom_users' => $objective->custom_users ?? 'Aucun',
            'custom_categories' => $objective->custom_categories ?? 'Aucune',
            'expense_categories' => $objective->expense_categories ?? 'Aucune'
        ]);

        // Calcul des montants selon les sources
        if ($objective->use_standard_sources) {
            Log::info('Utilisation des sources standard');
            
            if ($objective->sector === 'alimentation') {
                Log::info('Calcul pour le secteur Alimentation');
                $versements = VersementChef::where('status', true)
                    ->whereBetween('date', [$startDate, $today])
                    ->join('users', 'Versement_chef.verseur', '=', 'users.id')
                    ->where('users.role', 'caissiere')
                    ->select('Versement_chef.*')
                    ->get();
                
                Log::info('Versements Alimentation', [
                    'nombre_versements' => $versements->count(),
                    'montant_total' => $versements->sum('montant')
                ]);
                    
                $currentAmount = $versements->sum('montant');
                $transactionIds = $versements->pluck('code_vc')->toArray();
            } elseif ($objective->sector === 'boulangerie-patisserie') {
                Log::info('Calcul pour le secteur Boulangerie-Patisserie');
                $versements = VersementChef::where('status', true)
                    ->whereBetween('date', [$startDate, $today])
                    ->join('users', 'Versement_chef.verseur', '=', 'users.id')
                    ->where(function($query) {
                        $query->where('users.role', 'chef_production')
                              ->orWhere('users.secteur', 'vente');
                    })
                    ->select('Versement_chef.*')
                    ->get();
                
                Log::info('Versements Boulangerie', [
                    'nombre_versements' => $versements->count(),
                    'montant_total' => $versements->sum('montant')
                ]);
                    
                $currentAmount = $versements->sum('montant');
                $transactionIds = $versements->pluck('code_vc')->toArray();
            } elseif ($objective->sector === 'glace') {
                Log::info('Calcul pour le secteur Glace');
                $versements = VersementChef::where('status', true)
                    ->whereBetween('date', [$startDate, $today])
                    ->join('users', 'Versement_chef.verseur', '=', 'users.id')
                    ->where('users.role', 'glace')
                    ->select('Versement_chef.*')
                    ->get();
                
                Log::info('Versements Glace', [
                    'nombre_versements' => $versements->count(),
                    'montant_total' => $versements->sum('montant')
                ]);
                    
                $currentAmount = $versements->sum('montant');
                $transactionIds = $versements->pluck('code_vc')->toArray();
            } elseif ($objective->sector === 'global') {
                Log::info('Calcul pour le secteur Global');
                $incomeTransactions = Transaction::where('type', 'income')
                    ->whereBetween('date', [$startDate, $today])
                    ->get();
                
                Log::info('Transactions Globales', [
                    'nombre_transactions' => $incomeTransactions->count(),
                    'montant_total' => $incomeTransactions->sum('amount')
                ]);
                    
                $currentAmount = $incomeTransactions->sum('amount');
                $transactionIds = $incomeTransactions->pluck('id')->toArray();
            }
        } else {
            Log::info('Utilisation des sources personnalisées');
            
            // Sources personnalisées - Utilisateurs
            if ($objective->custom_users) {
                Log::info('Recherche de versements pour utilisateurs personnalisés', [
                    'utilisateurs' => $objective->custom_users
                ]);
                
                $versements = VersementChef::where('status', true)
                    ->whereBetween('date', [$startDate, $today])
                    ->whereIn('verseur', $objective->custom_users)
                    ->get();
                
                Log::info('Versements Utilisateurs Personnalisés', [
                    'nombre_versements' => $versements->count(),
                    'montant_total' => $versements->sum('montant')
                ]);
                
                $currentAmount += $versements->sum('montant');
                $transactionIds = array_merge($transactionIds, $versements->pluck('code_vc')->toArray());
            }
            
            // Sources personnalisées - Catégories
            if ($objective->custom_categories) {
                Log::info('Recherche de transactions pour catégories personnalisées', [
                    'categories' => $objective->custom_categories
                ]);
                
                $incomeTransactions = Transaction::where('type', 'income')
                    ->whereBetween('date', [$startDate, $today])
                    ->whereIn('category_id', $objective->custom_categories)
                    ->get();
                
                Log::info('Transactions Catégories Personnalisées', [
                    'nombre_transactions' => $incomeTransactions->count(),
                    'montant_total' => $incomeTransactions->sum('amount')
                ]);
                
                $currentAmount += $incomeTransactions->sum('amount');
                $transactionIds = array_merge($transactionIds, $incomeTransactions->pluck('id')->toArray());
            }
        }
        
        // Calcul des dépenses
        Log::info('Calcul des dépenses');
        if ($objective->expense_categories) {
            Log::info('Dépenses par catégories spécifiques', [
                'categories' => $objective->expense_categories
            ]);
            
            $expenses = Transaction::where('type', 'outcome')
                ->whereBetween('date', [$startDate, $today])
                ->whereIn('category_id', $objective->expense_categories)
                ->sum('amount');
        } else {
            Log::info('Dépenses totales sans catégories spécifiques');
            $expenses = Transaction::where('type', 'outcome')
                ->whereBetween('date', [$startDate, $today])
                ->sum('amount');
        }
        
        Log::info('Résumé des dépenses', [
            'montant_depenses' => $expenses
        ]);
        
        // Calcul du profit
        $profit = $currentAmount - $expenses;
        Log::info('Calcul du profit', [
            'montant_recettes' => $currentAmount,
            'montant_depenses' => $expenses,
            'profit' => $profit
        ]);
        
        // Calcul du pourcentage de progression
        $progressPercentage = $objective->target_amount > 0 ? 
            min(100, ($objective->goal_type === 'revenue' ? $currentAmount : $profit) / $objective->target_amount * 100) : 0;
        
        Log::info('Calcul du pourcentage de progression', [
            'type_objectif' => $objective->goal_type,
            'montant_cible' => $objective->target_amount,
            'montant_actuel' => $objective->goal_type === 'revenue' ? $currentAmount : $profit,
            'pourcentage_progression' => $progressPercentage
        ]);
        
        // Création de l'enregistrement de progression
        $progressRecord = ObjectiveProgress::create([
            'objective_id' => $objective->id,
            'date' => $today,
            'current_amount' => $currentAmount,
            'expenses' => $expenses,
            'profit' => $profit,
            'progress_percentage' => $progressPercentage,
            'transactions' => $transactionIds
        ]);
        
        Log::info('Création de l\'enregistrement de progression', [
            'progress_record_id' => $progressRecord->id
        ]);
        
        // Vérification de l'atteinte de l'objectif
        $isAchieved = $progressPercentage >= 100;
        
        // Mise à jour du statut de l'objectif
        if ($isAchieved && !$objective->is_achieved) {
            Log::info('Objectif atteint - Mise à jour du statut');
            $objective->update(['is_achieved' => true]);
        }
        
        // Désactivation de l'objectif si la date de fin est passée
        if ($today->gt($endDate) && $objective->is_active) {
            Log::info('Date de fin dépassée - Désactivation de l\'objectif');
            $objective->update(['is_active' => false]);
        }
        
        // Mise à jour des sous-objectifs pour la boulangerie-patisserie
        if ($objective->sector === 'boulangerie-patisserie') {
            Log::info('Mise à jour des sous-objectifs pour Boulangerie-Patisserie');
            $this->updateSubObjectivesProgress($objective);
        }
        
        Log::info('Fin de updateObjectiveProgress', [
            'objective_id' => $objective->id,
            'statut_final' => [
                'progression' => $progressPercentage,
                'est_atteint' => $isAchieved,
                'est_actif' => $objective->is_active
            ]
        ]);
    }
    /**
     * Mettre à jour la progression des sous-objectifs pour un objectif boulangerie-patisserie.
     */
    private function updateSubObjectivesProgress(Objective $objective)
    {
        // Ne mettre à jour que pour les objectifs boulangerie-patisserie
        if ($objective->sector !== 'boulangerie-patisserie') {
            return;
        }
        
        // Récupérer tous les sous-objectifs
        $subObjectives = $objective->subObjectives;
        
        foreach ($subObjectives as $subObjective) {
            // Si pas de produit associé, sauter
            if (!$subObjective->product_id) {
                continue;
            }
            
            // Calculer le montant actuel pour ce produit à partir des transactions de vente
            $sales = TransactionVente::where('produit', $subObjective->product_id)
                ->whereBetween('date_vente', [
                    $objective->start_date,
                    Carbon::today() < $objective->end_date ? Carbon::today() : $objective->end_date
                ])
                ->where('type','vente')
                ->get();
            
            $currentAmount = $sales->sum(function($sale) {
                return $sale->quantite * $sale->prix;
            });
            
            // Calculer le pourcentage de progression
            $progressPercentage = $subObjective->target_amount > 0 ? 
                min(100, $currentAmount / $subObjective->target_amount * 100) : 0;
            
            // Mettre à jour le sous-objectif
            $subObjective->update([
                'current_amount' => $currentAmount,
                'progress_percentage' => $progressPercentage
            ]);
        }
    }
    
    /**
     * Store a new sub-objective for a bakery product.
     */
    public function storeSubObjective(Request $request, Objective $objective)
    {
        // S'assurer que l'utilisateur ne peut ajouter des sous-objectifs que pour ses propres objectifs non confirmés
        if ($objective->user_id !== Auth::id() || $objective->is_confirmed) {
            abort(403, 'Non autorisé');
        }
        
        // S'assurer que c'est bien un objectif boulangerie-patisserie
        if ($objective->sector !== 'boulangerie-patisserie') {
            return back()->with('error', 'Seuls les objectifs boulangerie-patisserie peuvent avoir des sous-objectifs.');
        }
        
        $validatedData = $request->validate([
            'product_id' => 'nullable|exists:Produit_fixes,code_produit',
            'title' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1'
        ]);
        
        // Vérifier si le montant total des sous-objectifs ne dépasse pas l'objectif principal
        $totalSubObjectives = $objective->subObjectives()->sum('target_amount') + $validatedData['target_amount'];
        if ($totalSubObjectives > $objective->target_amount) {
            return back()
                ->withInput()
                ->with('error', 'Le montant total des sous-objectifs dépasse l\'objectif principal.');
        }
        
        // Créer le sous-objectif
        $subObjective = new SubObjective([
            'product_id' => $validatedData['product_id'],
            'title' => $validatedData['title'],
            'target_amount' => $validatedData['target_amount'],
            'current_amount' => 0,
            'progress_percentage' => 0
        ]);
        
        $objective->subObjectives()->save($subObjective);
        
        // Mettre à jour immédiatement la progression
        $this->updateSubObjectivesProgress($objective);
        
        return redirect()->route('objectives.show', $objective->id)
            ->with('success', 'Sous-objectif ajouté avec succès.');
    }
    
    /**
     * Update a sub-objective for a bakery product.
     */
    public function updateSubObjective(Request $request, Objective $objective, SubObjective $subObjective)
    {
        // S'assurer que l'utilisateur ne peut modifier que ses propres sous-objectifs non confirmés
        if ($objective->user_id !== Auth::id() || $objective->is_confirmed) {
            abort(403, 'Non autorisé');
        }
        
        // S'assurer que le sous-objectif appartient bien à cet objectif
        if ($subObjective->objective_id !== $objective->id) {
            abort(404, 'Sous-objectif non trouvé pour cet objectif.');
        }
        
        $validatedData = $request->validate([
            'product_id' => 'nullable|exists:Produit_fixes,code_produit',
            'title' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1'
        ]);
        
        // Vérifier si le montant total des sous-objectifs ne dépasse pas l'objectif principal
        $totalSubObjectives = $objective->subObjectives()
            ->where('id', '!=', $subObjective->id)
            ->sum('target_amount') + $validatedData['target_amount'];
            
        if ($totalSubObjectives > $objective->target_amount) {
            return back()
                ->withInput()
                ->with('error', 'Le montant total des sous-objectifs dépasse l\'objectif principal.');
        }
        
        // Mettre à jour le sous-objectif
        $subObjective->update($validatedData);
        
        return redirect()->route('objectives.show', $objective->id)
            ->with('success', 'Sous-objectif mis à jour avec succès.');
    }
    
    /**
     * Remove a sub-objective.
     */
    public function destroySubObjective(Objective $objective, SubObjective $subObjective)
    {
        // S'assurer que l'utilisateur ne peut supprimer que ses propres sous-objectifs non confirmés
        if ($objective->user_id !== Auth::id() || $objective->is_confirmed) {
            abort(403, 'Non autorisé');
        }
        
        // S'assurer que le sous-objectif appartient bien à cet objectif
        if ($subObjective->objective_id !== $objective->id) {
            abort(404, 'Sous-objectif non trouvé pour cet objectif.');
        }
        
        $subObjective->delete();
        
        return redirect()->route('objectives.show', $objective->id)
            ->with('success', 'Sous-objectif supprimé avec succès.');
    }
}
