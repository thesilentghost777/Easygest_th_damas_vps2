<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Utilisation;
use App\Models\ProduitRecu1;
use App\Models\ListenerLog;
use App\Http\Controllers\NotificationController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CheckMissingProductReception extends Command
{
    protected $signature = 'reception:check-missing';
    protected $description = 'Check for missing product reception declarations from pointeurs';

    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
    }

    public function handle()
    {
        $startTime = microtime(true);
        Log::info("CheckMissingProductReception: Début de la vérification des réceptions manquantes");

        try {
            $today = Carbon::now('Africa/Douala');

            // Récupérer les productions d'aujourd'hui (table Utilisation)
            $productionsToday = Utilisation::whereDate('created_at', $today->format('Y-m-d'))
                ->get();

            if ($productionsToday->isEmpty()) {
                $message = "Aucune production trouvée";
                Log::info("CheckMissingProductReception: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $missingReceptions = [];

            foreach ($productionsToday as $production) {
                // Vérifier s'il y a une déclaration de réception pour ce produit
                $hasReception = ProduitRecu1::where('produit_id', $production->produit)
                    ->where('producteur_id', $production->producteur)
                    ->whereDate('date_reception', $today->format('Y-m-d'))
                    ->exists();

                if (!$hasReception) {
                    $missingReceptions[] = [
                        'production_id' => $production->id,
                        'produit_id' => $production->produit,
                        'producteur_id' => $production->producteur,
                        'quantite_produite' => $production->quantite_produit,
                        'heure_production' => $production->created_at,
                        'temps_ecoule' => $today->diffInHours($production->created_at)
                    ];
                    
                    Log::info("CheckMissingProductReception: Production manquante - Produit {$production->produit}, Producteur {$production->producteur}, Production: {$production->created_at}");
                }
            }

            if (empty($missingReceptions)) {
                $message = "Toutes les productions ont leurs déclarations de réception";
                Log::info("CheckMissingProductReception: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            // Notifier les pointeurs et CP
            $pointeurs = User::where('secteur', 'production')
                ->where('role', 'pointeur')
                ->get();
                
            $chefProductions = User::where('secteur', 'administration')
                ->where('role', 'chef_production')
                ->get();

            $notificationsSent = 0;

            // Notifier les pointeurs
            foreach ($pointeurs as $pointeur) {
                $message = $this->getPointeurMessage($pointeur->language, count($missingReceptions));
                
                $request = new Request([
                    'recipient_id' => $pointeur->id,
                    'subject' => $pointeur->language === 'en' ? 'Missing Product Reception' : 'Réceptions Produits Manquantes',
                    'message' => $message
                ]);
                
                $this->notificationController->send($request);
                $notificationsSent++;
                
                Log::info("CheckMissingProductReception: Notification envoyée au pointeur {$pointeur->name} (ID: {$pointeur->id})");
            }

            // Notifier les CP
            foreach ($chefProductions as $cp) {
                $message = $this->getCPMessage($cp->language, count($missingReceptions));
                
                $request = new Request([
                    'recipient_id' => $cp->id,
                    'subject' => $cp->language === 'en' ? 'Missing Product Reception Alert' : 'Alerte Réceptions Produits Manquantes',
                    'message' => $message
                ]);
                
                $this->notificationController->send($request);
                $notificationsSent++;
                
                Log::info("CheckMissingProductReception: Notification envoyée au CP {$cp->name} (ID: {$cp->id})");
            }

            $message = "Notifications envoyées à $notificationsSent utilisateur(s) pour " . count($missingReceptions) . " réception(s) manquante(s)";
            Log::info("CheckMissingProductReception: $message");
            $this->logExecution('success', $message, $missingReceptions, $startTime);

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors de la vérification des réceptions manquantes: " . $e->getMessage();
            Log::error("CheckMissingProductReception: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
    }

    private function getPointeurMessage($language, $count)
    {
        if ($language === 'en') {
            return "Alert: $count product reception(s) are missing declarations. Products were produced over 2 hours ago but not yet recorded by the pointer. Please check and record receptions immediately.";
        }
        
        return "Alerte : $count réception(s) de produits manquent de déclarations. Des produits ont été produits , mais pas encore enregistrés par le pointeur. Veuillez vérifier et enregistrer les réceptions immédiatement.";
    }

    private function getCPMessage($language, $count)
    {
        if ($language === 'en') {
            return "Production Alert: $count product reception(s) are missing. This may indicate issues in the production-reception workflow (producer-pointer). Please investigate immediately.";
        }
        
        return "Alerte Production : $count réception(s) de produits manquantes. Ceci peut indiquer des problèmes dans le flux production-réception (producteur-pointeur). Veuillez enquêter immédiatement.";
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'CheckMissingProductReception',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}
