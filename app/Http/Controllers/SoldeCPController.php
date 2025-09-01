<?php

namespace App\Http\Controllers;

use App\Models\SoldeCP;
use App\Models\HistoriqueSoldeCP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\HistorisableActions;


class SoldeCPController extends Controller
{
    use HistorisableActions;

    public function index()
    {
        $nom = auth()->user()->name;
        $role = auth()->user()->role;
        $solde = SoldeCP::getSoldeActuel();
        $historique = HistoriqueSoldeCP::with('user')
         ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('solde-cp.index', compact('solde', 'historique', 'nom', 'role'));
    }

    public function ajuster()
    {
        $nom = auth()->user()->name;
        $role = auth()->user()->role;
        $solde = SoldeCP::getSoldeActuel();
        return view('solde-cp.ajuster', compact('solde', 'nom', 'role'));
    }

    public function storeAjustement(Request $request)
    {
        $validated = $request->validate([
            'montant' => 'required|numeric',
            'operation' => 'required|in:ajouter,soustraire,fixer',
            'description' => 'required|string'
        ]);

        try {
            DB::beginTransaction();
            $user = auth()->user();
            $solde = SoldeCP::getSoldeActuel();
            $montantInitial = $solde->montant;
            $nouveauMontant = $montantInitial;
            if ($validated['montant'] < 0) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Soyons serieux dans ce que nous faisons Le montant ne peut etre negatif')
                    ->withInput();
            }
            $montantOperation = abs($validated['montant']);
            $typeOperation = 'ajustement';

            // Calculer le nouveau solde
            switch ($validated['operation']) {
                case 'ajouter':
                    $nouveauMontant += $montantOperation;
                    $this->historiser("L'utilisateur {$user->name} a ajouter  {$montantOperation} au solde cp", 'modify_solde_cp');

                    break;

                case 'soustraire':
                    if ($montantInitial < $montantOperation) {
                        DB::rollBack();
                        return redirect()->back()
                            ->with('error', 'Le montant à soustraire est supérieur au solde actuel.')
                            ->withInput();
                    }
                    $nouveauMontant -= $montantOperation;
                    $this->historiser("L'utilisateur {$user->name} a soustrait  {$montantOperation} au solde cp", 'modify_solde_cp');

                    break;

                case 'fixer':
                    $nouveauMontant = $montantOperation;
                    $this->historiser("L'utilisateur {$user->name} a ajuster le solde cp a {$montantOperation}", 'modify_solde_cp');
                    break;
            }

            // Mettre à jour le solde

            $solde->montant = $nouveauMontant;
            $solde->derniere_mise_a_jour = now();
            $solde->description = $validated['description'];
            $solde->save();

            // Enregistrer l'historique
            HistoriqueSoldeCP::create([
                'montant' => $validated['montant'],
                'type_operation' => $typeOperation,
                'operation_id' => null,
                'solde_avant' => $montantInitial,
                'solde_apres' => $nouveauMontant,
                'user_id' => auth()->id(),
                'description' => $validated['description']
            ]);
            DB::commit();

            return redirect()->route('solde-cp.index')
                ->with('success', 'Ajustement du solde effectué avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire d'édition d'un historique
     */
    public function edit($id)
    {
        $nom = auth()->user()->name;
        $role = auth()->user()->role;
        $historique = HistoriqueSoldeCP::findOrFail($id);
        
        // Vérifier que seuls les ajustements peuvent être modifiés
        if ($historique->type_operation !== 'ajustement') {
            return redirect()->route('solde-cp.index')
                ->with('error', 'Seuls les ajustements peuvent être modifiés.');
        }

        return view('solde-cp.edit', compact('historique', 'nom', 'role'));
    }

    /**
     * Mettre à jour un historique et recalculer la chaîne
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'montant' => 'required|numeric',
            'description' => 'required|string'
        ]);

        try {
            DB::beginTransaction();
            
            $historique = HistoriqueSoldeCP::findOrFail($id);
            
            // Vérifier que c'est bien un ajustement
            if ($historique->type_operation !== 'ajustement') {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Seuls les ajustements peuvent être modifiés.');
            }

            $ancienMontant = $historique->montant;
            $nouveauMontant = $validated['montant'];
            $difference = $nouveauMontant - $ancienMontant;

            // Mettre à jour l'historique
            $historique->montant = $nouveauMontant;
            $historique->description = $validated['description'];
            $historique->solde_apres = $historique->solde_avant + $nouveauMontant;
            $historique->save();

            // Recalculer tous les soldes suivants
            $this->recalculerSoldesDepuis($historique->id, $difference);

            // Mettre à jour le solde actuel
            $soldeActuel = SoldeCP::first();
            $soldeActuel->montant += $difference;
            $soldeActuel->derniere_mise_a_jour = now();
            $soldeActuel->save();

            // Historiser la modification
            $user = auth()->user();
            $this->historiser("L'utilisateur {$user->name} a modifié un ajustement de solde (différence: {$difference})", 'modify_solde_cp');

            DB::commit();

            return redirect()->route('solde-cp.index')
                ->with('success', 'Ajustement modifié avec succès. Les soldes ont été recalculés.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprimer un historique et recalculer la chaîne
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $historique = HistoriqueSoldeCP::findOrFail($id);
            
            // Vérifier que c'est bien un ajustement
            if ($historique->type_operation !== 'ajustement') {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Seuls les ajustements peuvent être supprimés.');
            }

            $montantSupprime = $historique->montant;
            
            // Recalculer tous les soldes suivants (en soustrayant le montant supprimé)
            $this->recalculerSoldesDepuis($historique->id, -$montantSupprime, true);

            // Mettre à jour le solde actuel
            $soldeActuel = SoldeCP::first();
            $soldeActuel->montant -= $montantSupprime;
            $soldeActuel->derniere_mise_a_jour = now();
            $soldeActuel->save();

            // Supprimer l'historique
            $historique->delete();

            // Historiser la suppression
            $user = auth()->user();
            $this->historiser("L'utilisateur {$user->name} a supprimé un ajustement de solde de {$montantSupprime}", 'modify_solde_cp');

            DB::commit();

            return redirect()->route('solde-cp.index')
                ->with('success', 'Ajustement supprimé avec succès. Les soldes ont été recalculés.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Recalculer les soldes à partir d'un point donné
     */
    private function recalculerSoldesDepuis($historiqueId, $difference, $suppression = false)
    {
        // Récupérer tous les historiques suivants (créés après celui modifié/supprimé)
        $historique = HistoriqueSoldeCP::find($historiqueId);
        
        if (!$suppression) {
            // Si c'est une modification, on commence par l'historique suivant
            $historiquesASuivre = HistoriqueSoldeCP::where('created_at', '>', $historique->created_at)
                ->orWhere(function($query) use ($historique) {
                    $query->where('created_at', '=', $historique->created_at)
                          ->where('id', '>', $historique->id);
                })
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        } else {
            // Si c'est une suppression, on commence dès l'historique supprimé
            $historiquesASuivre = HistoriqueSoldeCP::where('created_at', '>', $historique->created_at)
                ->orWhere(function($query) use ($historique) {
                    $query->where('created_at', '=', $historique->created_at)
                          ->where('id', '>', $historique->id);
                })
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        }

        // Mettre à jour chaque historique suivant
        foreach ($historiquesASuivre as $h) {
            $h->solde_avant += $difference;
            $h->solde_apres += $difference;
            $h->save();
        }
    }

    /**
     * Afficher les détails d'un historique
     */
    public function show($id)
    {
        $nom = auth()->user()->name;
        $role = auth()->user()->role;
        $historique = HistoriqueSoldeCP::with('user')->findOrFail($id);
        
        return view('solde-cp.show', compact('historique', 'nom', 'role'));
    }
}
