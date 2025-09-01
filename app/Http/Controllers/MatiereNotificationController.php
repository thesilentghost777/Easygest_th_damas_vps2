<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Notifications\MatiereBelowThreshold;
use App\Models\User;
use App\Traits\HistorisableActions;

class MatiereNotificationController extends Controller
{
    use HistorisableActions;
    public function index()
    {
        $matieres = Matiere::where('nom', 'not like', 'Taule%')
                   ->where('nom', 'not like', 'produit avar%')
                   ->orderBy('nom')
                   ->get(); 
        return view('matieres.notifications.index', compact('matieres'));
    }
    
    public function update(Request $request, Matiere $matiere)
    {
        Log::info("IIII-debut");
        $validated = $request->validate([
            'quantite_seuil' => 'required|numeric|min:0',
            'notification_active' => 'boolean'
        ]);
        Log::info("IIII-donner valider $validated");
        
        $matiere->update([
            'quantite_seuil' => $validated['quantite_seuil'],
            'notification_active' => $request->has('notification_active')
        ]);
        Log::info("IIII-update succes");
        // Historiser l'action
        $this->historiser("L'utilisateur " . auth()->user()->name . " a mis à jour le seuil de notification pour la matière {$matiere->nom} (ID: {$matiere->id})", 'update_matiere_notification');
        return redirect()->back()
            ->with('success', 'Seuil de notification mis à jour avec succès.');
    }
    
