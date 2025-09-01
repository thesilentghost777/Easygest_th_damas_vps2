<?php

namespace App\Console\Commands;

use App\Models\UserPin;
use App\Models\ListenerLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetUserPinFlags extends Command
{
    protected $signature = 'pins:reset-flags';
    protected $description = 'Reset user pin flags to false every hour';

    public function handle()
    {
        $startTime = microtime(true);
        Log::info("ResetUserPinFlags: Début de la réinitialisation des flags PIN");

        try {
            $updatedCount = UserPin::where('flag', true)
                ->update(['flag' => false]);

            if ($updatedCount === 0) {
                $message = "Aucun flag PIN à réinitialiser";
                Log::info("ResetUserPinFlags: $message");
                $this->logExecution('skipped', $message, [], $startTime);
            } else {
                $message = "Réinitialisation réussie de $updatedCount flag(s) PIN";
                Log::info("ResetUserPinFlags: $message");
                $this->logExecution('success', $message, ['updated_count' => $updatedCount], $startTime);
            }

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors de la réinitialisation des flags PIN: " . $e->getMessage();
            Log::error("ResetUserPinFlags: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'ResetUserPinFlags',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}
