<?php

namespace App\Console\Commands;

use App\Models\Objective;
use App\Models\User;
use App\Models\ListenerLog;
use App\Http\Controllers\NotificationController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CheckObjectivesExistence extends Command
{
    protected $signature = 'objectives:check-existence';
    protected $description = 'Remind DG and PDG to define objectives if none exist';

    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
    }

    public function handle()
    {
        $startTime = microtime(true);
        Log::info("CheckObjectivesExistence: Début de la vérification de l'existence d'objectifs");

        try {
            $activeObjectives = Objective::where('is_active', true)->count();

            if ($activeObjectives > 0) {
                $message = "Des objectifs actifs existent déjà ($activeObjectives objectif(s))";
                Log::info("CheckObjectivesExistence: $message");
                $this->logExecution('skipped', $message, ['active_objectives_count' => $activeObjectives], $startTime);
                return 0;
            }

            // Notifier DG et PDG
            $managers = User::whereIn('role', ['dg', 'pdg'])->get();

            if ($managers->isEmpty()) {
                $message = "Aucun DG ou PDG trouvé";
                Log::warning("CheckObjectivesExistence: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $notificationsSent = 0;

            foreach ($managers as $manager) {
                $message = $this->getObjectivesMessage($manager->language);
                
                $request = new Request([
                    'recipient_id' => $manager->id,
                    'subject' => $manager->language === 'en' ? 'Objectives Definition Reminder' : 'Rappel Définition Objectifs',
                    'message' => $message
                ]);
                
                $this->notificationController->send($request);
                $notificationsSent++;
                
                Log::info("CheckObjectivesExistence: Notification envoyée au {$manager->role} {$manager->name} (ID: {$manager->id})");
            }

            $message = "Rappel envoyé à $notificationsSent manager(s) pour définir des objectifs";
            Log::info("CheckObjectivesExistence: $message");
            $this->logExecution('success', $message, ['notifications_sent' => $notificationsSent], $startTime);

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors de la vérification des objectifs: " . $e->getMessage();
            Log::error("CheckObjectivesExistence: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
    }

    private function getObjectivesMessage($language)
    {
        if ($language === 'en') {
            return "Reminder: No active objectives are currently defined for the company. Please consider setting strategic objectives to guide business growth and performance.";
        }
        
        return "Rappel : Aucun objectif actif n'est actuellement défini pour l'entreprise. Veuillez envisager de définir des objectifs stratégiques pour guider la croissance et les performances de l'entreprise.";
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'CheckObjectivesExistence',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}
