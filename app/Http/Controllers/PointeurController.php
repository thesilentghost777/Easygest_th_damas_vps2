<?php

namespace App\Http\Controllers;

use App\Models\ProduitRecu1;
use App\Models\ProduitRecuVendeur;
use App\Models\ProduitStock;
use App\Models\User;
use App\Models\Commande;
use App\Models\TransactionVente;
use App\Traits\HistorisableActions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PointeurController extends Controller
{
    use HistorisableActions;
    
    /**
     * Affiche le tableau de bord du pointeur
     */
    public function dashboard()
    {
        $user = Auth::user();
        $nom = $user->name;
        $secteur = $user->role;
        $commandesEnAttente = Commande::with('produit_fixe')
            ->where('valider', 'en_attente')
            ->orderBy('date_commande', 'desc')
            ->get();
            
        $produitsRecus = ProduitRecu1::with(['produit', 'producteur'])
            ->orderBy('date_reception', 'desc')
            ->take(10)
            ->get();
            
        return view('pointeur.dashboard', compact('commandesEnAttente', 'produitsRecus','nom','secteur'));
    }
    
    /**
     * Enregistre un produit reçu du producteur
     */
    public function enregistrerProduit(Request $request)
{
    Log::info('Début de la méthode enregistrerProduit', [
        'request_data' => $request->all(),
        'user_id' => auth()->id()
    ]);

    $validated = $request->validate([
        'produit_id' => 'required|exists:Produit_fixes,code_produit',
        'quantite' => 'required|integer|min:1',
        'producteur_id' => 'required|exists:users,id',
        'remarques' => 'nullable|string'
    ]);

    Log::info('Données validées avec succès', ['validated' => $validated]);

    try {
        DB::transaction(function () use ($validated) {
            $produitId = $validated['produit_id'];
            $quantite = $validated['quantite'];
            $producteurId = $validated['producteur_id'];
            $pointeurId = auth()->id();

            Log::info('Variables initialisées dans la transaction', [
                'produit_id' => $produitId,
                'quantite' => $quantite,
                'producteur_id' => $producteurId,
                'pointeur_id' => $pointeurId
            ]);

            // Vérifier si une entrée existe déjà pour ce produit aujourd'hui par ce producteur
            $aujourdhui = now()->startOfDay();
            $produitExistant = ProduitRecu1::where('produit_id', $produitId)
                ->where('producteur_id', $producteurId)
                ->whereDate('date_reception', $aujourdhui)
                ->first();

            Log::info('Recherche de produit existant', [
                'date_recherche' => $aujourdhui->format('Y-m-d'),
                'produit_existant' => $produitExistant ? true : false,
                'produit_existant_details' => $produitExistant
            ]);

            if ($produitExistant) {
                // Mettre à jour l'entrée existante
                $ancienneQuantite = $produitExistant->quantite;
                $produitExistant->quantite += $quantite;
                $produitExistant->remarques .= "\n" . ($validated['remarques'] ?? "Mise à jour le " . now()->format('d/m/Y H:i'));
                $produitExistant->save();

                Log::info('Mise à jour du produit existant', [
                    'id' => $produitExistant->id,
                    'ancienne_quantite' => $ancienneQuantite,
                    'nouvelle_quantite' => $produitExistant->quantite,
                    'remarques' => $produitExistant->remarques
                ]);

                $this->historiser("Mise à jour de la quantité du produit #$produitId : +$quantite unités", 'update', $produitExistant->id, 'produit_recu');
                $produitRecuId = $produitExistant->id;
            } else {
                // Créer une nouvelle entrée
                $produitRecu = ProduitRecu1::create([
                    'produit_id' => $produitId,
                    'quantite' => $quantite,
                    'producteur_id' => $producteurId,
                    'pointeur_id' => $pointeurId,
                    'date_reception' => now(),
                    'remarques' => $validated['remarques'] ?? null
                ]);

                Log::info('Nouveau produit créé', [
                    'id' => $produitRecu->id,
                    'details' => $produitRecu->toArray()
                ]);

                $this->historiser("Enregistrement du produit #$produitId : $quantite unités", 'create', $produitRecu->id, 'produit_recu');
                $produitRecuId = $produitRecu->id;
            }
        });

        Log::info('Transaction terminée avec succès');
        
        return redirect()->back()
            ->with('success', 'Produit enregistré avec succès');
    } catch (\Exception $e) {
        Log::error('Erreur lors de l\'enregistrement du produit', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
            ->with('error', 'Erreur lors de l\'enregistrement du produit: ' . $e->getMessage());
    }
}
    
    /**
     * Affiche la liste des produits reçus disponibles pour assignation aux vendeurs
     */
    public function listeProduitsPourAssignation()
    {
        $produits = ProduitRecu1::with(['produit', 'producteur'])
            ->whereNull('vendeur_id')
            ->where('date_reception', '>=', Carbon::now()->subDays(7))
            ->orderBy('date_reception', 'desc')
            ->get()
            ->groupBy('produit_id')
            ->map(function ($group) {
                $first = $group->first();
                return [
                    'produit_id' => $first->produit_id,
                    'produit' => $first->produit,
                    'quantite_totale' => $group->sum('quantite'),
                    'items' => $group
                ];
            });
            
        $vendeurs = User::where('secteur', 'vente')->orWhere('role','glace')->get();
        
        return view('pointeur.assignation.create', compact('produits', 'vendeurs'));
    }
    
    /**
     * Assigne des produits à un vendeur
     */
    public function assignerProduits(Request $request)
    {
        $validated = $request->validate([
            'vendeur_id' => 'required|exists:users,id',
            'produit_recu_ids' => 'required|array',
            'produit_recu_ids.*' => 'required|exists:produits_recu_1,id',
            'quantites' => 'required|array',
            'quantites.*' => 'required|integer|min:1',
            'remarques' => 'nullable|string'
        ]);
        
        try {
            DB::transaction(function () use ($validated) {
                $vendeurId = $validated['vendeur_id'];
                $produitRecuIds = $validated['produit_recu_ids'];
                $quantites = $validated['quantites'];
                $remarques = $validated['remarques'] ?? null;
                
                foreach ($produitRecuIds as $index => $produitRecuId) {
                    $produitRecu = ProduitRecu1::find($produitRecuId);
                    $quantite = $quantites[$index];
                    
                    // Vérifier que la quantité demandée est disponible
                    if ($produitRecu->quantite < $quantite) {
                        throw new \Exception("Quantité insuffisante pour le produit #{$produitRecu->produit_id}");
                    }
                    
                    // Créer l'entrée d'assignation
                    ProduitRecuVendeur::create([
                        'produit_recu_id' => $produitRecuId,
                        'vendeur_id' => $vendeurId,
                        'quantite_recue' => $quantite,
                        'status' => 'en_attente',
                        'remarques' => $remarques
                    ]);
                    
                    // Mettre à jour la référence au vendeur
                    $produitRecu->vendeur_id = $vendeurId;
                    $produitRecu->save();
                    
                    $this->historiser(
                        "Assignation de {$quantite} unités du produit #{$produitRecu->produit_id} au vendeur #{$vendeurId}",
                        'assign',
                        $produitRecu->id,
                        'produit_recu_vendeur'
                    );
                }
            });
            
            return redirect()->route('pointeur.assignation.liste')
                ->with('success', 'Produits assignés avec succès au vendeur');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'assignation des produits: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'assignation des produits: ' . $e->getMessage());
        }
    }
    
    /**
     * Liste des assignations effectuées
     */
    public function listeAssignations()
    {
        $assignations = ProduitRecuVendeur::with(['produitRecu.produit', 'vendeur'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('pointeur.assignation.liste', compact('assignations'));
    }
    
    /**
     * Rapport des assignations par vendeur
     */
    public function rapportVendeurs(Request $request)
    {
        // Période par défaut: semaine en cours
        $dateDebut = $request->get('date_debut', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $dateFin = $request->get('date_fin', Carbon::now()->format('Y-m-d'));
        
        $rapportVendeurs = ProduitRecuVendeur::with(['vendeur', 'produitRecu.produit'])
            ->whereBetween(DB::raw('DATE(created_at)'), [$dateDebut, $dateFin])
            ->select(
                'vendeur_id',
                DB::raw('SUM(CASE WHEN status = "confirmé" THEN quantite_confirmee ELSE 0 END) as quantite_confirmee_total'),
                DB::raw('SUM(CASE WHEN status = "en_attente" THEN quantite_recue ELSE 0 END) as quantite_en_attente'),
                DB::raw('SUM(CASE WHEN status = "rejeté" THEN quantite_recue ELSE 0 END) as quantite_rejetee'),
                DB::raw('COUNT(DISTINCT produit_recu_id) as nombre_produits')
            )
            ->groupBy('vendeur_id')
            ->get();
            
        // Récupérer les ventes correspondantes
        $ventesParVendeur = TransactionVente::whereBetween('date_vente', [$dateDebut, $dateFin])
            ->select(
                'serveur',
                DB::raw('SUM(CASE WHEN type = "Vente" THEN quantite ELSE 0 END) as quantite_vendue'),
                DB::raw('SUM(CASE WHEN type = "Produit invendu" THEN quantite ELSE 0 END) as quantite_invendue'),
                DB::raw('SUM(CASE WHEN type = "Produit Avarie" THEN quantite ELSE 0 END) as quantite_avariee'),
                DB::raw('SUM(CASE WHEN type = "Vente" THEN quantite * prix ELSE 0 END) as montant_vendu')
            )
            ->groupBy('serveur')
            ->get()
            ->keyBy('serveur');
        
        // Fusionner les données
        foreach ($rapportVendeurs as $rapport) {
            $ventes = $ventesParVendeur->get($rapport->vendeur_id);
            
            $rapport->quantite_vendue = $ventes ? $ventes->quantite_vendue : 0;
            $rapport->quantite_invendue = $ventes ? $ventes->quantite_invendue : 0;
            $rapport->quantite_avariee = $ventes ? $ventes->quantite_avariee : 0;
            $rapport->montant_vendu = $ventes ? $ventes->montant_vendu : 0;
            
            // Calculer les ratios
            $totalAssigne = $rapport->quantite_confirmee_total + $rapport->quantite_en_attente;
            if ($totalAssigne > 0) {
                $rapport->taux_vente = round(($rapport->quantite_vendue / $totalAssigne) * 100, 1);
                $rapport->taux_invendu = round(($rapport->quantite_invendue / $totalAssigne) * 100, 1);
                $rapport->taux_avarie = round(($rapport->quantite_avariee / $totalAssigne) * 100, 1);
            } else {
                $rapport->taux_vente = 0;
                $rapport->taux_invendu = 0;
                $rapport->taux_avarie = 0;
            }
        }
        
        return view('pointeur.rapport.vendeurs', compact('rapportVendeurs', 'dateDebut', 'dateFin'));
    }
    
    /**
     * Valider une commande
     */
    public function validerCommande(Commande $commande)
{
    try {
        $user = Auth::user();

        $commande->valider = true;
        $commande->save();

        $this->historiser(
            "Validation de la commande #{$commande->id} pour le produit #{$commande->produit} par le pointeur {$user->name}", 
            'validate_commande'
        );

        return redirect()->route('pointeur.workspace')
            ->with('success', 'Commande validée avec succès.');
            
    } catch (\Exception $e) {
        Log::error('Erreur lors de la validation de la commande: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Erreur lors de la validation de la commande: ' . $e->getMessage());
    }
}

}
