<?php

namespace App\Console\Commands;

use App\Models\PaydayConfig;
use App\Models\Prime;
use App\Models\User;
use App\Models\ListenerLog;
use App\Http\Controllers\NotificationController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckBonusesBeforePayday extends Command
{
    protected $signature = 'bonuses:check-before-payday';
    protected $description = 'Remind DG to define bonuses before payday';

    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
    }

    public function handle()
    {
        $startTime = microtime(true);
        Log::info("CheckBonusesBeforePayday: Début de la vérification des primes avant le jour de paie");

        try {
            $config = PaydayConfig::first();
            if (!$config) {
                $message = "Aucune configuration de jour de paie trouvée";
                Log::warning("CheckBonusesBeforePayday: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $today = Carbon::now('Africa/Douala');
            $salaryDay = $config->salary_day;
            $daysUntilPayday = $this->calculateDaysUntilPayday($today, $salaryDay);

            // Vérifier seulement les jours x-5, x-3, x-1
            if (!in_array($daysUntilPayday, [5, 3, 1])) {
                $message = "Pas un jour de vérification (jours restants: $daysUntilPayday)";
                Log::info("CheckBonusesBeforePayday: $message");
                $this->logExecution('skipped', $message, ['days_until_payday' => $daysUntilPayday], $startTime);
                return 0;
            }

            $hasBonuses = Prime::where('montant', '>', 0)->exists();
            if ($hasBonuses) {
                $message = "Des primes sont déjà définies";
                Log::info("CheckBonusesBeforePayday: $message");
                $this->logExecution('skipped', $message, ['days_until_payday' => $daysUntilPayday], $startTime);
                return 0;
            }

            // Notifier le DG
            $dg = User::where('role', 'dg')->first();

            if (!$dg) {
                $message = "Aucun DG trouvé";
                Log::warning("CheckBonusesBeforePayday: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $message = $this->getBonusMessage($dg->language, $daysUntilPayday);
            
            $request = new Request([
                'recipient_id' => $dg->id,
                'subject' => $dg->language === 'en' ? 'Bonus Definition Reminder' : 'Rappel Définition Primes',
                'message' => $message
            ]);
            
            $this->notificationController->send($request);

            $message = "Rappel envoyé au DG pour définir les primes ($daysUntilPayday jours avant payday)";
            Log::info("CheckBonusesBeforePayday: $message");
            $this->logExecution('success', $message, ['days_until_payday' => $daysUntilPayday], $startTime);

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors de la vérification des primes: " . $e->getMessage();
            Log::error("CheckBonusesBeforePayday: $message");
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

    private function getBonusMessage($language, $daysLeft)
    {
        if ($language === 'en') {
            return "Reminder: Payday is in $daysLeft days. Please define employee bonuses if you haven't already done so to ensure they are included in the salary calculations.";
        }
        
        return "Rappel : Le jour de paie est dans $daysLeft jours. Veuillez définir les primes des employés si ce n'est pas encore fait pour qu'elles soient incluses dans les calculs de salaire.";
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'CheckBonusesBeforePayday',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}
