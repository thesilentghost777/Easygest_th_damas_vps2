<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complexe;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialController extends Controller
{
    /**
     * Récupérer le solde de cette structure
     */
    public function getSolde()
    {
        try {
            $complexe = Complexe::first(); // Récupère la structure locale
            
            if (!$complexe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune structure configurée'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'complexe_id' => $complexe->id_comp,
                    'nom' => $complexe->nom,
                    'solde' => $complexe->solde,
                    'solde_formatted' => number_format($complexe->solde, 0, ',', ' ') . ' FCFA'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du solde'
            ], 500);
        }
    }

    /**
     * Récupérer le revenu mensuel de cette structure
     */
    public function getRevenuMensuel()
    {
        try {
            $complexe = Complexe::first();
            
            if (!$complexe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune structure configurée'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'complexe_id' => $complexe->id_comp,
                    'nom' => $complexe->nom,
                    'revenu_mensuel' => $complexe->revenu_mensuel,
                    'revenu_mensuel_formatted' => number_format($complexe->revenu_mensuel, 0, ',', ' ') . ' FCFA'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du revenu mensuel'
            ], 500);
        }
    }

    /**
     * Récupérer le revenu annuel de cette structure
     */
    public function getRevenuAnnuel()
    {
        try {
            $complexe = Complexe::first();
            
            if (!$complexe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune structure configurée'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'complexe_id' => $complexe->id_comp,
                    'nom' => $complexe->nom,
                    'revenu_annuel' => $complexe->revenu_annuel,
                    'revenu_annuel_formatted' => number_format($complexe->revenu_annuel, 0, ',', ' ') . ' FCFA'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du revenu annuel'
            ], 500);
        }
    }

    /**
     * Calculer le chiffre d'affaires de cette structure
     */
    public function getChiffreAffaires(Request $request)
    {
        try {
            $complexe = Complexe::first();
            
            if (!$complexe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune structure configurée'
                ], 404);
            }
            
            // Paramètres de période (optionnels)
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $period = $request->get('period', 'all'); // all, today, week, month, year
            
            $query = Transaction::where('type', 'income');
            
            // Appliquer les filtres de date selon la période
            switch ($period) {
                case 'today':
                    $query->whereDate('date', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('date', Carbon::now()->month)
                          ->whereYear('date', Carbon::now()->year);
                    break;
                case 'year':
                    $query->whereYear('date', Carbon::now()->year);
                    break;
                case 'custom':
                    if ($startDate && $endDate) {
                        $query->whereBetween('date', [$startDate, $endDate]);
                    }
                    break;
            }
            
            $chiffreAffaires = $query->sum('amount');
            $nombreTransactions = $query->count();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'complexe_id' => $complexe->id_comp,
                    'nom' => $complexe->nom,
                    'periode' => $period,
                    'chiffre_affaires' => $chiffreAffaires,
                    'chiffre_affaires_formatted' => number_format($chiffreAffaires, 0, ',', ' ') . ' FCFA',
                    'nombre_transactions' => $nombreTransactions,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul du chiffre d\'affaires'
            ], 500);
        }
    }

    /**
     * Récupérer toutes les données financières de cette structure
     */
    public function getAllFinancialData(Request $request)
    {
        try {
            $complexe = Complexe::first();
            
            if (!$complexe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune structure configurée'
                ], 404);
            }
            
            // Calcul du chiffre d'affaires pour différentes périodes
            $chiffreAffairesToday = Transaction::where('type', 'income')
                                              ->whereDate('date', Carbon::today())
                                              ->sum('amount');
            
            $chiffreAffairesMonth = Transaction::where('type', 'income')
                                              ->whereMonth('date', Carbon::now()->month)
                                              ->whereYear('date', Carbon::now()->year)
                                              ->sum('amount');
            
            $chiffreAffairesYear = Transaction::where('type', 'income')
                                             ->whereYear('date', Carbon::now()->year)
                                             ->sum('amount');
            
            // Calcul des dépenses
            $depensesMonth = Transaction::where('type', 'outcome')
                                       ->whereMonth('date', Carbon::now()->month)
                                       ->whereYear('date', Carbon::now()->year)
                                       ->sum('amount');
            
            return response()->json([
                'success' => true,
                'data' => [
                    'complexe_id' => $complexe->id_comp,
                    'nom' => $complexe->nom,
                    'localisation' => $complexe->localisation,
                    'solde' => $complexe->solde,
                    'revenu_mensuel' => $complexe->revenu_mensuel,
                    'revenu_annuel' => $complexe->revenu_annuel,
                    'caisse_sociale' => $complexe->caisse_sociale,
                    'valeur_caisse_sociale' => $complexe->valeur_caisse_sociale,
                    'chiffre_affaires' => [
                        'aujourd_hui' => $chiffreAffairesToday,
                        'ce_mois' => $chiffreAffairesMonth,
                        'cette_annee' => $chiffreAffairesYear
                    ],
                    'depenses_mois' => $depensesMonth,
                    'benefice_mois' => $chiffreAffairesMonth - $depensesMonth,
                    'formatted' => [
                        'solde' => number_format($complexe->solde, 0, ',', ' ') . ' FCFA',
                        'revenu_mensuel' => number_format($complexe->revenu_mensuel, 0, ',', ' ') . ' FCFA',
                        'revenu_annuel' => number_format($complexe->revenu_annuel, 0, ',', ' ') . ' FCFA',
                        'chiffre_affaires_aujourd_hui' => number_format($chiffreAffairesToday, 0, ',', ' ') . ' FCFA',
                        'chiffre_affaires_mois' => number_format($chiffreAffairesMonth, 0, ',', ' ') . ' FCFA',
                        'chiffre_affaires_annee' => number_format($chiffreAffairesYear, 0, ',', ' ') . ' FCFA',
                        'depenses_mois' => number_format($depensesMonth, 0, ',', ' ') . ' FCFA',
                        'benefice_mois' => number_format($chiffreAffairesMonth - $depensesMonth, 0, ',', ' ') . ' FCFA'
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données financières'
            ], 500);
        }
    }

    /**
     * Récupérer les informations de base de cette structure
     */
    public function getComplexeInfo()
    {
        try {
            $complexe = Complexe::first();
            
            if (!$complexe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune structure configurée'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id_comp' => $complexe->id_comp,
                    'nom' => $complexe->nom,
                    'localisation' => $complexe->localisation,
                    'solde' => $complexe->solde,
                    'revenu_mensuel' => $complexe->revenu_mensuel,
                    'revenu_annuel' => $complexe->revenu_annuel,
                    'formatted' => [
                        'solde' => number_format($complexe->solde, 0, ',', ' ') . ' FCFA',
                        'revenu_mensuel' => number_format($complexe->revenu_mensuel, 0, ',', ' ') . ' FCFA',
                        'revenu_annuel' => number_format($complexe->revenu_annuel, 0, ',', ' ') . ' FCFA'
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des informations'
            ], 500);
        }
    }
}
