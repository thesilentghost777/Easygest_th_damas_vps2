<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HistorisableActions;

class ConfigurationController extends Controller
{
    use HistorisableActions;
    public function index()
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est DG
      

        // Récupérer ou créer la configuration
        $config = Configuration::firstOrCreate(
            ['id' => 1],
            [
                'first_config' => false,
                'flag1' => false,
                'flag2' => false,
                'flag3' => false,
                'flag4' => false,
            ]
        );

        $isFrench = session('locale', 'fr') === 'fr';

        return view('configurations.index', compact('config', 'isFrench'));
    }

    public function toggleSalaire(Request $request)
    {
        $user = Auth::user();
        
        

        try {
            $config = Configuration::firstOrCreate(['id' => 1]);
            $config->flag2 = !$config->flag2;
            $config->save();

            $status = $config->flag2 ? 'débloquées' : 'bloquées';
            $statusEn = $config->flag2 ? 'unlocked' : 'locked';
            $isFrench = session('locale', 'fr') === 'fr';

            // Historiser l'action
            $this->historiser("Salaire requests toggled by {$user->name} to {$status}", 'toggle_salaire_requests');
            return response()->json([
                'success' => true,
                'message' => $isFrench 
                    ? "Demandes de salaire {$status} avec succès" 
                    : "Salary requests {$statusEn} successfully",
                'status' => $config->flag2
            ]);
        } catch (\Exception $e) {
            $isFrench = session('locale', 'fr') === 'fr';
            return response()->json([
                'success' => false,
                'message' => $isFrench 
                    ? 'Erreur lors de la mise à jour' 
                    : 'Error during update'
            ], 500);
        }
    }

    public function toggleAvanceSalaire(Request $request)
    {
        $user = Auth::user();
        

        try {
            $config = Configuration::firstOrCreate(['id' => 1]);
            $config->flag3 = !$config->flag3;
            $config->save();

            $status = $config->flag3 ? 'débloquées' : 'bloquées';
            $statusEn = $config->flag3 ? 'unlocked' : 'locked';
            $isFrench = session('locale', 'fr') === 'fr';

            // Historiser l'action
            $this->historiser("Salary advance requests toggled by {$user->name} to {$status}", 'toggle_avance_salaire_requests');
            return response()->json([
                'success' => true,
                'message' => $isFrench 
                    ? "Demandes d'avance salaire {$status} avec succès" 
                    : "Salary advance requests {$statusEn} successfully",
                'status' => $config->flag3
            ]);
        } catch (\Exception $e) {
            $isFrench = session('locale', 'fr') === 'fr';
            return response()->json([
                'success' => false,
                'message' => $isFrench 
                    ? 'Erreur lors de la mise à jour' 
                    : 'Error during update'
            ], 500);
        }
    }
}
