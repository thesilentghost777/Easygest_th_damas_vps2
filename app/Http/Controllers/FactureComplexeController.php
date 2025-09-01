<?php

namespace App\Http\Controllers;

use App\Models\FactureComplexe;
use App\Models\FactureComplexeDetail;
use App\Models\Matiere;
use App\Models\MatiereComplexe;
use App\Models\AssignationMatiere;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Traits\HistorisableActions;

class FactureComplexeController extends Controller
{
    use HistorisableActions;
    /**
     * Afficher la liste des factures
     */
    public function index()
    {
        $producteur = Auth::user();
        $secteur = $producteur->secteur;
        $role = $producteur->role;
        if ($secteur != 'administration' && $role != 'caissiere' ) {
            // Si l'utilisateur n'est pas dans le secteur administration, on filtre les factures
            $factures = FactureComplexe::with('producteur')
                ->whereHas('producteur', function ($query) use ($producteur) {
                    $query->where('producteur_id', $producteur->id);
                })
                ->orderBy('created_at', 'desc')
                ->get();
            
                return view('factures-complexe.index', compact('factures'));

        }
        $factures = FactureComplexe::with('producteur')
            ->orderBy('created_at', 'desc')
            ->get();
        

        return view('factures-complexe.index', compact('factures'));
    }

    /**
     * Afficher le formulaire de création d'une facture
     */
    public function create()
    {
        $producteurs = User::whereIn('role', ['boulanger', 'patissier'])
                            ->orWhere('role', 'glace')
                            ->get();
        $matieres = Matiere::with('complexe')
        ->whereRaw("LOWER(nom) NOT LIKE 'taules%'")
    ->whereRaw("LOWER(nom) NOT LIKE 'produit avarier%'")
    ->get();


        return view('factures-complexe.create', compact('producteurs', 'matieres'));
    }

    /**
     * Enregistrer une nouvelle facture
     */
    public function store(Request $request)
    {
        $request->validate([
            'producteur_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
            'matieres' => 'required|array',
            'matieres.*.id' => 'required|exists:Matiere,id',
            'matieres.*.quantite' => 'required|numeric|min:0.001',
            'matieres.*.unite' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Créer la facture
            $facture = new FactureComplexe([
                'reference' => FactureComplexe::genererReference(),
                'producteur_id' => $request->producteur_id,
                'notes' => $request->notes,
                'montant_total' => 0, // sera calculé ci-dessous
                'statut' => 'en_attente',
                'date_creation' => now(),
            ]);

            $facture->save();

            $montantTotal = 0;

            // Ajouter les détails de la facture
            foreach ($request->matieres as $matiereData) {
                if (!isset($matiereData['quantite']) || $matiereData['quantite'] <= 0) {
                    continue; // Ignorer les lignes avec quantité nulle ou négative
                }

                $matiere = Matiere::findOrFail($matiereData['id']);
                $matiereComplexe = MatiereComplexe::where('matiere_id', $matiere->id)->first();

             
                // Déterminer le prix unitaire à utiliser
                $prixUnitaire = $matiereComplexe->prix_complexe ?? $matiere->prix_unitaire;

                // Calculer la quantité unitaire
                $quantiteUnitaire = $matiereData['quantite'] / $matiere->quantite_par_unite;

                // Calculer le montant pour cette ligne en utilisant la quantité unitaire
                $montant = $quantiteUnitaire * $prixUnitaire;
                $montantTotal += $montant;

                // Créer le détail de la facture
                FactureComplexeDetail::create([
                    'facture_id' => $facture->id,
                    'matiere_id' => $matiere->id,
                    'quantite' => $matiereData['quantite'],
                    'unite' => $matiereData['unite'],
                    'prix_unitaire' => $prixUnitaire,
                    'montant' => $montant,
                ]);
            }

            // Mettre à jour le montant total de la facture
            $facture->montant_total = $montantTotal;
            $facture->save();
            // Historiser l'action de création de la facture
            $user = Auth::user();
            $this->historiser("L'utilisateur {$user->name} a créé une facture complexe pour le producteur {$facture->producteur->name} avec un montant total de {$facture->montant_total}", 'create_facture_complexe');
            DB::commit();

            return redirect()->route('factures-complexe.show', $facture->id)
                ->with('success', 'Facture créée avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Une erreur est survenue: ' . $e->getMessage()]);
        }
    }

    /**
     * Afficher une facture
     */
    public function show($id)
    {
        $facture = FactureComplexe::with(['producteur', 'details.matiere'])->findOrFail($id);
        return view('factures-complexe.show', compact('facture'));
    }

