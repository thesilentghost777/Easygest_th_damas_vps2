<?php

namespace App\Console\Commands;

use App\Models\PaydayConfig;
use App\Models\User;
use App\Models\LoanRequest;
use App\Models\ListenerLog;
use App\Http\Controllers\NotificationController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CheckLoanRepaymentsBeforePayday extends Command
{
    protected $signature = 'loans:check-repayments-before-payday';
    protected $description = 'Remind DG to define loan repayment amounts before payday';

    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
    }

    public function handle()
    {
        $startTime = microtime(true);
        Log::info("CheckLoanRepaymentsBeforePayday: Début de la vérification des remboursements de dettes avant le jour de paie");

        try {
            $config = PaydayConfig::first();
            if (!$config) {
                $message = "Aucune configuration de jour de paie trouvée";
                Log::warning("CheckLoanRepaymentsBeforePayday: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $today = Carbon::now('Africa/Douala');
            $salaryDay = $config->salary_day;
            $daysUntilPayday = $this->calculateDaysUntilPayday($today, $salaryDay);

            // Vérifier seulement les jours x-5, x-3, x-1
            if (!in_array($daysUntilPayday, [5, 3, 1])) {
                $message = "Pas un jour de vérification (jours restants: $daysUntilPayday)";
                Log::info("CheckLoanRepaymentsBeforePayday: $message");
                $this->logExecution('skipped', $message, ['days_until_payday' => $daysUntilPayday], $startTime);
                return 0;
            }

            // Récupérer les employés avec des prêts approuvés
            $employeesWithLoans = User::whereIn('id', function ($query) {
                $query->select('user_id')
                      ->from('loan_requests')
                      ->where('status', 'approved');
            })->get();

            if ($employeesWithLoans->isEmpty()) {
                $message = "Aucun employé avec des dettes en cours";
                Log::info("CheckLoanRepaymentsBeforePayday: $message");
                $this->logExecution('skipped', $message, ['days_until_payday' => $daysUntilPayday], $startTime);
                return 0;
            }

            // Notifier le DG
            $dg = User::where('role', 'dg')->first();

            if (!$dg) {
                $message = "Aucun DG trouvé";
                Log::warning("CheckLoanRepaymentsBeforePayday: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $details = [];
            foreach ($employeesWithLoans as $employee) {
                $totalLoanAmount = LoanRequest::where('user_id', $employee->id)
                    ->where('status', 'approved')
                    ->sum('amount');
                
                $details[] = [
                    'employee_id' => $employee->id,
                    'nom' => $employee->name,
                    'total_debt' => $totalLoanAmount
                ];
            }

            $message = $this->getLoanMessage($dg->language, count($employeesWithLoans), $daysUntilPayday);
            
            $request = new Request([
                'recipient_id' => $dg->id,
                'subject' => $dg->language === 'en' ? 'Loan Repayment Definition Reminder' : 'Rappel Définition Remboursements Dettes',
                'message' => $message
            ]);
            
            $this->notificationController->send($request);

            $message = "Rappel envoyé au DG pour " . count($employeesWithLoans) . " employé(s) avec dettes ($daysUntilPayday jours avant payday)";
            Log::info("CheckLoanRepaymentsBeforePayday: $message");
            $this->logExecution('success', $message, $details, $startTime);

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors de la vérification des remboursements: " . $e->getMessage();
            Log::error("CheckLoanRepaymentsBeforePayday: $message");
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

    private function getLoanMessage($language, $count, $daysLeft)
    {
        if ($language === 'en') {
            return "Reminder: Payday is in $daysLeft days. $count employee(s) have outstanding loans. Please define repayment amounts to be deducted from their salaries.";
        }
        
        return "Rappel : Le jour de paie est dans $daysLeft jours. $count employé(s) ont des dettes en cours. Veuillez définir les montants de remboursement à déduire de leurs salaires.";
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'CheckLoanRepaymentsBeforePayday',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}
