<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use App\Models\Matiere;
use App\Models\SoldeCP;
use App\Models\HistoriqueSoldeCP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\HistorisableActions;


class DepenseController extends Controller
{
    use HistorisableActions;

    public function index()
    {
        $nom = auth()->user()->name;
        $role = auth()->user()->role;
        $depenses = Depense::with(['user', 'matiere'])->latest('date')->get();
        return view('depenses.index', compact('depenses','nom','role'));
    }

    public function index2()
    {
        $nom = auth()->user()->name;
        $role = auth()->user()->role;
        $depenses = Depense::with(['user', 'matiere'])->latest('date')->get()->where('type', 'livraison_matiere');
        return view('depenses.index2', compact('depenses','nom','role'));
    }

    public function create()
    {
        $matieres = Matiere::all();
        $solde = SoldeCP::getSoldeActuel();
        $nom = auth()->user()->name;
        $role = auth()->user()->role;

        return view('depenses.create', compact('matieres', 'solde', 'nom', 'role'));
    }

    public function store(Request $request)
{
    // 1. PROTECTION CONTRE LES DOUBLONS BASÉE SUR LE TIMESTAMP
    if ($request->has('form_timestamp')) {
        $cacheKey = 'expense_form_' . auth()->id() . '_' . $request->form_timestamp;
        
        if (Cache::has($cacheKey)) {
            return redirect()->route('depenses.index')
                ->with('warning', 'Cette dépense a déjà été enregistrée.');
        }
        
        // Marquer ce formulaire comme traité pour 5 minutes
        Cache::put($cacheKey, true, 300);
    }

    // 2. VALIDATION DES DONNÉES
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'type' => 'required|in:achat_matiere,livraison_matiere,reparation,autre,depense_fiscale',
        'date' => 'required|date',
        'idm' => 'required_if:type,achat_matiere,livraison_matiere|exists:Matiere,id|nullable',
        'prix' => 'required|numeric|min:0',
        'quantite' => 'required_if:type,achat_matiere,livraison_matiere|numeric|min:0|nullable',
        'form_timestamp' => 'nullable|string'
    ]);

    // 3. VÉRIFICATION SUPPLÉMENTAIRE CONTRE LES DOUBLONS RÉCENTS
    $recentDuplicate = Depense::where('nom', $validated['nom'])
        ->where('prix', $validated['prix'])
        ->where('type', $validated['type'])
        ->where('auteur', auth()->id())
        ->where('created_at', '>=', now()->subMinutes(1)) // 1 minute de protection
        ->first();

    if ($recentDuplicate) {
        return redirect()->route('depenses.index')
            ->with('info', 'Une dépense identique a été créée récemment.')
            ->with('expense_id', $recentDuplicate->id);
    }

    // Valider automatiquement sauf pour les livraisons
    $validated['valider'] = $validated['type'] !== 'livraison_matiere';
    $validated['auteur'] = auth()->id();

    try {
        DB::beginTransaction();

        // 4. VÉRIFICATION DU SOLDE AVANT CRÉATION
        if (in_array($validated['type'], ['achat_matiere','livraison_matiere', 'reparation','depense_fiscale','autre'])) {
            $soldeActuel = SoldeCP::getSoldeActuel();
            
            if ($soldeActuel->montant < $validated['prix']) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Solde insuffisant pour effectuer cette dépense. Solde actuel: ' . number_format($soldeActuel->montant, 0, ',', ' ') . ' FCFA')
                    ->withInput();
            }

            // 5. CRÉATION DE LA DÉPENSE
            $depenseData = collect($validated)->except(['form_timestamp'])->toArray();
            $depense = Depense::create($depenseData);

            // 6. MISE À JOUR DU SOLDE
            HistoriqueSoldeCP::logTransaction(
                $validated['prix'],
                'depense',
                $depense->id,
                "Dépense de type {$validated['type']} - {$validated['nom']}"
            );

        } else {
            // Créer la dépense sans affecter le solde (cas spécial si nécessaire)
            $depenseData = collect($validated)->except(['form_timestamp'])->toArray();
            $depense = Depense::create($depenseData);
        }

        // 7. HISTORISATION
        $user = auth()->user();
        $this->historiser(
            "L'utilisateur {$user->name} a créé une dépense '{$validated['nom']}' de {$validated['prix']} FCFA pour une dépense de type {$validated['type']}", 
            'create_depense_cp'
        );

        DB::commit();

        // 8. REDIRECTION AVEC SUCCÈS
        return redirect()->route('depenses.index')
            ->with('success', 'Dépense enregistrée avec succès.')
            ->with('expense_id', $depense->id);

    } catch (\Exception $e) {
        DB::rollBack();
        
        // 9. LOG DE L'ERREUR
        \Log::error('Erreur lors de la création de la dépense', [
            'user_id' => auth()->id(),
            'data' => $validated,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->with('error', 'Une erreur est survenue lors de l\'enregistrement de la dépense. Veuillez réessayer.')
            ->withInput();
    }
}

    public function edit(Depense $depense)
    {
        $matieres = Matiere::all();
        $nom = auth()->user()->name;
        $role = auth()->user()->role;
        return view('depenses.edit', compact('depense', 'matieres', 'nom', 'role'));
    }

    public function update(Request $request, Depense $depense)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|in:achat_matiere,livraison_matiere,reparation,depense_fiscale,autre',
            'date' => 'required|date',
            'idm' => 'required_if:type,achat_matiere,livraison_matiere|exists:Matiere,id|nullable',
            'prix' => 'required|numeric|min:0',
            'quantite' => 'required_if:type,achat_matiere,livraison_matiere|numeric|min:0|nullable'
        ]);

        try {
            DB::beginTransaction();

            // Si c'était une dépense qui affectait le solde, on récupère l'ancien montant
            $ancienMontant = 0;
            $nouveauMontant = $validated['prix'];

            if (in_array($depense->type, ['achat_matiere', 'reparation','depense_fiscale','autre'])) {
                $ancienMontant = $depense->prix;
            }

            // Mettre à jour la dépense
            $depense->update($validated);

            // Ajuster le solde si nécessaire (uniquement pour les dépenses d'achat ou réparation)
            if (in_array($validated['type'], ['achat_matiere', 'reparation','depense_fiscale','autre'])) {
                $difference = $nouveauMontant - $ancienMontant;

                // Si le nouveau montant est plus élevé, vérifier si le solde est suffisant
                if ($difference > 0) {
                    $soldeActuel = SoldeCP::getSoldeActuel();

                    if ($soldeActuel->montant < $difference) {
                        DB::rollBack();
                        return redirect()->back()
                            ->with('error', 'Solde insuffisant pour effectuer cette modification.')
                            ->withInput();
                    }

                    // Mettre à jour le solde
                    HistoriqueSoldeCP::logTransaction(
                        $difference,
                        'depense',
                        $depense->id,
                        "Ajustement de dépense - {$validated['nom']}"
                    );
                } elseif ($difference < 0) {
                    // Si le nouveau montant est moins élevé, rembourser la différence au solde
                    HistoriqueSoldeCP::logTransaction(
                        abs($difference),
                        'versement',
                        $depense->id,
                        "Remboursement suite à ajustement de dépense - {$validated['nom']}"
                    );
                }
            }

            // Historiser l'action
            $user = auth()->user();
            $this->historiser("L'utilisateur {$user->name} a mis à jour la dépense {$validated['nom']} avec un nouveau montant de {$validated['prix']} pour une dépense de type {$validated['type']}", 'update_depense_cp');
            DB::commit();

            return redirect()->route('depenses.index')
                ->with('success', 'Dépense mise à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Depense $depense)
    {
        try {
            DB::beginTransaction();

            // Si c'était une dépense qui affectait le solde, on rembourse le montant
            if (in_array($depense->type, ['achat_matiere','livraison_matiere', 'reparation','depense_fiscale','autre'])) {
                HistoriqueSoldeCP::logTransaction(
                    $depense->prix,
                    'versement',
                    null,
                    "Remboursement suite à suppression de dépense - {$depense->nom}"
                );
            }

            // Supprimer la dépense
            $depense->delete();
            // Historiser l'action
            $user = auth()->user();
            $this->historiser("L'utilisateur {$user->name} a supprimé la dépense {$depense->nom} de type {$depense->type} avec un montant de {$depense->prix}", 'delete_depense_cp');
            DB::commit();   

            return redirect()->route('depenses.index')
                ->with('success', 'Dépense supprimée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    public function validerLivraison(Depense $depense)
    {
        if ($depense->type !== 'livraison_matiere') {
            return back()->with('error', 'Cette dépense n\'est pas une livraison.');
        }

        $depense->update(['valider' => true]);
        // Historiser l'action
        $user = auth()->user();
        $this->historiser("L'utilisateur {$user->name} a validé la livraison de la dépense {$depense->nom} de type {$depense->type} avec un montant de {$depense->prix}", 'validate_livraison_depense_cp');
        return redirect()->route('depenses.index')
            ->with('success', 'Livraison validée avec succès.');
    }
}