    /**
     * Afficher le formulaire d'édition d'une facture
     */
    public function edit($id)
    {
        $facture = FactureComplexe::with(['producteur', 'details.matiere'])->findOrFail($id);

        // Vérifier si la facture peut être modifiée
        if ($facture->statut !== 'en_attente') {
            return redirect()->route('factures-complexe.index')
                ->with('error', 'Impossible de modifier une facture qui a déjà été validée ou annulée');
        }

        $producteurs = User::whereIn('role', ['boulanger', 'patissier'])->get();
        $matieres = Matiere::with('complexe')->get();

        return view('factures-complexe.edit', compact('facture', 'producteurs', 'matieres'));
    }

    /**
     * Mettre à jour une facture
     */
    public function update(Request $request, $id)
    {
        $facture = FactureComplexe::findOrFail($id);

        // Vérifier si la facture peut être modifiée
        if ($facture->statut !== 'en_attente') {
            return redirect()->route('factures-complexe.index')
                ->with('error', 'Impossible de modifier une facture qui a déjà été validée ou annulée');
        }

        $request->validate([
            'producteur_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
            'matieres' => 'required|array',
            'matieres.*.id' => 'required|exists:Matiere,id',
            'matieres.*.quantite' => 'required|numeric|min:0.001',
            'matieres.*.unite' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Mettre à jour les informations de base de la facture
            $facture->producteur_id = $request->producteur_id;
            $facture->notes = $request->notes;

            // Supprimer les anciens détails
            FactureComplexeDetail::where('facture_id', $facture->id)->delete();

            $montantTotal = 0;

            // Ajouter les nouveaux détails
            foreach ($request->matieres as $matiereData) {
                if (!isset($matiereData['quantite']) || $matiereData['quantite'] <= 0) {
                    continue; // Ignorer les lignes avec quantité nulle ou négative
                }

                $matiere = Matiere::findOrFail($matiereData['id']);
                $matiereComplexe = MatiereComplexe::where('matiere_id', $matiere->id)->first();

                // Déterminer le prix unitaire à utiliser
                $prixUnitaire = $matiereComplexe->prix_complexe ?? $matiere->prix_unitaire;

                // Calculer le montant pour cette ligne
                $montant = $matiereData['quantite'] * $prixUnitaire;
                $montantTotal += $montant;

                // Créer le détail de la facture
                FactureComplexeDetail::create([
                    'facture_id' => $facture->id,
                    'matiere_id' => $matiere->id,
                    'quantite' => $matiereData['quantite'],
                    'unite' => $matiereData['unite'],
                    'prix_unitaire' => $prixUnitaire,
                    'montant' => $montant,
                ]);
            }

            // Mettre à jour le montant total de la facture
            $facture->montant_total = $montantTotal;
            $facture->save();
            // Historiser l'action de mise à jour de la facture
            $user = Auth::user();
            $this->historiser("L'utilisateur {$user->name} a mis à jour la facture complexe pour le producteur {$facture->producteur->name} avec un nouveau montant total de {$facture->montant_total}", 'update_facture_complexe');
            DB::commit();

            return redirect()->route('factures-complexe.show', $facture->id)
                ->with('success', 'Facture mise à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Une erreur est survenue: ' . $e->getMessage()]);
        }
    }

    /**
     * Supprimer une facture
     */
    public function destroy($id)
    {
        $facture = FactureComplexe::findOrFail($id);

        // Vérifier si la facture peut être supprimée
        if ($facture->statut !== 'en_attente') {
            return redirect()->route('factures-complexe.index')
                ->with('error', 'Impossible de supprimer une facture qui a déjà été validée ou annulée');
        }

        // Supprimer la facture (les détails seront supprimés automatiquement grâce à onDelete('cascade'))
        $facture->delete();
        // Historiser l'action de suppression de la facture
        $user = Auth::user();
        $this->historiser("L'utilisateur {$user->name} a supprimé la facture complexe pour le producteur {$facture->producteur->name}", 'delete_facture_complexe');
        return redirect()->route('factures-complexe.index')
            ->with('success', 'Facture supprimée avec succès');
    }

