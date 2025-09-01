<?php

namespace App\Console\Commands;

use App\Models\SoldeCP;
use App\Models\User;
use App\Models\ListenerLog;
use App\Http\Controllers\NotificationController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CheckSoldeCPBalance extends Command
{
    protected $signature = 'solde:check-cp-balance';
    protected $description = 'Remind CP to manage daily balance if still at 0';

    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
    }

    public function handle()
    {
        $startTime = microtime(true);
        Log::info("CheckSoldeCPBalance: Début de la vérification du solde CP");

        try {
            $today = Carbon::now('Africa/Douala');
            $soldeCP = SoldeCP::first();

            if (!$soldeCP || $soldeCP->montant > 0) {
                $message = $soldeCP ? "Le solde CP n'est pas à zéro (montant: {$soldeCP->montant})" : "Aucun enregistrement de solde CP trouvé";
                Log::info("CheckSoldeCPBalance: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            // Notifier tous les CP
            $chefProductions = User::where('role', 'chef_production')->get();

            if ($chefProductions->isEmpty()) {
                $message = "Aucun chef de production trouvé";
                Log::warning("CheckSoldeCPBalance: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $notificationsSent = 0;

            foreach ($chefProductions as $cp) {
                $message = $this->getBalanceMessage($cp->language);
                
                $request = new Request([
                    'recipient_id' => $cp->id,
                    'subject' => $cp->language === 'en' ? 'Daily Balance Management Reminder' : 'Rappel Gestion Solde Journalier',
                    'message' => $message
                ]);
                
                $this->notificationController->send($request);
                $notificationsSent++;
                
                Log::info("CheckSoldeCPBalance: Notification envoyée au CP {$cp->name} (ID: {$cp->id})");
            }

            $message = "Rappel envoyé à $notificationsSent CP(s) pour gérer le solde journalier";
            Log::info("CheckSoldeCPBalance: $message");
            $this->logExecution('success', $message, ['solde_amount' => $soldeCP->montant], $startTime);

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors de la vérification du solde CP: " . $e->getMessage();
            Log::error("CheckSoldeCPBalance: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
    }

    private function getBalanceMessage($language)
    {
        if ($language === 'en') {
            return "Reminder: Please check the 'Daily Balance Management' feature to record the amount received this morning from the DG if the balance is still at 0.";
        }
        
        return "Rappel : Veuillez consulter la fonctionnalité 'Gérer Solde Journalier' pour enregistrer le montant reçu ce matin par le DG si le solde est toujours à 0.";
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'CheckSoldeCPBalance',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}
