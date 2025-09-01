<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Matiere;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MatiereBelowThreshold;

class CheckMatiereThresholds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matiere:check-thresholds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Vérifie les seuils des matières premières et envoie des notifications si nécessaire";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Vérification des seuils des matières premières...');
        
        $matieresBelowThreshold = Matiere::where('notification_active', true)
            ->whereRaw('quantite < quantite_seuil')
            ->get();
            
        if ($matieresBelowThreshold->count() > 0) {
            Log::info($matieresBelowThreshold->count() . ' matières en dessous du seuil détectées.');
            
            // Notifier les administrateurs
            $admins = User::where('role', 'admin')
                          ->orWhere('role', 'dg')
                          ->get();
            
            if ($admins->count() > 0) {
                Log::info('Envoi de notifications à ' . $admins->count() . ' administrateurs...');
                
                Notification::send($admins, new MatiereBelowThreshold($matieresBelowThreshold));
                
                Log::info('Notifications envoyées avec succès.');
            } else {
                Log::alert('Aucun administrateur trouvé pour recevoir les notifications.');
            }
            
            // Afficher les matières concernées
            $this->table(
                ['ID', 'Nom', 'Quantité actuelle', 'Seuil', 'Unité'],
                $matieresBelowThreshold->map(function ($matiere) {
                    return [
                        $matiere->id,
                        $matiere->nom,
                        $matiere->quantite,
                        $matiere->quantite_seuil,
                        $matiere->unite_minimale
                    ];
                })
            );
        } else {
            Log::info('Toutes les matières sont au-dessus de leur seuil de notification.');
        }
        
        return 0;
    }
}
