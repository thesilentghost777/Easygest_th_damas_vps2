<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Commande;
use App\Models\History;

class CommandeReductionController extends Controller
{
    public function index()
    {
        // Récupérer toutes les commandes non validées avec les informations des produits
        $commandes = DB::table('Commande')
            ->join('Produit_fixes', 'Commande.produit', '=', 'Produit_fixes.code_produit')
            ->where('Commande.valider', false)
            ->select(
                'Commande.*',
                'Produit_fixes.nom as nom_produit',
                'Produit_fixes.prix as prix_unitaire'
            )
            ->orderBy('Commande.date_commande', 'desc')
            ->get();

        return view('commandes.reduction.index', compact('commandes'));
    }

    public function processSelection(Request $request)
    {
        $request->validate([
            'commandes_ids' => 'required|array|min:1',
            'commandes_ids.*' => 'exists:Commande,id'
        ]);

        $commandesIds = $request->commandes_ids;
        
        // Récupérer les détails des commandes sélectionnées
        $commandes = DB::table('Commande')
            ->join('Produit_fixes', 'Commande.produit', '=', 'Produit_fixes.code_produit')
            ->whereIn('Commande.id', $commandesIds)
            ->where('Commande.valider', false)
            ->select(
                'Commande.*',
                'Produit_fixes.nom as nom_produit',
                'Produit_fixes.prix as prix_unitaire'
            )
            ->get();

        // Calculer les totaux
        $totalGeneral = 0;
        $commandesAvecTotaux = $commandes->map(function($commande) use (&$totalGeneral) {
            $sousTotal = $commande->prix_unitaire * $commande->quantite;
            $totalGeneral += $sousTotal;
            $commande->sous_total = $sousTotal;
            return $commande;
        });

        return view('commandes.reduction.selection', [
            'commandes' => $commandesAvecTotaux,
            'totalGeneral' => $totalGeneral,
            'commandesIds' => $commandesIds
        ]);
    }

    public function applyReduction(Request $request)
    {
        $request->validate([
            'commandes_ids' => 'required|array|min:1',
            'pourcentage_reduction' => 'required|numeric|min:0|max:100',
            'total_original' => 'required|numeric|min:0'
        ]);

        $pourcentageReduction = $request->pourcentage_reduction;
        $totalOriginal = $request->total_original;
        $montantReduction = ($totalOriginal * $pourcentageReduction) / 100;
        $montantFinal = $totalOriginal - $montantReduction;

        return response()->json([
            'montant_reduction' => number_format($montantReduction, 2),
            'montant_final' => number_format($montantFinal, 2)
        ]);
    }

    public function validerCommandes(Request $request)
    {
        $request->validate([
            'commandes_ids' => 'required|array|min:1',
            'pourcentage_reduction' => 'required|numeric|min:0|max:100',
            'total_original' => 'required|numeric|min:0',
            'montant_final' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $commandesIds = $request->commandes_ids;
            $pourcentageReduction = $request->pourcentage_reduction;
            $totalOriginal = $request->total_original;
            $montantFinal = $request->montant_final;

            // Récupérer les détails des commandes pour l'historique
            $commandes = DB::table('Commande')
                ->join('Produit_fixes', 'Commande.produit', '=', 'Produit_fixes.code_produit')
                ->whereIn('Commande.id', $commandesIds)
                ->select(
                    'Commande.*',
                    'Produit_fixes.nom as nom_produit',
                    'Produit_fixes.prix as prix_unitaire'
                )
                ->get();

            // Valider toutes les commandes sélectionnées
            DB::table('Commande')
                ->whereIn('id', $commandesIds)
                ->update(['valider' => true, 'updated_at' => now()]);

            // Préparer les données pour l'historique
            $commandesDetails = $commandes->map(function($commande) {
                return [
                    'id' => $commande->id,
                    'libelle' => $commande->libelle,
                    'produit' => $commande->nom_produit,
                    'quantite' => $commande->quantite,
                    'prix_unitaire' => $commande->prix_unitaire,
                    'sous_total' => $commande->prix_unitaire * $commande->quantite
                ];
            });

            $historyData = [
                'action' => 'Validation commandes avec réduction',
                'commandes_ids' => $commandesIds,
                'commandes_details' => $commandesDetails,
                'pourcentage_reduction' => $pourcentageReduction,
                'total_original' => $totalOriginal,
                'montant_final' => $montantFinal,
                'nombre_commandes' => count($commandesIds),
                'date_validation' => now()->format('Y-m-d H:i:s')
            ];

            // Enregistrer dans l'historique
            History::create([
                'description' => json_encode($historyData, JSON_UNESCAPED_UNICODE),
                'user_id' => Auth::id(),
                'action_type' => 'commande_validation_reduction',
                'ip_address' => $request->ip()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Commandes validées avec succès!',
                'nombre_commandes' => count($commandesIds)
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la validation: ' . $e->getMessage()
            ], 500);
        }
    }
}