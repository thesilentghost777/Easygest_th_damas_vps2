<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\PaydayConfig;
use App\Models\Evaluation;
use App\Models\ListenerLog;
use App\Http\Controllers\NotificationController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CheckEmployeeEvaluations extends Command
{
    protected $signature = 'evaluations:check-missing';
    protected $description = 'Check if CP has evaluated employees before payday';

    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
    }

    public function handle()
    {
        $startTime = microtime(true);
        Log::info("CheckEmployeeEvaluations: Début de la vérification des évaluations des employés");

        try {
            $config = PaydayConfig::first();
            if (!$config) {
                $message = "Aucune configuration de jour de paie trouvée";
                Log::warning("CheckEmployeeEvaluations: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $today = Carbon::now('Africa/Douala');
            $salaryDay = $config->salary_day;
            $daysUntilPayday = $this->calculateDaysUntilPayday($today, $salaryDay);

            // Vérifier seulement les jours x-5, x-3, x-1
            if (!in_array($daysUntilPayday, [5, 3, 1])) {
                $message = "Pas un jour de vérification (jours restants: $daysUntilPayday)";
                Log::info("CheckEmployeeEvaluations: $message");
                $this->logExecution('skipped', $message, ['days_until_payday' => $daysUntilPayday], $startTime);
                return 0;
            }

            $employees = User::whereNot('secteur','administration')->get();
            $currentMonth = $today->format('Y-m');
            $unevaluatedEmployees = [];

            foreach ($employees as $employee) {
                $hasEvaluation = Evaluation::where('user_id', $employee->id)
                    ->whereYear('created_at', $today->year)
                    ->whereMonth('created_at', $today->month)
                    ->exists();

                if (!$hasEvaluation) {
                    $unevaluatedEmployees[] = $employee;
                }
            }

            if (empty($unevaluatedEmployees)) {
                $message = "Tous les employés ont été évalués ce mois-ci";
                Log::info("CheckEmployeeEvaluations: $message");
                $this->logExecution('skipped', $message, ['days_until_payday' => $daysUntilPayday], $startTime);
                return 0;
            }

            // Notifier les CP
            $chefProductions = User::where('role', 'dg')->get();
            $notificationsSent = 0;

            foreach ($chefProductions as $cp) {
                $message = $this->getEvaluationMessage($cp->language, count($unevaluatedEmployees), $daysUntilPayday);
                
                $request = new Request([
                    'recipient_id' => $cp->id,
                    'subject' => $cp->language === 'en' ? 'Employee Evaluation Reminder' : 'Rappel Évaluation Employés',
                    'message' => $message
                ]);
                
                $this->notificationController->send($request);
                $notificationsSent++;
                
                Log::info("CheckEmployeeEvaluations: Notification envoyée au dg {$cp->name} (ID: {$cp->id})");
            }

            $details = [
                'days_until_payday' => $daysUntilPayday,
                'unevaluated_count' => count($unevaluatedEmployees),
                'notifications_sent' => $notificationsSent
            ];

            $message = "Rappel envoyé à $notificationsSent CP(s) pour " . count($unevaluatedEmployees) . " employé(s) non évalué(s)";
            Log::info("CheckEmployeeEvaluations: $message");
            $this->logExecution('success', $message, $details, $startTime);

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors de la vérification des évaluations: " . $e->getMessage();
            Log::error("CheckEmployeeEvaluations: $message");
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

    private function getEvaluationMessage($language, $count, $daysLeft)
    {
        if ($language === 'en') {
            return "Reminder: $count employee(s) have not been evaluated this month. Payday is in $daysLeft days. Please complete evaluations.";
        }
        
        return "Rappel : $count employé(s) n'ont pas été évalués ce mois-ci. Le jour de paie est dans $daysLeft jours. Veuillez compléter les évaluations.";
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'CheckEmployeeEvaluations',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}