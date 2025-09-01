<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Objective;
use App\Models\SubObjective;
use App\Models\ObjectiveProgress;
use App\Models\RapportConfig;
use App\Models\User;
use App\Models\VersementChef;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PDF;

class RapportMensuelController extends Controller
{
    /**
     * Affiche la page d'accueil des rapports mensuels
     */
    public function index()
    {
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->startOfMonth()->subMonths($i);
            $months[$date->format('Y-m')] = $date->locale('fr')->isoFormat('MMMM YYYY');
        }

        return view('rapports.mensuel.index', compact('months'));
    }

    /**
     * Affiche la page de configuration des rapports
     */
    public function configure()
    {
        $config = RapportConfig::first() ?? new RapportConfig();
        $categories = Category::all();
        $users = User::all();

        // Classification des utilisateurs par rôle pour faciliter la sélection
        $usersByRole = $users->groupBy('role');

        return view('rapports.mensuel.configure', compact('config', 'categories', 'usersByRole'));
    }

    /**
     * Enregistre la configuration du rapport
     */
    public function saveConfig(Request $request)
    {
        $validated = $request->validate([
            'production_categories' => 'nullable|array',
            'alimentation_categories' => 'nullable|array',
            'production_users' => 'nullable|array',
            'alimentation_users' => 'nullable|array',
            'social_climat' => 'nullable|array',
            'social_climat.*.title' => 'required|string',
            'social_climat.*.description' => 'required|string',
            'major_problems' => 'nullable|array',
            'major_problems.*.title' => 'required|string',
            'major_problems.*.description' => 'required|string',
            'recommendations' => 'nullable|array',
            'recommendations.*.source' => 'required|string',
            'recommendations.*.content' => 'required|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $config = RapportConfig::first() ?? new RapportConfig();
        $config->fill($validated);
        $config->save();

        return redirect()->route('rapports.mensuel.configure')
            ->with('success', 'La configuration du rapport a été enregistrée avec succès.');
    }

    /**
     * Génère et affiche le rapport mensuel
     */
    public function show(Request $request)
    {
        // Récupérer le mois et l'année demandés
        $monthYear = $request->input('month_year', Carbon::now()->format('Y-m'));
        list($year, $month) = explode('-', $monthYear);
        
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        // Récupérer le mois précédent pour les comparaisons
        $previousMonth = $startDate->copy()->subMonth();
        $previousStartDate = $previousMonth->copy()->startOfMonth();
        $previousEndDate = $previousMonth->copy()->endOfMonth();
        
        // Récupérer la configuration du rapport
        $config = RapportConfig::first() ?? new RapportConfig();
        
        // 1. Chiffre d'affaires
        $chiffreAffaires = $this->getChiffreAffaires($startDate, $endDate);
        $previousChiffreAffaires = $this->getChiffreAffaires($previousStartDate, $previousEndDate);
        
        // 2. Dépenses totales
        $depensesTotales = $this->getDepensesTotales($startDate, $endDate);
        $previousDepensesTotales = $this->getDepensesTotales($previousStartDate, $previousEndDate);
        
        // 3. Bénéfice
        $benefice = $chiffreAffaires - $depensesTotales;
        $previousBenefice = $previousChiffreAffaires - $previousDepensesTotales;
        
        // 4. Évolution par rapport au mois précédent
        $evolutionChiffreAffaires = $this->calculateEvolution($chiffreAffaires, $previousChiffreAffaires);
        $evolutionDepenses = $this->calculateEvolution($depensesTotales, $previousDepensesTotales);
        $evolutionBenefice = $this->calculateEvolution($benefice, $previousBenefice);
        
        // 5. Objectifs
        $objectifs = $this->getObjectifs($startDate, $endDate);
        
        // 6. Répartition des dépenses par catégorie
        $depensesParCategories = $this->getDepensesParCategories($startDate, $endDate);
        
        // 7. Répartition des gains par secteur
        $gainsParSecteur = $this->getGainsParSecteur($startDate, $endDate, $config);
        
        // 8. Répartition des dépenses par secteur
        $depensesParSecteur = $this->getDepensesParSecteur($startDate, $endDate, $config);
        
        // 9. Données pour graphiques
        $graphiqueEvolutionCA = $this->getEvolutionMensuelleCA($year);
        $graphiqueEvolutionDepenses = $this->getEvolutionMensuelleDepenses($year);
        $graphiqueEvolutionBenefice = $this->getEvolutionMensuelleBenefice($year);
        
        // 10. Top produits vendus
        $topProduits = $this->getTopProduits($startDate, $endDate);
        
        // 11. Analyse IA
        $analyseIA = $this->getAnalyseIA([
            'chiffre_affaires' => $chiffreAffaires,
            'depenses_totales' => $depensesTotales,
            'benefice' => $benefice,
            'evolution_ca' => $evolutionChiffreAffaires,
            'evolution_depenses' => $evolutionDepenses,
            'evolution_benefice' => $evolutionBenefice,
            'objectifs' => $objectifs,
            'gains_secteurs' => $gainsParSecteur,
            'depenses_secteurs' => $depensesParSecteur,
            'top_produits' => $topProduits,
            'climat_social' => $config->social_climat ?? [],
            'problemes_majeurs' => $config->major_problems ?? []
        ]);

        // 12. Données sur les versements des caissières vs ventes réelles
        $analyseVersements = $this->analyseVersementsCaissieres($startDate, $endDate);
        
        return view('rapports.mensuel.show', compact(
            'monthYear',
            'startDate',
            'chiffreAffaires',
            'depensesTotales',
            'benefice',
            'evolutionChiffreAffaires',
            'evolutionDepenses',
            'evolutionBenefice',
            'objectifs',
            'depensesParCategories',
            'gainsParSecteur',
            'depensesParSecteur',
            'graphiqueEvolutionCA',
            'graphiqueEvolutionDepenses',
            'graphiqueEvolutionBenefice',
            'topProduits',
            'analyseIA',
            'analyseVersements',
            'config'
        ));
    }
    
    /**
     * Exporte le rapport mensuel en PDF
     */
    public function export(Request $request)
    {
        $monthYear = $request->input('month_year', Carbon::now()->format('Y-m'));
        list($year, $month) = explode('-', $monthYear);
        
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        // Récupérer les mêmes données que pour l'affichage
        $config = RapportConfig::first() ?? new RapportConfig();
        
        // Toutes les données nécessaires pour le PDF
        $chiffreAffaires = $this->getChiffreAffaires($startDate, $endDate);
        $depensesTotales = $this->getDepensesTotales($startDate, $endDate);
        $benefice = $chiffreAffaires - $depensesTotales;
        
        $previousMonth = $startDate->copy()->subMonth();
        $previousStartDate = $previousMonth->copy()->startOfMonth();
        $previousEndDate = $previousMonth->copy()->endOfMonth();
        $previousChiffreAffaires = $this->getChiffreAffaires($previousStartDate, $previousEndDate);
        $previousDepensesTotales = $this->getDepensesTotales($previousStartDate, $previousEndDate);
        $previousBenefice = $previousChiffreAffaires - $previousDepensesTotales;
        
        $evolutionChiffreAffaires = $this->calculateEvolution($chiffreAffaires, $previousChiffreAffaires);
        $evolutionDepenses = $this->calculateEvolution($depensesTotales, $previousDepensesTotales);
        $evolutionBenefice = $this->calculateEvolution($benefice, $previousBenefice);
        
        $objectifs = $this->getObjectifs($startDate, $endDate);
        $depensesParCategories = $this->getDepensesParCategories($startDate, $endDate);
        $gainsParSecteur = $this->getGainsParSecteur($startDate, $endDate, $config);
        $depensesParSecteur = $this->getDepensesParSecteur($startDate, $endDate, $config);
        $topProduits = $this->getTopProduits($startDate, $endDate);
        $analyseVersements = $this->analyseVersementsCaissieres($startDate, $endDate);
        
        // Générer le PDF
        $pdf = PDF::loadView('rapports.mensuel.pdf', compact(
            'monthYear',
            'startDate',
            'chiffreAffaires',
            'depensesTotales',
            'benefice',
            'evolutionChiffreAffaires',
            'evolutionDepenses',
            'evolutionBenefice',
            'objectifs',
            'depensesParCategories',
            'gainsParSecteur',
            'depensesParSecteur',
            'topProduits',
            'analyseVersements',
            'config'
        ));
        
        // Format A4, portrait
        $pdf->setPaper('a4', 'portrait');
        
        // Nom du fichier
        $filename = 'Rapport_Mensuel_' . $startDate->locale('fr')->isoFormat('MMMM_YYYY') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Récupère le chiffre d'affaires pour une période donnée
     */
    private function getChiffreAffaires(Carbon $startDate, Carbon $endDate)
    {
        return Transaction::where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
    }

    /**
     * Récupère les dépenses totales pour une période donnée
     */
    private function getDepensesTotales(Carbon $startDate, Carbon $endDate)
    {
        return Transaction::where('type', 'outcome')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
    }

    /**
     * Calcule l'évolution entre deux valeurs
     */
    private function calculateEvolution($current, $previous)
    {
        if ($previous <= 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return round((($current - $previous) / $previous) * 100, 2);
    }

    /**
     * Récupère les objectifs pour la période donnée
     */
    private function getObjectifs(Carbon $startDate, Carbon $endDate)
    {
        $objectives = Objective::with(['progress', 'subObjectives'])
            ->where('is_active', true)
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->get();
            
        $objectifsPourRapport = [];
        
        foreach ($objectives as $objective) {
            $progression = $objective->getCurrentProgressAttribute();
            $atteint = $progression >= 100;
            
            $details = [
                'id' => $objective->id,
                'titre' => $objective->title,
                'secteur' => $objective->sector,
                'montant_cible' => $objective->target_amount,
                'montant_actuel' => $objective->getCurrentAmountAttribute(),
                'progression' => $progression,
                'atteint' => $atteint,
                'type' => $objective->goal_type,
                'sous_objectifs' => [],
            ];
            
            foreach ($objective->subObjectives as $subObjective) {
                $details['sous_objectifs'][] = [
                    'titre' => $subObjective->title,
                    'montant_cible' => $subObjective->target_amount,
                    'montant_actuel' => $subObjective->current_amount,
                    'progression' => $subObjective->progress_percentage,
                ];
            }
            
            $objectifsPourRapport[] = $details;
        }
        
        return $objectifsPourRapport;
    }

    /**
     * Récupère la répartition des dépenses par catégorie
     */
    private function getDepensesParCategories(Carbon $startDate, Carbon $endDate)
    {
        return Transaction::where('type', 'outcome')
            ->whereBetween('date', [$startDate, $endDate])
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select('categories.name as category_name', DB::raw('SUM(transactions.amount) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Récupère la répartition des gains par secteur
     */
    private function getGainsParSecteur(Carbon $startDate, Carbon $endDate, RapportConfig $config)
    {
        $result = [
            'alimentation' => [
                'montant' => 0,
                'pourcentage' => 0,
                'details' => []
            ],
            'production' => [
                'montant' => 0,
                'pourcentage' => 0,
                'details' => []
            ],
            'glace' => [
                'montant' => 0,
                'pourcentage' => 0,
                'details' => []
            ],
            'autre' => [
                'montant' => 0,
                'pourcentage' => 0,
                'details' => []
            ],
        ];
        
        // Gains pour le secteur alimentation
        if (!empty($config->alimentation_users)) {
            $gainAlimentation = VersementChef::where('status', true)
                ->whereBetween('date', [$startDate, $endDate])
                ->whereIn('verseur', $config->alimentation_users)
                ->sum('montant');
            
            $result['alimentation']['montant'] = $gainAlimentation;
            
            // Détails par utilisateur
            $detailsAlimentation = VersementChef::where('status', true)
                ->whereBetween('date', [$startDate, $endDate])
                ->whereIn('verseur', $config->alimentation_users)
                ->join('users', 'Versement_chef.verseur', '=', 'users.id')
                ->select('users.name', DB::raw('SUM(montant) as total'))
                ->groupBy('users.id', 'users.name')
                ->orderByDesc('total')
                ->get();
                
            $result['alimentation']['details'] = $detailsAlimentation;

            
        } else {
            // Utiliser la configuration standard (caissières)
            $gainAlimentation = VersementChef::where('status', true)
                ->whereBetween('date', [$startDate, $endDate])
                ->join('users', 'Versement_chef.verseur', '=', 'users.id')
                ->where('users.role', 'caissiere')
                ->sum('montant');
                
            $result['alimentation']['montant'] = $gainAlimentation;
            
            // Détails par utilisateur
            $detailsAlimentation = VersementChef::where('status', true)
                ->whereBetween('date', [$startDate, $endDate])
                ->join('users', 'Versement_chef.verseur', '=', 'users.id')
                ->where('users.role', 'caissiere')
                ->select('users.name', DB::raw('SUM(montant) as total'))
                ->groupBy('users.id', 'users.name')
                ->orderByDesc('total')
                ->get();
                
            $result['alimentation']['details'] = $detailsAlimentation;
        }
        
        // Gains pour le secteur production
        if (!empty($config->production_users)) {
            $gainProduction = VersementChef::where('status', true)
                ->whereBetween('date', [$startDate, $endDate])
                ->whereIn('verseur', $config->production_users)
                ->sum('montant');
                
            $result['production']['montant'] = $gainProduction;
            
            // Détails par utilisateur
            $detailsProduction = VersementChef::where('status', true)
                ->whereBetween('date', [$startDate, $endDate])
                ->whereIn('verseur', $config->production_users)
                ->join('users', 'Versement_chef.verseur', '=', 'users.id')
                ->select('users.name', DB::raw('SUM(montant) as total'))
                ->groupBy('users.id', 'users.name')
                ->orderByDesc('total')
                ->get();
                
            $result['production']['details'] = $detailsProduction;
        } else {
            // Utiliser la configuration standard (chef production et vendeurs)
            $gainProduction = VersementChef::where('status', true)
                ->whereBetween('date', [$startDate, $endDate])
                ->join('users', 'Versement_chef.verseur', '=', 'users.id')
                ->where(function($query) {
                    $query->where('users.role', 'chef_production')
                          ->orWhere('users.secteur', 'vente');
                })
                ->sum('montant');
                
            $result['production']['montant'] = $gainProduction;
            
            // Détails par utilisateur
            $detailsProduction = VersementChef::where('status', true)
                ->whereBetween('date', [$startDate, $endDate])
                ->join('users', 'Versement_chef.verseur', '=', 'users.id')
                ->where(function($query) {
                    $query->where('users.role', 'chef_production')
                          ->orWhere('users.secteur', 'vente');
                })
                ->select('users.name', DB::raw('SUM(montant) as total'))
                ->groupBy('users.id', 'users.name')
                ->orderByDesc('total')
                ->get();
                
            $result['production']['details'] = $detailsProduction;
        }
        
        // Gains pour le secteur glace (toujours les employés avec rôle 'glace')
        $gainGlace = VersementChef::where('status', true)
            ->whereBetween('date', [$startDate, $endDate])
            ->join('users', 'Versement_chef.verseur', '=', 'users.id')
            ->where('users.role', 'glace')
            ->sum('montant');
            
        $result['glace']['montant'] = $gainGlace;
        // Détails par utilisateur
        $detailsGlace = VersementChef::where('status', true)
            ->whereBetween('date', [$startDate, $endDate])
            ->join('users', 'Versement_chef.verseur', '=', 'users.id')
            ->where('users.role', 'glace')
            ->select('users.name', DB::raw('SUM(montant) as total'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total')
            ->get();
            
        $result['glace']['details'] = $detailsGlace;
        
        // Autres gains (non catégorisés)
        $gainTotal = Transaction::where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
            
        $gainCategorise = $gainAlimentation + $gainProduction + $gainGlace;
        $gainAutre = max(0, $gainTotal - $gainCategorise);
        
        $result['autre']['montant'] = $gainAutre;
        
        // Calculer les pourcentages
        $totalGains = array_sum(array_column($result, 'montant'));
        if ($totalGains > 0) {
            foreach ($result as $key => $value) {
                $result[$key]['pourcentage'] = round(($value['montant'] / $totalGains) * 100, 2);
            }
        }
        
        return $result;
    }

    /**
     * Récupère la répartition des dépenses par secteur
     */
    private function getDepensesParSecteur(Carbon $startDate, Carbon $endDate, RapportConfig $config)
    {
        $result = [
            'alimentation' => [
                'montant' => 0,
                'pourcentage' => 0,
                'details' => []
            ],
            'production' => [
                'montant' => 0,
                'pourcentage' => 0,
                'details' => []
            ],
            'commun' => [
                'montant' => 0,
                'pourcentage' => 0,
                'details' => []
            ],
        ];
        
        // Dépenses pour le secteur alimentation
        if (!empty($config->alimentation_categories)) {
            $result['alimentation']['montant'] = Transaction::where('type', 'outcome')
                ->whereBetween('date', [$startDate, $endDate])
                ->whereIn('category_id', $config->alimentation_categories)
                ->sum('amount');
                
            $result['alimentation']['details'] = Transaction::where('type', 'outcome')
                ->whereBetween('date', [$startDate, $endDate])
                ->whereIn('category_id', $config->alimentation_categories)
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->select('categories.name', DB::raw('SUM(transactions.amount) as total'))
                ->groupBy('categories.id', 'categories.name')
                ->orderByDesc('total')
                ->get();
        }
        
        // Dépenses pour le secteur production
        if (!empty($config->production_categories)) {
            $result['production']['montant'] = Transaction::where('type', 'outcome')
                ->whereBetween('date', [$startDate, $endDate])
                ->whereIn('category_id', $config->production_categories)
                ->sum('amount');
                
            $result['production']['details'] = Transaction::where('type', 'outcome')
                ->whereBetween('date', [$startDate, $endDate])
                ->whereIn('category_id', $config->production_categories)
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->select('categories.name', DB::raw('SUM(transactions.amount) as total'))
                ->groupBy('categories.id', 'categories.name')
                ->orderByDesc('total')
                ->get();
        }
        
        // Dépenses communes (non attribuées à un secteur spécifique)
        $categoriesSecteurs = array_merge(
            $config->alimentation_categories ?? [],
            $config->production_categories ?? []
        );
        
        if (!empty($categoriesSecteurs)) {
            $result['commun']['montant'] = Transaction::where('type', 'outcome')
                ->whereBetween('date', [$startDate, $endDate])
                ->whereNotIn('category_id', $categoriesSecteurs)
                ->sum('amount');
                
            $result['commun']['details'] = Transaction::where('type', 'outcome')
                ->whereBetween('date', [$startDate, $endDate])
                ->whereNotIn('category_id', $categoriesSecteurs)
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->select('categories.name', DB::raw('SUM(transactions.amount) as total'))
                ->groupBy('categories.id', 'categories.name')
                ->orderByDesc('total')
                ->get();
        } else {
            // Si aucune catégorie n'est spécifiée, toutes les dépenses sont "communes"
            $result['commun']['montant'] = Transaction::where('type', 'outcome')
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');
                
            $result['commun']['details'] = Transaction::where('type', 'outcome')
                ->whereBetween('date', [$startDate, $endDate])
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->select('categories.name', DB::raw('SUM(transactions.amount) as total'))
                ->groupBy('categories.id', 'categories.name')
                ->orderByDesc('total')
                ->get();
        }
        
        // Calculer les pourcentages
        $totalDepenses = array_sum(array_column($result, 'montant'));
        if ($totalDepenses > 0) {
            foreach ($result as $key => $value) {
                $result[$key]['pourcentage'] = round(($value['montant'] / $totalDepenses) * 100, 2);
            }
        }
        
        return $result;
    }

    /**
     * Récupère les données d'évolution mensuelle du CA pour l'année en cours
     */
    private function getEvolutionMensuelleCA($year)
    {
        $result = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
            if ($endDate->isFuture()) {
                break;
            }
            
            $ca = $this->getChiffreAffaires($startDate, $endDate);
            
            $result[] = [
                'month' => $startDate->locale('fr')->isoFormat('MMM'),
                'amount' => $ca
            ];
        }
        
        return $result;
    }

    /**
     * Récupère les données d'évolution mensuelle des dépenses pour l'année en cours
     */
    private function getEvolutionMensuelleDepenses($year)
    {
        $result = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
            if ($endDate->isFuture()) {
                break;
            }
            
            $depenses = $this->getDepensesTotales($startDate, $endDate);
            
            $result[] = [
                'month' => $startDate->locale('fr')->isoFormat('MMM'),
                'amount' => $depenses
            ];
        }
        
        return $result;
    }

    /**
     * Récupère les données d'évolution mensuelle du bénéfice pour l'année en cours
     */
    private function getEvolutionMensuelleBenefice($year)
    {
        $result = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
            if ($endDate->isFuture()) {
                break;
            }
            
            $ca = $this->getChiffreAffaires($startDate, $endDate);
            $depenses = $this->getDepensesTotales($startDate, $endDate);
            $benefice = $ca - $depenses;
            
            $result[] = [
                'month' => $startDate->locale('fr')->isoFormat('MMM'),
                'amount' => $benefice
            ];
        }
        
        return $result;
    }

    /**
     * Récupère le top 10 des produits les plus vendus
     */
    private function getTopProduits($startDate, $endDate)
    {
        return DB::table('transaction_ventes')
            ->join('Produit_fixes', 'transaction_ventes.produit', '=', 'Produit_fixes.code_produit')
            ->whereBetween('date_vente', [$startDate, $endDate])
            ->select(
                'Produit_fixes.nom',
                DB::raw('SUM(transaction_ventes.quantite) as quantite_totale'),
                DB::raw('SUM(transaction_ventes.quantite * transaction_ventes.prix) as montant_total')
            )
            ->groupBy('transaction_ventes.produit', 'Produit_fixes.nom')
            ->orderByDesc('quantite_totale')
            ->limit(10)
            ->get();
    }

    /**
     * Analyse la différence entre les versements des caissières et les ventes réelles
     */
    private function analyseVersementsCaissieres($startDate, $endDate)
    {
        // Montant total des versements des caissières
        $totalVersements = VersementChef::where('status', true)
            ->whereBetween('date', [$startDate, $endDate])
            ->join('users', 'Versement_chef.verseur', '=', 'users.id')
            ->where('users.role', 'caissiere')
            ->sum('montant');
            
        // Montant total des ventes enregistrées
        $totalVentesEnregistrees = DB::table('transaction_ventes')
            ->whereBetween('date_vente', [$startDate, $endDate])
            ->sum(DB::raw('quantite * prix'));
            
        // Différence
        $difference = $totalVentesEnregistrees - $totalVersements;
        
        // Détails par caissière
        $detailsParCaissiere = VersementChef::where('status', true)
            ->whereBetween('date', [$startDate, $endDate])
            ->join('users', 'Versement_chef.verseur', '=', 'users.id')
            ->where('users.role', 'caissiere')
            ->select(
                'users.id',
                'users.name',
                DB::raw('SUM(montant) as total_versements')
            )
            ->groupBy('users.id', 'users.name')
            ->get();
            
        foreach ($detailsParCaissiere as $detail) {
            // Calculer les ventes associées à cette caissière (si c'est possible)
            $ventesAssociees = DB::table('transaction_ventes')
                ->where('serveur', $detail->id)
                ->whereBetween('date_vente', [$startDate, $endDate])
                ->sum(DB::raw('quantite * prix'));
                
            $detail->total_ventes = $ventesAssociees;
            $detail->difference = $ventesAssociees - $detail->total_versements;
            $detail->pourcentage_manquant = $ventesAssociees > 0 ? 
                round(($detail->difference / $ventesAssociees) * 100, 2) : 0;
        }
            
        return [
            'total_versements' => $totalVersements,
            'total_ventes' => $totalVentesEnregistrees,
            'difference' => $difference,
            'pourcentage_difference' => $totalVentesEnregistrees > 0 ? 
                round(($difference / $totalVentesEnregistrees) * 100, 2) : 0,
            'details_par_caissiere' => $detailsParCaissiere
        ];
    }

    /**
     * Génère une analyse des données avec l'IA
     */
    private function getAnalyseIA($data)
    {
        try {
            $apiKey = config('services.openai.api_key');
            
            if (empty($apiKey)) {
                Log::warning('OpenAI API key not configured. Returning empty analysis.');
                return [
                    'summary' => 'L\'analyse par IA n\'est pas disponible (clé API non configurée).',
                    'secteurs' => [],
                    'objectifs' => [],
                    'recommendations' => []
                ];
            }
            
            $userLanguage = auth()->user()->language;

            if ($userLanguage == 'fr') {
                $langue = 'française';
            } else {
                $langue = 'anglaise';
            }
            
            $prompt = "RÔLE : Expert en analyse financière spécialisé dans l'agroalimentaire camerounais

CONTEXTE : Entreprise multi-secteurs (boulangerie-pâtisserie, glaces, alimentation générale) au Cameroun, devise FCFA.

RÈGLES D'ANALYSE CRITIQUES :
- TOUJOURS utiliser les données de 'gains_secteurs' pour l'analyse sectorielle (données complètes)
- IGNORER les valeurs CA/bénéfices des objectifs pour l'analyse par secteur (données partielles)
- Les données que tu reçois pour l'évolution sont en pourcentage

TERMINOLOGIE OBLIGATOIRE :
- Secteur alimentation = \"General Store\" (en anglais dans le rapport)

STRUCTURE OBLIGATOIRE DU RAPPORT :
Le rapport DOIT suivre EXACTEMENT cette structure avec ces titres spécifiques selon la langue :

=== EN FRANÇAIS ===
# 1. DIAGNOSTIC GLOBAL
# 2. ANALYSE SECTORIELLE
## Secteur Alimentation (General Store)
## Secteur Production (Boulangerie-Pâtisserie)  
## Secteur Glaces
# 3. BILAN DES OBJECTIFS
# 4. RECOMMANDATIONS STRATÉGIQUES

=== EN ANGLAIS ===
# 1. GLOBAL DIAGNOSTIC
# 2. SECTORIAL ANALYSIS
## General Store (Alimentation)
## Production (Boulangerie-Pâtisserie)
## Ice Cream (Glaces)
# 3. OBJECTIVES BALANCE
# 4. STRATEGIC RECOMMENDATIONS

CONTRAINTES DE CONTENU :
1. DIAGNOSTIC GLOBAL/GLOBAL DIAGNOSTIC (200 mots MAX)
   - Santé financière générale
   - Tendances clés et signaux d'alerte
   - Évolution globale des performances

2. ANALYSE SECTORIELLE/SECTORIAL ANALYSIS (150 mots MAX par secteur)
   - Performance financière (CA, marge, rentabilité)
   - Évolution vs mois précédent
   - Contribution au chiffre d'affaires total
   - Points d'attention spécifiques

3. BILAN DES OBJECTIFS/OBJECTIVES BALANCE (200 mots MAX)
   - Objectifs atteints vs non atteints
   - Écarts de performance et causes identifiées  
   - Impact sur la stratégie d'entreprise

4. RECOMMANDATIONS STRATÉGIQUES/STRATEGIC RECOMMENDATIONS
   - EXACTEMENT 5 actions concrètes et chiffrées
   - Format numéroté : 1. Action... 2. Action... etc.
   - Pour chaque recommandation inclure :
     * Investissement/budget requis
     * Délai d'implémentation
     * ROI estimé ou impact attendu

RÈGLES DE FORMATAGE CRITIQUES :
- Utiliser les titres EXACTS indiqués ci-dessus (avec # pour les sections principales)
- Numéroter les sections principales (1., 2., 3., 4.)
- Utiliser ## pour les sous-sections sectorielles
- Numéroter les recommandations (1., 2., 3., 4., 5.)
- Langue du rapport : {$langue}
- Respecter les limites de mots par section


IMPÉRATIF : Respecter STRICTEMENT cette structure pour garantir un parsing correct des données
DONNÉES À ANALYSER :" . json_encode($data, JSON_PRETTY_PRINT);
            // Appeler l'API OpenAI
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'Tu es un expert en analyse financière d\'entreprise qui fournit des conseils clairs et exploitables.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.5,
                'max_tokens' => 1500,
            ]);
            
            if ($response->successful()) {
                $result = $response->json()['choices'][0]['message']['content'] ?? '';
                // Analyser la réponse pour la structurer
                $analysis = $this->parseAIResponse($result);
                return $analysis;
            } else {
                Log::error('OpenAI API error: ' . $response->body());
                return [
                    'summary' => 'Une erreur est survenue lors de l\'analyse par IA.',
                    'secteurs' => [],
                    'objectifs' => [],
                    'recommendations' => []
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error in AI analysis: ' . $e->getMessage());
            return [
                'summary' => 'Une erreur est survenue lors de l\'analyse par IA: ' . $e->getMessage(),
                'secteurs' => [],
                'objectifs' => [],
                'recommendations' => []
            ];
        }
    }

    /**
     * Parse la réponse de l'IA pour la structurer
     */
    private function parseAIResponse($response)
{
    Log::info("reponse reçu : " . $response);
    $sections = [
        'summary' => '',
        'secteurs' => [
            'alimentation' => '',
            'production' => '',
            'glaces' => ''
        ],
        'objectifs' => '',
        'recommendations' => []
    ];
    
    // Patterns multilingues pour les sections
    $sectionPatterns = [
        // Français
        'summary_fr' => ['analyse globale', 'diagnostic global', 'global diagnostic', 'santé financière', 'résumé exécutif'],
        'sectorial_fr' => ['analyse des secteurs', 'analyse sectori', 'sectorial analysis'],
        'alimentation_fr' => ['general store', 'alimentation', 'secteur alimentation', 'magasin général'],
        'production_fr' => ['production', 'secteur production', 'boulangerie', 'pâtisserie', 'boulangerie-pâtisserie'],
        'glaces_fr' => ['glaces', 'secteur glaces', 'ice cream'],
        'objectifs_fr' => ['analyse des objectifs', 'objectifs', 'objectives balance', 'bilan des objectifs'],
        'recommendations_fr' => ['recommandations', 'strategic recommendations', 'recommandations stratégiques'],
        
        // Anglais
        'summary_en' => ['global diagnostic', 'executive summary', 'overall analysis', 'financial health'],
        'sectorial_en' => ['sectorial analysis', 'sector analysis', 'sectoral analysis'],
        'alimentation_en' => ['general store', 'food sector', 'grocery sector', 'alimentation'],
        'production_en' => ['production', 'bakery', 'boulangerie', 'pâtisserie', 'boulangerie-pâtisserie'],
        'glaces_en' => ['ice cream', 'glaces', 'frozen products'],
        'objectifs_en' => ['objectives balance', 'targets analysis', 'goals analysis', 'objectives analysis'],
        'recommendations_en' => ['strategic recommendations', 'recommendations', 'action plan']
    ];
    
    // Expression régulière étendue pour capturer les titres en français et anglais
    $pattern = '/(?:#{1,3}|[0-9]+\.)\s*(GLOBAL DIAGNOSTIC|Analyse globale|Diagnostic global|Santé financière|Executive Summary|' .
               'SECTORIAL ANALYSIS|Analyse des secteurs|Sectorial Analysis|' .
               'General Store|Secteur alimentation|Alimentation|Food Sector|' .
               'Production|Secteur production|Boulangerie|Pâtisserie|Boulangerie-Pâtisserie|Bakery|' .
               'Ice Cream|Secteur glaces|Glaces|Frozen Products|' .
               'OBJECTIVES BALANCE|Analyse des objectifs|Objectifs|Objectives Analysis|Targets Analysis|' .
               'STRATEGIC RECOMMENDATIONS|Recommandations|Strategic Recommendations|Action Plan)[^\n]*/i';
    
    preg_match_all($pattern, $response, $matches, PREG_OFFSET_CAPTURE);
    
    $sectionStarts = $matches[0];
    $sectionsData = [];
    
    // Si aucune section n'est trouvée, utiliser tout le texte comme résumé
    if (empty($sectionStarts)) {
        $sections['summary'] = trim($response);
        return $sections;
    }
    
    // Extraire le contenu de chaque section
    for ($i = 0; $i < count($sectionStarts); $i++) {
        $title = strtolower(trim(preg_replace('/(?:#{1,3}|[0-9]+\.)\s*/i', '', $sectionStarts[$i][0])));
        $start = $sectionStarts[$i][1] + strlen($sectionStarts[$i][0]);
        $end = ($i < count($sectionStarts) - 1) ? $sectionStarts[$i + 1][1] : strlen($response);
        
        $content = trim(substr($response, $start, $end - $start));
        $sectionsData[$title] = $content;
    }
    
    // Fonction helper pour vérifier si un titre correspond à une catégorie
    $matchesCategory = function($title, $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($title, strtolower($keyword)) !== false) {
                return true;
            }
        }
        return false;
    };
    
    // Remplir les sections structurées avec support multilingue
    foreach ($sectionsData as $title => $content) {
        // Summary / Analyse globale
        if ($matchesCategory($title, array_merge($sectionPatterns['summary_fr'], $sectionPatterns['summary_en']))) {
            $sections['summary'] = $content;
        }
        // Alimentation / General Store
        else if ($matchesCategory($title, array_merge($sectionPatterns['alimentation_fr'], $sectionPatterns['alimentation_en']))) {
            $sections['secteurs']['alimentation'] = $content;
        }
        // Production / Bakery
        else if ($matchesCategory($title, array_merge($sectionPatterns['production_fr'], $sectionPatterns['production_en']))) {
            $sections['secteurs']['production'] = $content;
        }
        // Glaces / Ice Cream
        else if ($matchesCategory($title, array_merge($sectionPatterns['glaces_fr'], $sectionPatterns['glaces_en']))) {
            $sections['secteurs']['glaces'] = $content;
        }
        // Objectifs / Objectives
        else if ($matchesCategory($title, array_merge($sectionPatterns['objectifs_fr'], $sectionPatterns['objectifs_en']))) {
            $sections['objectifs'] = $content;
        }
        // Recommandations / Recommendations
        else if ($matchesCategory($title, array_merge($sectionPatterns['recommendations_fr'], $sectionPatterns['recommendations_en']))) {
            // Extraire les recommandations individuelles avec patterns multilingues
            $recommendations = preg_split('/[0-9]+\.\s*/', $content, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($recommendations as $rec) {
                $rec = trim($rec);
                if ($rec && !empty($rec)) {
                    // Nettoyer les recommandations des caractères indésirables
                    $rec = preg_replace('/^\*+\s*/', '', $rec); // Enlever les astérisques en début
                    $rec = preg_replace('/^-+\s*/', '', $rec);  // Enlever les tirets en début
                    if (strlen($rec) > 10) { // Éviter les recommandations trop courtes
                        $sections['recommendations'][] = $rec;
                    }
                }
            }
        }
    }
    
    // Si aucune recommandation n'a été trouvée par le pattern principal, essayer une approche alternative
    if (empty($sections['recommendations'])) {
        $recSection = '';
        foreach ($sectionsData as $title => $content) {
            if ($matchesCategory($title, array_merge($sectionPatterns['recommendations_fr'], $sectionPatterns['recommendations_en']))) {
                $recSection = $content;
                break;
            }
        }
        
        if ($recSection) {
            $lines = explode("\n", $recSection);
            foreach ($lines as $line) {
                $line = trim($line);
                // Patterns pour différents formats de listes
                if (preg_match('/^[0-9]+\.\s*(.+)$/', $line, $m)) {
                    $sections['recommendations'][] = trim($m[1]);
                } elseif (preg_match('/^[\-\*•]\s*(.+)$/', $line, $m)) {
                    $sections['recommendations'][] = trim($m[1]);
                } elseif (preg_match('/^\*\*(.+?)\*\*:?\s*(.*)$/', $line, $m)) {
                    // Format avec texte en gras (markdown)
                    $sections['recommendations'][] = trim($m[1] . ': ' . $m[2]);
                } elseif (!empty($line) && 
                         count($sections['recommendations']) < 10 && 
                         strlen($line) > 15 && 
                         !preg_match('/^#{1,6}\s/', $line)) {
                    // Ligne non vide qui pourrait être une recommendation
                    $sections['recommendations'][] = $line;
                }
            }
        }
    }
    
    // Nettoyer les sections vides et les données de debug
    foreach ($sections['secteurs'] as $key => $value) {
        if (empty(trim($value))) {
            $sections['secteurs'][$key] = '';
        }
    }
    
    // Limiter le nombre de recommandations à 10 maximum
    if (count($sections['recommendations']) > 10) {
        $sections['recommendations'] = array_slice($sections['recommendations'], 0, 10);
    }
    
    Log::info("sections parsées : " . json_encode($sections, JSON_PRETTY_PRINT));
    return $sections;
}
}
