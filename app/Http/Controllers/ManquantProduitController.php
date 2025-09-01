<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit_fixes;
use App\Models\ProduitStock;
use Illuminate\Support\Facades\DB;

class ManquantProduitController extends Controller
{
    public function index()
    {
        $produits = Produit_fixes::with('stock')->orderBy('nom')->get();
        $isFrench = app()->getLocale() === 'fr' || session('locale') === 'fr';
        
        return view('manquant-produit.index', compact('produits', 'isFrench'));
    }

    public function calculer(Request $request)
    {
        $request->validate([
            'quantites_reelles' => 'required|array',
            'quantites_reelles.*' => 'required|numeric|min:0',
        ]);

        $produits = Produit_fixes::with('stock')->orderBy('nom')->get();
        $quantitesReelles = $request->quantites_reelles;
        $manquants = [];
        $totalManquants = 0;

        foreach ($produits as $produit) {
            $quantiteAttendue = $produit->stock ? $produit->stock->quantite_en_stock : 0;
            $quantiteReelle = $quantitesReelles[$produit->code_produit] ?? 0;
            $manquant = max(0, $quantiteAttendue - $quantiteReelle);
            
            if ($manquant > 0) {
                $manquants[] = [
                    'produit' => $produit,
                    'quantite_attendue' => $quantiteAttendue,
                    'quantite_reelle' => $quantiteReelle,
                    'manquant' => $manquant,
                    'valeur_manquant' => $manquant * $produit->prix
                ];
                $totalManquants += $manquant * $produit->prix;
            }
        }

        $isFrench = app()->getLocale() === 'fr' || session('locale') === 'fr';
        $recommendations = $this->genererRecommandations($manquants, $isFrench);

        return view('manquant-produit.rapport', compact('manquants', 'totalManquants', 'recommendations', 'isFrench'));
    }

    private function genererRecommandations($manquants, $isFrench)
    {
        $recommendations = [];
        
        if (count($manquants) > 5) {
            $recommendations[] = $isFrench 
                ? "Réviser les processus de production pour éviter les ruptures de stock fréquentes."
                : "Review production processes to avoid frequent stockouts.";
        }

        $totalValue = array_sum(array_column($manquants, 'valeur_manquant'));
        if ($totalValue > 100000) {
            $recommendations[] = $isFrench 
                ? "La valeur totale des manquants est élevée. Considérer une augmentation de la production."
                : "Total shortage value is high. Consider increasing production levels.";
        }

        $categoriesManquantes = array_unique(array_column(array_column($manquants, 'produit'), 'categorie'));
        if (count($categoriesManquantes) > 3) {
            $recommendations[] = $isFrench 
                ? "Plusieurs catégories de produits sont touchées. Revoir la planification globale de production."
                : "Multiple product categories are affected. Review overall production planning.";
        }

        if (empty($recommendations)) {
            $recommendations[] = $isFrench 
                ? "Maintenir les bonnes pratiques de gestion des stocks de produits actuelles."
                : "Maintain current good product inventory management practices.";
        }

        return $recommendations;
    }
}
