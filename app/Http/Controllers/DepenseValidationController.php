<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DepenseValidationController extends Controller
{
    /**
     * Affichage de la liste des dépenses en attente de validation
     */
    public function index()
    {
        $depenses = Depense::with(['user', 'matiere'])
            ->where('valider', true)
            ->whereNull('validated_at')
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('depenses.validation.index', compact('depenses'));
    }

    /**
     * Confirmer une dépense et créer la transaction
     */
    public function confirm(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $depense = Depense::findOrFail($id);
            
            if ($depense->validated_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette dépense a déjà été traitée.'
                ]);
            }

            // Marquer comme validée
            $depense->update([
                'validated_at' => Carbon::now()
            ]);

            // Créer la transaction correspondante
            $transaction = Transaction::create([
                'type' => 'outcome',
                'category_id' => $this->getCategoryIdByType($depense->type),
                'amount' => $depense->prix,
                'date' => $depense->date,
                'description' => "Validation dépense: {$depense->nom} (ID: {$depense->id})"
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dépense confirmée avec succès!',
                'transaction_id' => $transaction->id
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la confirmation: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Annuler une dépense
     */
    public function cancel(Request $request, $id)
    {
        try {
            $depense = Depense::findOrFail($id);
            
            if ($depense->validated_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette dépense a déjà été traitée.'
                ]);
            }

            // Marquer comme annulée (on peut utiliser validated_at avec une valeur spéciale)
            $depense->update([
                'valider' => false,
                'validated_at' => Carbon::now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dépense annulée avec succès!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'annulation: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtenir l'ID de catégorie basé sur le type de dépense
     * (À adapter selon votre table categories)
     */
    private function getCategoryIdByType($type)
    {
        $categoryMapping = [
            'achat_matiere' => 2,
            'livraison_matiere' => 2,
            'reparation' => 4,
            'depense_fiscale' => 5,
            'autre' => 6
        ];

        return $categoryMapping[$type] ?? 6; // Default à 'autre'
    }
}