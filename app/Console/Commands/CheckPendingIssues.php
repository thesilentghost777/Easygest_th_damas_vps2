<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\PaydayConfig;
use App\Models\ManquantTemporaire;
use App\Models\ListenerLog;
use App\Http\Controllers\MessageController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CheckPendingIssues extends Command
{
    protected $signature = 'issues:check-pending';
    protected $description = 'Check for pending employee issues before payday';

    protected $messageController;

    public function __construct(MessageController $messageController)
    {
        parent::__construct();
        $this->messageController = $messageController;
    }

    public function handle()
    {
        $startTime = microtime(true);
        Log::info("CheckPendingIssues: Début de la vérification des problèmes en attente");

        try {
            $config = PaydayConfig::first();
            if (!$config) {
                $message = "Aucune configuration de jour de paie trouvée";
                Log::warning("CheckPendingIssues: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $today = Carbon::now('Africa/Douala');
            $salaryDay = $config->salary_day;
            $daysUntilPayday = $this->calculateDaysUntilPayday($today, $salaryDay);

            // Vérifier seulement les jours x-5, x-3, x-1
            if (!in_array($daysUntilPayday, [5, 3, 1])) {
                $message = "Pas un jour de vérification (jours restants: $daysUntilPayday)";
                Log::info("CheckPendingIssues: $message");
                $this->logExecution('skipped', $message, ['days_until_payday' => $daysUntilPayday], $startTime);
                return 0;
            }

            // Vérifier les manquants en attente
            $pendingIssues = ManquantTemporaire::where('statut', 'en_attente')->get();

            if ($pendingIssues->isEmpty()) {
                $message = "Aucun problème en attente trouvé";
                Log::info("CheckPendingIssues: $message");
                $this->logExecution('skipped', $message, ['days_until_payday' => $daysUntilPayday], $startTime);
                return 0;
            }

            $details = [];
            foreach ($pendingIssues as $issue) {
                $employee = User::find($issue->employe_id);
                $details[] = [
                    'employe_id' => $issue->employe_id,
                    'nom' => $employee->name ?? 'Inconnu',
                    'montant' => $issue->montant,
                    'explication' => $issue->explication
                ];
            }

            // Signaler au DG
            $messageContent = "Alerte : " . count($pendingIssues) . " problème(s) d'employé(s) en attente de résolution avant le jour de paie (dans $daysUntilPayday jours). Veuillez traiter ces dossiers rapidement.";
            
            $signalementRequest = new Request([
                'message' => $messageContent,
                'category' => 'report'
            ]);
            
            $this->messageController->store_messageX($signalementRequest);

            $message = "Signalement envoyé au DG pour " . count($pendingIssues) . " manquant(s) non validé(s) en attente";
            Log::info("CheckPendingIssues: $message");
            $this->logExecution('success', $message, $details, $startTime);

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors de la vérification des problèmes en attente: " . $e->getMessage();
            Log::error("CheckPendingIssues: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
    }

    private function calculateDaysUntilPayday($today, $salaryDay)
    {
        $currentDay = $today->day;
        if ($currentDay <= $salaryDay) {
            return $salaryDay - $currentDay;
        } else {
            return $today->copy()->addMonth()->day($salaryDay)->diffInDays($today);
        }
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'CheckPendingIssues',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}
