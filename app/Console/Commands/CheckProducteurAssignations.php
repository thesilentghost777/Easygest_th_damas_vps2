<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AssignationMatiere;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Log;

class CheckProducteurAssignations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:producteur-assignations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier si chaque producteur a une assignation de matière et notifier le chef de production si ce n\'est pas le cas';

    /**
     * Controller pour les notifications
     *
     * @var NotificationController
     */
    private $notificationController;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(NotificationController $notificationController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            Log::info('[CheckProducteurAssignations] Début de la vérification des assignations de matières');
            $this->info('Début de la vérification des assignations de matières...');

            // Test de la connexion à la base de données
            Log::info('[CheckProducteurAssignations] Test de connexion à la base de données');
            
            // Récupération de tous les producteurs (pâtissiers et boulangers)
            Log::info('[CheckProducteurAssignations] Récupération des producteurs (pâtissiers et boulangers)');
            $producteurs = User::whereIn('role', ['patissier', 'boulanger'])->get();
            Log::info('[CheckProducteurAssignations] Nombre de producteurs trouvés: ' . $producteurs->count());
            
            if ($producteurs->isEmpty()) {
                Log::warning('[CheckProducteurAssignations] Aucun producteur trouvé dans le système');
                $this->warn('Aucun producteur trouvé dans le système.');
                return 0;
            }
            
            // Récupération du chef de production
            Log::info('[CheckProducteurAssignations] Recherche du chef de production');
            $chefProduction = User::where('role', 'chef_production')->first();
            
            if (!$chefProduction) {
                Log::error('[CheckProducteurAssignations] Aucun chef de production trouvé dans le système');
                $this->error('Aucun chef de production trouvé dans le système.');
                return 1;
            }
            
            Log::info('[CheckProducteurAssignations] Chef de production trouvé: ' . $chefProduction->name . ' (ID: ' . $chefProduction->id . ')');

            $producteursNonAssignes = [];

            // Vérification pour chaque producteur
            Log::info('[CheckProducteurAssignations] Début de la vérification des assignations pour chaque producteur');
            foreach ($producteurs as $producteur) {
                Log::info('[CheckProducteurAssignations] Vérification pour le producteur: ' . $producteur->name . ' (ID: ' . $producteur->id . ', Role: ' . $producteur->role . ')');
                
                try {
                    $assignations = AssignationMatiere::where('producteur_id', $producteur->id)->where('date_limite_utilisation', '>=', now())->count();
                    Log::info('[CheckProducteurAssignations] Nombre d\'assignations pour ' . $producteur->name . ': ' . $assignations);
                    
                    if ($assignations === 0) {
                        $producteursNonAssignes[] = $producteur;
                        Log::warning('[CheckProducteurAssignations] Producteur sans assignation: ' . $producteur->name . ' (ID: ' . $producteur->id . ')');
                        $this->warn("Producteur sans assignation: {$producteur->name} (ID: {$producteur->id})");
                    }
                } catch (\Exception $e) {
                    Log::error('[CheckProducteurAssignations] Erreur lors de la vérification des assignations pour ' . $producteur->name . ': ' . $e->getMessage());
                    throw $e;
                }
            }

            Log::info('[CheckProducteurAssignations] Fin de la vérification. Nombre de producteurs non assignés: ' . count($producteursNonAssignes));

            // Si des producteurs n'ont pas d'assignations, notifier le chef de production
            if (!empty($producteursNonAssignes)) {
                Log::info('[CheckProducteurAssignations] Envoi de notification au chef de production');
                $this->notifierChefProduction($chefProduction->id, $producteursNonAssignes);
                Log::info('[CheckProducteurAssignations] Notification envoyée au chef de production');
                $this->info('Notification envoyée au chef de production.');
            } else {
                Log::info('[CheckProducteurAssignations] Tous les producteurs ont des assignations de matières');
                $this->info('Tous les producteurs ont des assignations de matières.');
            }

            Log::info('[CheckProducteurAssignations] Vérification terminée avec succès');
            $this->info('Vérification terminée avec succès.');
            return 0;
            
        } catch (\Exception $e) {
            Log::error('[CheckProducteurAssignations] Erreur fatale: ' . $e->getMessage());
            Log::error('[CheckProducteurAssignations] Stack trace: ' . $e->getTraceAsString());
            $this->error('Erreur lors de la vérification: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Notifier le chef de production
     *
     * @param int $chefProductionId
     * @param array $producteursNonAssignes
     */
    private function notifierChefProduction($chefProductionId, $producteursNonAssignes)
{
    try {
        Log::info('[CheckProducteurAssignations] Début de la création de la notification');
        
        // Récupération du chef de production
        $user = User::find($chefProductionId);
        $lang = $user->language ?? 'fr'; // Par défaut français si non défini
        
        // Création de la liste des producteurs concernés
        $listeProducteurs = '';
        foreach ($producteursNonAssignes as $producteur) {
            $listeProducteurs .= "- {$producteur->name} ({$producteur->role})\n";
        }

        $nombreProducteurs = count($producteursNonAssignes);

        if ($lang === 'en') {
            // Sujet
            $subject = $nombreProducteurs > 1 
                ? "Alert: {$nombreProducteurs} producers without material assignment"
                : "Alert: 1 producer without material assignment";

            // Message
            $message = "Hello,\n\n";
            $message .= $nombreProducteurs > 1 
                ? "The following producers currently have no material assignment:\n\n"
                : "The following producer currently has no material assignment:\n\n";
            $message .= $listeProducteurs;
            $message .= "\nPlease proceed with the necessary assignments.\n\n";
            $message .= "Automated management system";

        } else {
            // Sujet
            $subject = $nombreProducteurs > 1 
                ? "Alerte : {$nombreProducteurs} producteurs sans assignation de matières"
                : "Alerte : 1 producteur sans assignation de matières";

            // Message
            $message = "Bonjour,\n\n";
            $message .= $nombreProducteurs > 1 
                ? "Les producteurs suivants n'ont actuellement aucune assignation de matières :\n\n"
                : "Le producteur suivant n'a actuellement aucune assignation de matières :\n\n";
            $message .= $listeProducteurs;
            $message .= "\nMerci de procéder aux assignations nécessaires.\n\n";
            $message .= "Système de gestion automatique";
        }

        Log::info('[CheckProducteurAssignations] Sujet de la notification: ' . $subject);
        Log::info('[CheckProducteurAssignations] Destinataire: ID ' . $chefProductionId);

        // Création de la requête pour la notification
        Log::info('[CheckProducteurAssignations] Création de la requête pour NotificationController');
        $request = new Request();
        $request->merge([
            'recipient_id' => $chefProductionId,
            'subject' => $subject,
            'message' => $message
        ]);

        Log::info('[CheckProducteurAssignations] Données de la requête: ' . json_encode($request->all()));

        // Envoi de la notification
        Log::info('[CheckProducteurAssignations] Appel du NotificationController');
        $result = $this->notificationController->send($request);
        
        Log::info('[CheckProducteurAssignations] Résultat de l\'envoi de notification: ' . json_encode($result));
        
    } catch (\Exception $e) {
        Log::error('[CheckProducteurAssignations] Erreur lors de l\'envoi de la notification: ' . $e->getMessage());
        Log::error('[CheckProducteurAssignations] Stack trace notification: ' . $e->getTraceAsString());
        throw $e;
    }
}

}