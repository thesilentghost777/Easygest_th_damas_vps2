<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Daily_assignments;
use App\Models\ReposConges;
use App\Models\ListenerLog;
use App\Http\Controllers\NotificationController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CheckMissingExpectedProduction extends Command
{
    protected $signature = 'production:check-missing-expected';
    protected $description = 'Check for producers without expected production assignments';

    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
    }

    public function handle()
    {
        $startTime = microtime(true);
        Log::info("CheckMissingExpectedProduction: Début de la vérification des productions attendues manquantes");

        try {
            $today = Carbon::now('Africa/Douala');
            $todayFrench = $this->getDayInFrench($today->dayOfWeek);
            Log::info("Jour - $todayFrench");
            // Récupérer tous les producteurs qui ne sont pas en repos
            $producteurs = User::where('secteur', 'production')
                ->where('role', 'patissier')
                ->orWhere('role', 'boulanger')
                ->whereNotIn('id', function ($query) use ($todayFrench) {
                    $query->select('employe_id')
                          ->from('repos_conges')
                          ->where('jour', $todayFrench);
                })
                ->get();

            if ($producteurs->isEmpty()) {
                $message = "Aucun producteur en service aujourd'hui";
                Log::info("CheckMissingExpectedProduction: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $producteursWithoutExpectedProduction = [];

            foreach ($producteurs as $producteur) {
                $hasExpectedProduction = Daily_assignments::where('producteur', $producteur->id)
                    ->whereDate('assignment_date', $today->format('Y-m-d'))
                    ->where('expected_quantity', '>', 0)
                    ->exists();

                if (!$hasExpectedProduction) {
                    $producteursWithoutExpectedProduction[] = $producteur;
                    Log::info("CheckMissingExpectedProduction: Producteur {$producteur->name} (ID: {$producteur->id}) sans production attendue définie");
                }
            }

            if (empty($producteursWithoutExpectedProduction)) {
                $message = "Tous les producteurs en service ont leurs productions attendues définies";
                Log::info("CheckMissingExpectedProduction: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            // Notifier tous les CP
            $chefProductions = User::where('role', 'chef_production')
                ->get();

            $notificationsSent = 0;
            $details = [];

            foreach ($chefProductions as $cp) {
                $message = $this->getNotificationMessage($cp->language, count($producteursWithoutExpectedProduction));
                
                $request = new Request([
                    'recipient_id' => $cp->id,
                    'subject' => $cp->language === 'en' ? 'Missing Expected Production' : 'Productions Attendues Manquantes',
                    'message' => $message
                ]);
                
                $this->notificationController->send($request);
                $notificationsSent++;
                
                Log::info("CheckMissingExpectedProduction: Notification envoyée au CP {$cp->name} (ID: {$cp->id})");
            }

            foreach ($producteursWithoutExpectedProduction as $producteur) {
                $details[] = [
                    'producteur_id' => $producteur->id,
                    'nom' => $producteur->name,
                    'secteur' => $producteur->secteur
                ];
            }

            $message = "Notifications envoyées à $notificationsSent CP(s) pour " . count($producteursWithoutExpectedProduction) . " producteur(s) sans production attendue";
            Log::info("CheckMissingExpectedProduction: $message");
            $this->logExecution('success', $message, $details, $startTime);

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors de la vérification des productions attendues: " . $e->getMessage();
            Log::error("CheckMissingExpectedProduction: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
    }

    private function getDayInFrench($dayOfWeek)
    {
        $days = [
            0 => 'dimanche', 1 => 'lundi', 2 => 'mardi', 3 => 'mercredi',
            4 => 'jeudi', 5 => 'vendredi', 6 => 'samedi'
        ];
        
        return $days[$dayOfWeek] ?? 'unknown';
    }

    private function getNotificationMessage($language, $count)
    {
        if ($language === 'en') {
            return "Alert: $count producer(s) scheduled to work today do not have expected production quantities defined. Please set production targets immediately.";
        }
        
        return "Alerte : $count producteur(s) censé(s) travailler aujourd'hui n'ont pas de quantités de production attendues définies. Veuillez définir les objectifs de production immédiatement.";
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'CheckMissingExpectedProduction',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}
