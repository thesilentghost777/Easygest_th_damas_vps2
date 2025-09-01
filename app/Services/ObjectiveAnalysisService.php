<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Objective;
use App\Models\ObjectiveProgress;
use App\Models\SubObjective;
use App\Models\Utilisation;
use App\Models\TransactionVente;
use Carbon\Carbon;

class ObjectiveAnalysisService
{
    /**
     * Collect objective analysis data for AI analysis
     */
    public function collectObjectiveData($month, $year)
    {
        Log::info('Collecting objective analysis data', [
            'month' => $month,
            'year' => $year
        ]);
        
        try {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
            // Obtenir les objectifs du mois concernant la production
            $objectives = Objective::with(['user', 'progress', 'subObjectives', 'subObjectives.product'])
                ->where('sector', 'boulangerie-patisserie')
                ->where(function($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function($q) use ($startDate, $endDate) {
                            $q->where('start_date', '<=', $startDate)
                              ->where('end_date', '>=', $endDate);
                        });
                })
                ->get();
                
            // Pour chaque objectif, analyser les raisons d'échec ou de réussite
            $objectivesAnalysis = $objectives->map(function($objective) use ($startDate, $endDate) {
                // Calculer le statut de l'objectif
                $statut = 'en_cours';
                if ($objective->end_date < now()) {
                    $statut = $objective->is_achieved ? 'atteint' : 'non_atteint';
                }
                
                // Production et ventes réelles pour la période
                $production = $this->getProductionForPeriod($startDate, $endDate);
                $ventes = $this->getVentesForPeriod($startDate, $endDate);
                
                // Analyse des raisons potentielles d'échec ou de réussite
                $raisonsReussiteEchec = $this->analyzeObjectiveSuccess($objective, $production, $ventes);
                
                return [
                    'objectif' => [
                        'id' => $objective->id,
                        'titre' => $objective->title,
                        'description' => $objective->description,
                        'montant_cible' => $objective->target_amount,
                        'montant_actuel' => $objective->getCurrentAmountAttribute(),
                        'progression' => $objective->getCurrentProgressAttribute(),
                        'date_debut' => $objective->start_date->format('Y-m-d'),
                        'date_fin' => $objective->end_date->format('Y-m-d'),
                        'statut' => $statut,
                        'createur' => $objective->user ? $objective->user->name : 'Inconnu',
                    ],
                    'sous_objectifs' => $objective->subObjectives->map(function($subObj) {
                        return [
                            'id' => $subObj->id,
                            'titre' => $subObj->title,
                            'produit' => $subObj->product ? $subObj->product->nom : 'N/A',
                            'montant_cible' => $subObj->target_amount,
                            'montant_actuel' => $subObj->current_amount,
                            'progression' => $subObj->progress_percentage,
                        ];
                    }),
                    'analyse' => $raisonsReussiteEchec
                ];
            });
            
            $data = [
                'total_objectives' => $objectives->count(),
                'achieved_objectives' => $objectives->where('is_achieved', true)->count(),
                'objectives_analysis' => $objectivesAnalysis
            ];
            
            Log::info('Objective analysis data collected successfully', [
                'data_size' => strlen(json_encode($data))
            ]);
            
            return $data;
        } catch (\Exception $e) {
            Log::error('Error collecting objective analysis data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Erreur lors de la collecte des données d\'analyse des objectifs: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get production data for a period
     */
    private function getProductionForPeriod($startDate, $endDate)
    {
        // Obtenir les productions par produit et producteur
        return Utilisation::join('Produit_fixes', 'Utilisation.produit', '=', 'Produit_fixes.code_produit')
            ->join('users', 'Utilisation.producteur', '=', 'users.id')
            ->whereBetween('Utilisation.created_at', [$startDate, $endDate])
            ->select(
                'Utilisation.id_lot',
                'Utilisation.produit',
                'Produit_fixes.nom as produit_nom',
                'users.id as producteur_id',
                'users.name as producteur_nom',
                DB::raw('SUM(Utilisation.quantite_produit) as quantite_produite')
            )
            ->groupBy('Utilisation.id_lot', 'Utilisation.produit', 'Produit_fixes.nom', 'users.id', 'users.name')
            ->get();
    }
    
    /**
     * Get sales data for a period
     */
    private function getVentesForPeriod($startDate, $endDate)
    {
        // Obtenir les ventes par produit
        return TransactionVente::join('Produit_fixes', 'transaction_ventes.produit', '=', 'Produit_fixes.code_produit')
            ->whereBetween('date_vente', [$startDate, $endDate])
            ->select(
                'transaction_ventes.produit',
                'Produit_fixes.nom as produit_nom',
                DB::raw('SUM(transaction_ventes.quantite) as quantite_vendue'),
                DB::raw('SUM(transaction_ventes.quantite * transaction_ventes.prix) as chiffre_affaires')
            )
            ->groupBy('transaction_ventes.produit', 'Produit_fixes.nom')
            ->get();
    }
    
    /**
     * Analyze reasons for objective success or failure
     */
    private function analyzeObjectiveSuccess($objective, $production, $ventes)
    {
        $raisons = [];
        
        // Vérifier si l'objectif est atteint ou non
        $isAchieved = $objective->is_achieved;
        $progression = $objective->getCurrentProgressAttribute();
        
        // Raisons générales
        if ($isAchieved) {
            $raisons['general'] = "L'objectif a été atteint à {$progression}%.";
        } else {
            $raisons['general'] = "L'objectif n'a été atteint qu'à {$progression}%.";
        }
        
        // Vérifier les sous-objectifs
        if ($objective->subObjectives->count() > 0) {
            $achievedSubObjectives = $objective->subObjectives->where('progress_percentage', '>=', 100)->count();
            $totalSubObjectives = $objective->subObjectives->count();
            $raisons['sous_objectifs'] = "Sur {$totalSubObjectives} sous-objectifs, {$achievedSubObjectives} ont été atteints.";
            
            // Lister les sous-objectifs problématiques
            $problematicSubObjectives = $objective->subObjectives
                ->where('progress_percentage', '<', 80)
                ->values();
                
            if ($problematicSubObjectives->count() > 0) {
                $raisons['sous_objectifs_problematiques'] = $problematicSubObjectives->map(function($so) use ($ventes) {
                    $venteProduit = $ventes->where('produit', $so->product_id)->first();
                    
                    return [
                        'titre' => $so->title,
                        'produit' => $so->product ? $so->product->nom : 'N/A',
                        'progression' => $so->progress_percentage,
                        'montant_actuel' => $so->current_amount,
                        'montant_cible' => $so->target_amount,
                        'ventes_reelles' => $venteProduit ? [
                            'quantite' => $venteProduit->quantite_vendue,
                            'chiffre_affaires' => $venteProduit->chiffre_affaires
                        ] : null
                    ];
                });
            }
        }
        
        // Analyser la production vs ventes
        $productionTotal = $production->sum('quantite_produite');
        $ventesTotal = $ventes->sum('quantite_vendue');
        
        if ($productionTotal > 0 && $ventesTotal > 0) {
            $ratio = $ventesTotal / $productionTotal * 100;
            $raisons['ratio_ventes_production'] = "Le ratio ventes/production est de " . round($ratio, 2) . "%.";
            
            if ($ratio < 80) {
                $raisons['probleme_ecoulement'] = "Problème possible d'écoulement des produits. La production est significativement plus élevée que les ventes.";
            } elseif ($ratio > 98) {
                $raisons['production_insuffisante'] = "La production pourrait être insuffisante pour satisfaire la demande.";
            }
        }
        
        return $raisons;
    }
}
