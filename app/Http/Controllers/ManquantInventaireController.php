<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matiere;
use Illuminate\Support\Facades\DB;

class ManquantInventaireController extends Controller
{
    public function index()
    {
        $matieres = Matiere::whereRaw("LOWER(nom) NOT LIKE 'taules%'")
                   ->whereRaw("LOWER(nom) NOT LIKE 'produit avarie%'")
                   ->orderBy('nom')
                   ->get();

        $isFrench = app()->getLocale() === 'fr' || session('locale') === 'fr';
        
        return view('manquant-inventaire.index', compact('matieres', 'isFrench'));
    }

    public function calculer(Request $request)
    {
        $request->validate([
            'quantites_reelles' => 'required|array',
            'quantites_reelles.*' => 'required|numeric|min:0',
        ]);

        $matieres = Matiere::whereRaw("LOWER(nom) NOT LIKE 'taules%'")
                   ->whereRaw("LOWER(nom) NOT LIKE 'produit avarie%'")
                   ->orderBy('nom')
                   ->get();
        $quantitesReelles = $request->quantites_reelles;
        $manquants = [];
        $totalManquants = 0;

        foreach ($matieres as $matiere) {
            $quantiteAttendue = $matiere->quantite;
            $quantiteReelle = $quantitesReelles[$matiere->id] ?? 0;
            $manquant = max(0, $quantiteAttendue - $quantiteReelle);
            
            if ($manquant > 0) {
                $manquants[] = [
                    'matiere' => $matiere,
                    'quantite_attendue' => $quantiteAttendue,
                    'quantite_reelle' => $quantiteReelle,
                    'manquant' => $manquant,
                    'valeur_manquant' => $manquant * $matiere->prix_unitaire
                ];
                $totalManquants += $manquant * $matiere->prix_unitaire;
            }
        }

        $isFrench = app()->getLocale() === 'fr' || session('locale') === 'fr';
        $recommendations = $this->genererRecommandations($manquants, $isFrench);

        return view('manquant-inventaire.rapport', compact('manquants', 'totalManquants', 'recommendations', 'isFrench'));
    }

    private function genererRecommandations($manquants, $isFrench)
    {
        $recommendations = [];
        
        if (count($manquants) > 5) {
            $recommendations[] = $isFrench 
                ? "Réviser les processus de commande pour éviter les ruptures de stock fréquentes."
                : "Review ordering processes to avoid frequent stockouts.";
        }

        $totalValue = array_sum(array_column($manquants, 'valeur_manquant'));
        if ($totalValue > 50000) {
            $recommendations[] = $isFrench 
                ? "La valeur totale des manquants est élevée. Considérer une augmentation des stocks de sécurité."
                : "Total shortage value is high. Consider increasing safety stock levels.";
        }

        if (empty($recommendations)) {
            $recommendations[] = $isFrench 
                ? "Maintenir les bonnes pratiques de gestion des stocks actuelles."
                : "Maintain current good inventory management practices.";
        }

        return $recommendations;
    }
}
