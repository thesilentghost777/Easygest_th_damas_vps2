<?php

namespace App\Console\Commands;

use App\Models\PaydayConfig;
use App\Models\Salaire;
use App\Models\AvanceSalaire;
use App\Models\ListenerLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PayrollPaymentRelease extends Command
{
    protected $signature = 'payroll:release-payments';
    protected $description = 'Release salary and advance salary payments on configured days';

    public function handle()
    {
        $startTime = microtime(true);
        Log::info("PayrollPaymentRelease: Début du processus de déblocage des paiements");

        try {
            $config = PaydayConfig::first();
            if (!$config) {
                $message = "Aucune configuration de jour de paie trouvée";
                Log::warning("PayrollPaymentRelease: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $today = Carbon::now('Africa/Douala')->day;
            $currentHour = Carbon::now('Africa/Douala')->hour;
            
            Log::info("PayrollPaymentRelease: Jour actuel: $today, Heure: $currentHour");
            Log::info("PayrollPaymentRelease: Configuration - Jour salaire: {$config->salary_day}, Jour avance: {$config->advance_day}");

            $actions = [];

            if ($today == $config->salary_day) {
                $salaryCount = $this->releaseSalaries();
                $actions[] = "Salaires débloqués: $salaryCount";
                Log::info("PayrollPaymentRelease: $salaryCount salaires ont été débloqués");
            }

            if (empty($actions)) {
                $message = "Aucune action requise - Pas le bon jour";
                Log::info("PayrollPaymentRelease: $message");
                $this->logExecution('skipped', $message, ['today' => $today, 'hour' => $currentHour], $startTime);
            } else {
                $message = "Déblocage effectué avec succès";
                Log::info("PayrollPaymentRelease: $message - " . implode(', ', $actions));
                $this->logExecution('success', $message, $actions, $startTime);
            }

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors du déblocage des paiements: " . $e->getMessage();
            Log::error("PayrollPaymentRelease: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
    }

    private function releaseSalaries(): int
    {
        return DB::transaction(function () {
            $count = Salaire::where('flag', true)
                ->orWhere('retrait_demande', true)
                ->orWhere('retrait_valide', true)
                ->update([
                    'flag' => false,
                    'retrait_demande' => false,
                    'retrait_valide' => false
                ]);
            
            Log::info("PayrollPaymentRelease: Mise à jour de $count entrées de salaires");
            return $count;
        });
    }

    private function releaseAdvances(): int
    {
        return 0;
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'PayrollPaymentRelease',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}