    public function updateBatch(Request $request)
{
    Log::info("IIII-debut", ['request_data' => $request->all()]);
    
    try {
        $request->validate([
            'matieres' => 'required|array',
            'matieres.*.id' => 'required|exists:Matiere,id',
            'matieres.*.quantite_seuil' => 'required|numeric|min:0',
            'matieres.*.notification_active' => 'boolean',
            'selected_matieres' => 'array',
            'selected_matieres.*' => 'exists:Matiere,id'
        ]);
        Log::info("IIII-validation terminer", [
            'total_matieres' => count($request->matieres),
            'selected_matieres' => $request->selected_matieres ?? []
        ]);
        // Historiser l'action
        $this->historiser("L'utilisateur " . auth()->user()->name . " a mis à jour les notifications pour les matières sélectionnées", 'update_matiere_notification_batch');
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error("IIII-erreur validation", [
            'errors' => $e->errors(),
            'request_data' => $request->all()
        ]);
        throw $e;
    }
    
    $selectedMatiereIds = $request->selected_matieres ?? [];
    Log::info("IIII-matieres selectionnees", ['ids' => $selectedMatiereIds]);
    
    if (empty($selectedMatiereIds)) {
        Log::warning("IIII-aucune matiere selectionnee");
        return redirect()->back()
            ->with('warning', 'Aucune matière sélectionnée pour la mise à jour.');
    }
    
    $updatedCount = 0;
    $errors = [];
    
    // Utiliser une transaction pour s'assurer de la cohérence
    DB::beginTransaction();
    
    try {
        foreach ($request->matieres as $index => $matiereData) {
            // Vérifier si cette matière est sélectionnée
            if (!in_array($matiereData['id'], $selectedMatiereIds)) {
                Log::info("IIII-matiere ignoree (non selectionnee)", [
                    'index' => $index,
                    'matiere_id' => $matiereData['id']
                ]);
                continue;
            }
            
            Log::info("IIII-traitement matiere selectionnee", [
                'index' => $index,
                'matiere_id' => $matiereData['id'],
                'quantite_seuil' => $matiereData['quantite_seuil'],
                'notification_active' => isset($matiereData['notification_active'])
            ]);
            
            $matiere = Matiere::find($matiereData['id']);
            
            if (!$matiere) {
                Log::warning("IIII-matiere non trouvee", ['id' => $matiereData['id']]);
                $errors[] = "Matière avec ID {$matiereData['id']} non trouvée";
                continue;
            }
            
            Log::info("IIII-matiere avant update", [
                'id' => $matiere->id,
                'nom' => $matiere->nom,
                'ancien_seuil' => $matiere->quantite_seuil,
                'ancienne_notification' => $matiere->notification_active
            ]);
            
            // CORRECTION 1: Conversion explicite du seuil en float/decimal
            $quantiteSeuil = (float) $matiereData['quantite_seuil'];
            
            // CORRECTION 2: Gestion correcte du boolean
            $notificationActive = isset($matiereData['notification_active']) && 
                                 ($matiereData['notification_active'] === true || 
                                  $matiereData['notification_active'] === '1' || 
                                  $matiereData['notification_active'] === 1);
            
            $updateData = [
                'quantite_seuil' => $quantiteSeuil,
                'notification_active' => $notificationActive
            ];
            
            Log::info("IIII-donnees a mettre a jour", [
                'updateData' => $updateData,
                'types' => [
                    'quantite_seuil_type' => gettype($quantiteSeuil),
                    'notification_active_type' => gettype($notificationActive)
                ]
            ]);
            
            // CORRECTION 3: Utilisation de update avec vérification plus robuste
            $updateResult = $matiere->update($updateData);
            
            // CORRECTION 4: Vérification alternative avec une requête directe
            if (!$updateResult) {
                // Tentative avec une requête directe
                $directUpdateResult = DB::table('Matiere')
                    ->where('id', $matiereData['id'])
                    ->update([
                        'quantite_seuil' => $quantiteSeuil,
                        'notification_active' => $notificationActive,
                        'updated_at' => now()
                    ]);
                
                Log::info("IIII-tentative update direct", [
                    'matiere_id' => $matiereData['id'],
                    'direct_result' => $directUpdateResult
                ]);
                
                if ($directUpdateResult > 0) {
                    $updateResult = true;
                }
            }
            
            if ($updateResult) {
                $updatedCount++;
                
                // Recharger pour vérifier
                $matiere->refresh();
                
                // CORRECTION 5: Vérification supplémentaire avec une requête fraîche
                $freshMatiere = Matiere::find($matiereData['id']);
                
                Log::info("IIII-matiere updated et rechargee", [
                    'id' => $matiere->id,
                    'nom' => $matiere->nom,
                    'nouveau_seuil_refresh' => $matiere->quantite_seuil,
                    'nouvelle_notification_refresh' => $matiere->notification_active,
                    'nouveau_seuil_fresh' => $freshMatiere->quantite_seuil,
                    'nouvelle_notification_fresh' => $freshMatiere->notification_active,
                    'values_match' => [
                        'seuil_match' => ($matiere->quantite_seuil == $freshMatiere->quantite_seuil),
                        'notification_match' => ($matiere->notification_active == $freshMatiere->notification_active)
                    ]
                ]);
                
                // Vérifier si la mise à jour a vraiment eu lieu
                if ($freshMatiere->quantite_seuil != $quantiteSeuil) {
                    Log::warning("IIII-update pas pris en compte", [
                        'expected_seuil' => $quantiteSeuil,
                        'actual_seuil' => $freshMatiere->quantite_seuil,
                        'matiere_id' => $matiereData['id']
                    ]);
                    $errors[] = "La mise à jour du seuil pour la matière ID {$matiereData['id']} n'a pas été prise en compte";
                }
            } else {
                Log::warning("IIII-echec update", ['matiere_id' => $matiereData['id']]);
                $errors[] = "Échec de mise à jour pour la matière ID {$matiereData['id']}";
            }
        }
        
        // Si pas d'erreurs, valider la transaction
        if (empty($errors)) {
            DB::commit();
            Log::info("IIII-transaction committed");
        } else {
            DB::rollback();
            Log::warning("IIII-transaction rolled back due to errors");
        }
        
    } catch (\Exception $e) {
        DB::rollback();
        Log::error("IIII-erreur generale transaction", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        throw $e;
    }
    
    Log::info("IIII-fin traitement", [
        'total_matieres_recues' => count($request->matieres),
        'matieres_selectionnees' => count($selectedMatiereIds),
        'matieres_updated' => $updatedCount,
        'errors_count' => count($errors),
        'errors' => $errors
    ]);
    
    if (!empty($errors)) {
        Log::warning("IIII-fin avec erreurs", ['errors' => $errors]);
        return redirect()->back()
            ->with('warning', 'Mise à jour partielle: ' . implode(', ', $errors));
    }
    
    Log::info("IIII-fin success");
    return redirect()->back()
        ->with('success', "Seuils de notification mis à jour avec succès pour {$updatedCount} matière(s).");
}

    public function checkThresholds()
    {
        $matieresBelowThreshold = Matiere::where('notification_active', true)
            ->whereRaw('quantite < quantite_seuil')
            ->get();
            
        if ($matieresBelowThreshold->count() > 0) {
            // Notifier les administrateurs
            $admins = User::where('role', 'admin')->orWhere('role', 'dg')->get();
            
            foreach ($admins as $admin) {
                Notification::send($admin, new MatiereBelowThreshold($matieresBelowThreshold));
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => $matieresBelowThreshold->count() . ' matières en dessous du seuil ont été notifiées.'
        ]);
    }
}
