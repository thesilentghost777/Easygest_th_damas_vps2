<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\ListenerLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AutoSetDepartureTime extends Command
{
    protected $signature = 'attendance:auto-set-departure';
    protected $description = 'Automatically set departure time for employees who marked arrival but not departure';

    public function handle()
    {
        $startTime = microtime(true);
        Log::info("AutoSetDepartureTime: Début de la mise à jour automatique des heures de départ");

        try {
            $today = Carbon::now('Africa/Douala');
            
            // Récupérer les employés qui ont marqué l'arrivée mais pas le départ aujourd'hui
            // en utilisant la table Horaire
            $employeesWithoutDeparture = DB::table('Horaire')
                ->whereDate('arrive', $today->toDateString())
                ->whereNotNull('arrive')
                ->whereNull('depart')
                ->get();

            if ($employeesWithoutDeparture->isEmpty()) {
                $message = "Aucun employé sans heure de départ marquée";
                Log::info("AutoSetDepartureTime: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $updatedCount = 0;
            $details = [];

            foreach ($employeesWithoutDeparture as $horaire) {
                $arrivalTime = Carbon::parse($horaire->arrive);
                $departureTime = null;

                // Si arrivée entre 0h et 14h -> départ à 15h
                if ($arrivalTime->hour >= 0 && $arrivalTime->hour <= 14) {
                    $departureTime = $today->copy()->setTime(15, 0, 0);
                }
                // Si arrivée entre 14h et 23h59 -> départ à 23h
                elseif ($arrivalTime->hour > 14 && $arrivalTime->hour <= 23) {
                    $departureTime = $today->copy()->setTime(23, 0, 0);
                }

                if ($departureTime) {
                    // Mise à jour de l'heure de départ dans la table Horaire
                    DB::table('Horaire')
                        ->where('id', $horaire->id)
                        ->update([
                            'depart' => $departureTime->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now('Africa/Douala')->format('Y-m-d H:i:s')
                        ]);

                    // Récupérer les informations de l'employé pour les logs
                    $employee = DB::table('users')->where('id', $horaire->employe)->first();
                    $employeeName = $employee ? $employee->name : 'Inconnu';

                    $details[] = [
                        'horaire_id' => $horaire->id,
                        'employe_id' => $horaire->employe,
                        'employe_name' => $employeeName,
                        'arrival_datetime' => $arrivalTime->format('Y-m-d H:i:s'),
                        'arrival_time' => $arrivalTime->format('H:i'),
                        'auto_departure_datetime' => $departureTime->format('Y-m-d H:i:s'),
                        'auto_departure_time' => $departureTime->format('H:i'),
                        'date' => $today->format('Y-m-d')
                    ];

                    $updatedCount++;
                    Log::info("AutoSetDepartureTime: Mise à jour heure de départ pour l'employé {$employeeName} (ID: {$horaire->employe}) - Arrivée: {$arrivalTime->format('H:i')}, Départ auto: {$departureTime->format('H:i')}");
                }
            }

            $message = "Mise à jour automatique de $updatedCount heure(s) de départ";
            Log::info("AutoSetDepartureTime: $message");
            $this->logExecution('success', $message, $details, $startTime);

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors de la mise à jour automatique des heures de départ: " . $e->getMessage();
            Log::error("AutoSetDepartureTime: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'AutoSetDepartureTime',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}