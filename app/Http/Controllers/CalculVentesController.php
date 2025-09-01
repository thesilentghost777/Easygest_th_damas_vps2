<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ReceptionVendeur;
use App\Models\TransactionVente;
use App\Models\Produit_fixes;
use App\Models\User;
use Carbon\Carbon;

class CalculVentesController extends Controller
{
    /**
     * Affiche la page de sélection du mois
     */
    public function index()
    {
        return view('calcul-ventes.index');
    }

    /**
     * Effectue le calcul des ventes pour le mois sélectionné
     */
    public function calculer(Request $request)
    {
        $request->validate([
            'mois' => 'required|integer|min:1|max:12',
            'annee' => 'required|integer|min:2020|max:2030'
        ]);

        $mois = $request->mois;
        $annee = $request->annee;

        try {
            DB::beginTransaction();

            // Supprimer les anciennes transactions pour ce mois
            TransactionVente::whereYear('date_vente', $annee)
                ->whereMonth('date_vente', $mois)
                ->delete();

            // Récupérer toutes les réceptions pour le mois sélectionné
            $receptions = ReceptionVendeur::with(['vendeur', 'produit'])
                ->whereYear('date_reception', $annee)
                ->whereMonth('date_reception', $mois)
                ->get();

            $transactionsCreees = 0;

            foreach ($receptions as $reception) {
                $produit = $reception->produit;
                $vendeur = $reception->vendeur;
                $dateReception = $reception->date_reception;

                // 1. Enregistrer les avaries si > 0
                if ($reception->quantite_avarie > 0) {
                    TransactionVente::create([
                        'produit' => $reception->produit_id,
                        'serveur' => $reception->vendeur_id,
                        'quantite' => $reception->quantite_avarie,
                        'prix' => 0,
                        'date_vente' => $dateReception,
                        'type' => 'Produit Avarie',
                        'monnaie' => 'cash'
                    ]);
                    $transactionsCreees++;
                }

                // 2. Enregistrer les invendus si > 0
                if ($reception->quantite_invendue > 0) {
                    TransactionVente::create([
                        'produit' => $reception->produit_id,
                        'serveur' => $reception->vendeur_id,
                        'quantite' => $reception->quantite_invendue,
                        'prix' => 0,
                        'date_vente' => $dateReception,
                        'type' => 'Produit invendu',
                        'monnaie' => 'cash'
                    ]);
                    $transactionsCreees++;
                }

                // 3. Calculer et enregistrer les ventes
                $totalEntree = $reception->quantite_entree_matin + $reception->quantite_entree_journee;
                $quantiteVente = $totalEntree + $reception->quantite_reste_hier - $reception->quantite_invendue - $reception->quantite_avarie;

                if ($quantiteVente > 0) {
                    TransactionVente::create([
                        'produit' => $reception->produit_id,
                        'serveur' => $reception->vendeur_id,
                        'quantite' => $quantiteVente,
                        'prix' => $produit->prix,
                        'date_vente' => $dateReception,
                        'type' => 'Vente',
                        'monnaie' => 'cash'
                    ]);
                    $transactionsCreees++;
                }
            }

            DB::commit();

            return redirect()->route('calcul-ventes.resultats', ['mois' => $mois, 'annee' => $annee])
                ->with('success', "Calcul terminé avec succès ! {$transactionsCreees} transactions créées.");

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors du calcul : ' . $e->getMessage());
        }
    }

    /**
     * Affiche les résultats du calcul
     */
    public function resultats($mois, $annee)
    {
        $nomMois = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        // Statistiques globales
        $stats = TransactionVente::whereYear('date_vente', $annee)
            ->whereMonth('date_vente', $mois)
            ->selectRaw('
                type,
                COUNT(*) as nombre_transactions,
                SUM(quantite) as quantite_totale,
                SUM(quantite * prix) as chiffre_affaire
            ')
            ->groupBy('type')
            ->get();

        // Détail par vendeur
        $ventesParVendeur = TransactionVente::with(['vendeur', 'Produit_fixes'])
            ->whereYear('date_vente', $annee)
            ->whereMonth('date_vente', $mois)
            ->where('type', 'Vente')
            ->selectRaw('
                serveur,
                COUNT(*) as nombre_ventes,
                SUM(quantite) as quantite_vendue,
                SUM(quantite * prix) as chiffre_affaire
            ')
            ->groupBy('serveur')
            ->get();

        // Détail des transactions récentes
        $transactionsRecentes = TransactionVente::with(['vendeur', 'Produit_fixes'])
            ->whereYear('date_vente', $annee)
            ->whereMonth('date_vente', $mois)
            ->orderBy('date_vente', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('calcul-ventes.resultats', compact(
            'mois', 'annee', 'nomMois', 'stats', 'ventesParVendeur', 'transactionsRecentes'
        ));
    }
}