    /**
     * Afficher les factures en attente de validation
     */
    public function facturesEnAttente()
    {
        if (Auth::user()->role == 'caissiere') {
            $factures = FactureComplexe::where('statut', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->get();
        }else{
             $factures = FactureComplexe::with('producteur')
            ->where('statut', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->get();

        }
       
        return view('factures-complexe.en-attente', compact('factures'));
    }

    /**
     * Valider une facture
     */
    public function valider($id)
    {
        $facture = FactureComplexe::findOrFail($id);

        // Vérifier si la facture peut être validée
        if ($facture->statut !== 'en_attente') {
            return redirect()->route('factures-complexe.index')
                ->with('error', 'Cette facture a déjà été traitée');
        }

        // Mettre à jour le statut de la facture
        $facture->statut = 'validee';
        $facture->date_validation = now();
        $facture->validee_par = Auth::user()->name; // Enregistrer le nom de l'utilisateur qui a validé la facture
        $facture->save();
        // Historiser l'action de validation de la facture
        $user = Auth::user();
        $this->historiser("L'utilisateur {$user->name} a validé la facture complexe pour le producteur {$facture->producteur->name} avec un montant total de {$facture->montant_total}", 'validate_facture_complexe');
        return redirect()->route('factures-complexe.en-attente')
            ->with('success', 'Facture validée avec succès');
    }

    /**
     * Annuler une facture
     */
    public function annuler($id)
    {
        $facture = FactureComplexe::findOrFail($id);

        // Vérifier si la facture peut être annulée
        if ($facture->statut !== 'en_attente') {
            return redirect()->route('factures-complexe.index')
                ->with('error', 'Cette facture a déjà été traitée');
        }

        // Mettre à jour le statut de la facture
        $facture->statut = 'annulee';
        $facture->save();
        // Historiser l'action d'annulation de la facture
        $user = Auth::user();
        $this->historiser("L'utilisateur {$user->name} a annulé la facture complexe pour le producteur {$facture->producteur->name}", 'cancel_facture_complexe');
        return redirect()->route('factures-complexe.en-attente')
            ->with('success', 'Facture annulée avec succès');
    }

    /**
     * Afficher les statistiques des factures
     */
    public function statistiques(Request $request)
    {
        $mois = $request->input('mois', date('m'));
        $annee = $request->input('annee', date('Y'));

        $dateDebut = Carbon::createFromDate($annee, $mois, 1)->startOfMonth();
        $dateFin = Carbon::createFromDate($annee, $mois, 1)->endOfMonth();

        // Récupérer le total des factures pour le mois sélectionné
        $totalFacturesMois = FactureComplexe::where('statut', 'validee')
            ->whereBetween('date_validation', [$dateDebut, $dateFin])
            ->sum('montant_total');

        // Récupérer les factures par jour pour le mois sélectionné
        $facturesParJour = FactureComplexe::where('statut', 'validee')
            ->whereBetween('date_validation', [$dateDebut, $dateFin])
            ->get();

        // Récupérer les matières les plus demandées pour le mois sélectionné
        $matieresPlusDemandees = FactureComplexeDetail::join('factures_complexe', 'facture_complexe_details.facture_id', '=', 'factures_complexe.id')
            ->join('Matiere', 'facture_complexe_details.matiere_id', '=', 'Matiere.id')
            ->where('factures_complexe.statut', 'validee')
            ->whereBetween('factures_complexe.date_validation', [$dateDebut, $dateFin])
            ->selectRaw('Matiere.nom, SUM(facture_complexe_details.quantite) as quantite_totale, facture_complexe_details.unite, SUM(facture_complexe_details.montant) as montant_total')
            ->groupBy('Matiere.nom', 'facture_complexe_details.unite')
            ->orderByDesc('montant_total')
            ->limit(10)
            ->get();

        // Récupérer les factures par producteur pour le mois sélectionné
        $facturesParProducteur = FactureComplexe::join('users', 'factures_complexe.producteur_id', '=', 'users.id')
            ->where('factures_complexe.statut', 'validee')
            ->whereBetween('factures_complexe.date_validation', [$dateDebut, $dateFin])
            ->selectRaw('users.name, COUNT(factures_complexe.id) as nombre_factures, SUM(factures_complexe.montant_total) as montant_total')
            ->groupBy('users.name')
            ->orderByDesc('montant_total')
            ->get();

        $moisName = Carbon::createFromDate($annee, $mois, 1)->locale('fr_FR')->isoFormat('MMMM Y');

        return view('factures-complexe.statistiques', compact(
            'totalFacturesMois',
            'facturesParJour',
            'matieresPlusDemandees',
            'facturesParProducteur',
            'mois',
            'annee',
            'moisName'
        ));
    }
    public function validate(FactureComplexe $facture)
    {
        try {
            DB::beginTransaction();

            // Vérifier si la facture est déjà validée
            if ($facture->statut !== 'en_attente') {
                return back()->with('error', 'Cette facture ne peut pas être validée car elle n\'est pas en attente.');
            }

            // Mettre à jour le statut et la date de validation
            $facture->statut = 'validee';
            $facture->date_validation = now();
            $facture->save();
            // Historiser l'action de validation de la facture
            $user = Auth::user();
            $this->historiser("L'utilisateur {$user->name} a validé la facture complexe pour le producteur {$facture->producteur->name} avec un montant total de {$facture->montant_total}", 'validate_facture_complexe');
            DB::commit();
            return redirect()->route('factures-complexe.show', $facture->id)
                ->with('success', 'La facture a été validée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la validation de la facture: ' . $e->getMessage());
        }
    }
}
