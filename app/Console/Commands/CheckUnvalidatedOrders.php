<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Commande;
use App\Models\ListenerLog;
use App\Http\Controllers\NotificationController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CheckUnvalidatedOrders extends Command
{
    protected $signature = 'orders:check-unvalidated';
    protected $description = 'Check for unvalidated orders and notify pointeurs and CP';

    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
    }

    public function handle()
    {
        $startTime = microtime(true);
        $currentHour = Carbon::now('Africa/Douala')->hour;
        Log::info("CheckUnvalidatedOrders: Début de la vérification des commandes non validées - Heure: $currentHour");

        try {
            $today = Carbon::now('Africa/Douala');
            
            // Vérifier les commandes non validées du jour
            $unvalidatedOrders = Commande::whereDate('date_commande', $today->format('Y-m-d'))
                ->where('valider', false)
                ->get();

            if ($unvalidatedOrders->isEmpty()) {
                $message = "Aucune commande non validée trouvée pour aujourd'hui";
                Log::info("CheckUnvalidatedOrders: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $notificationsSent = 0;
            $details = [];

            if ($currentHour == 18) {
                // À 18h10, notifier les pointeurs en service
                $pointeursEnService = User::where('secteur', 'production')
                ->where('role', 'pointeur')
                ->whereHas('horaires', function ($query) {
                    $query->whereDate('arrive', today())
                          ->whereNotNull('arrive')
                          ->whereNull('depart'); // Encore présent (pas encore parti)
                })
                ->get();

                if ($pointeursEnService->isEmpty()) {
                    // Si aucun pointeur en service, notifier tous les pointeurs
                    $pointeursEnService = User::where('secteur', 'production')
                        ->where('role', 'pointeur')
                        ->get();
                }

                foreach ($pointeursEnService as $pointeur) {
                    $message = $this->getPointeurMessage($pointeur->language, count($unvalidatedOrders));
                    
                    $request = new Request([
                        'recipient_id' => $pointeur->id,
                        'subject' => $pointeur->language === 'en' ? 'Unvalidated Orders Alert' : 'Alerte Commandes Non Validées',
                        'message' => $message
                    ]);
                    
                    $this->notificationController->send($request);
                    $notificationsSent++;
                    
                    Log::info("CheckUnvalidatedOrders: Notification envoyée au pointeur {$pointeur->name} (ID: {$pointeur->id})");
                }
                
                $details['type'] = 'pointeur_notification';
            } elseif ($currentHour == 20) {
                // À 20h30, notifier les CP si toujours pas validé
                $chefProductions = User::where('secteur', 'production')
                    ->where('role', 'chef_production')
                    ->get();

                foreach ($chefProductions as $cp) {
                    $message = $this->getCPMessage($cp->language, count($unvalidatedOrders));
                    
                    $request = new Request([
                        'recipient_id' => $cp->id,
                        'subject' => $cp->language === 'en' ? 'Critical: Unvalidated Orders' : 'Critique: Commandes Non Validées',
                        'message' => $message
                    ]);
                    
                    $this->notificationController->send($request);
                    $notificationsSent++;
                    
                    Log::info("CheckUnvalidatedOrders: Notification envoyée au CP {$cp->name} (ID: {$cp->id})");
                }
                
                $details['type'] = 'cp_notification';
            }

            foreach ($unvalidatedOrders as $order) {
                $details['orders'][] = [
                    'order_id' => $order->id,
                    'libelle' => $order->libelle,
                    'quantite' => $order->quantite,
                    'date_commande' => $order->date_commande
                ];
            }

            $message = "Notifications envoyées à $notificationsSent utilisateur(s) pour " . count($unvalidatedOrders) . " commande(s) non validée(s)";
            Log::info("CheckUnvalidatedOrders: $message");
            $this->logExecution('success', $message, $details, $startTime);

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors de la vérification des commandes non validées: " . $e->getMessage();
            Log::error("CheckUnvalidatedOrders: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
    }

    private function getPointeurMessage($language, $count)
    {
        if ($language === 'en') {
            return "Urgent: $count order(s) are still not validated today. Please review and validate pending orders immediately.";
        }
        
        return "Urgent : $count commande(s) ne sont toujours pas validées aujourd'hui. Veuillez réviser et valider les commandes en attente immédiatement.";
    }

    private function getCPMessage($language, $count)
    {
        if ($language === 'en') {
            return "Critical Alert: $count order(s) remain unvalidated at 20:30. This requires immediate attention to avoid production delays.";
        }
        
        return "Alerte Critique : $count commande(s) restent non validées à 20h30. Ceci nécessite une attention immédiate pour éviter les retards de production.";
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'CheckUnvalidatedOrders',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}
