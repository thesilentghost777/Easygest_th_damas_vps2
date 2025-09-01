<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Daily_assignments;
use App\Models\MatiereRecommander;
use App\Models\ListenerLog;
use App\Http\Controllers\NotificationController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CheckMissingRecommendedMaterials extends Command
{
    protected $signature = 'materials:check-missing-recommended';
    protected $description = 'Check for productions without recommended materials and notify CP';

    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
    }

    public function handle()
    {
        $startTime = microtime(true);
        Log::info("CheckMissingRecommendedMaterials: Début de la vérification des matières recommandées manquantes");

        try {
            $today = Carbon::now('Africa/Douala');

            // Récupérer toutes les assignations du jour
            $todayAssignments = Daily_assignments::whereDate('assignment_date', $today->format('Y-m-d'))->get();

            if ($todayAssignments->isEmpty()) {
                $message = "Aucune assignation trouvée pour aujourd'hui";
                Log::info("CheckMissingRecommendedMaterials: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $missingRecommendations = [];

            foreach ($todayAssignments as $assignment) {
                $hasRecommendation = MatiereRecommander::where('produit', $assignment->produit)->exists();
                
                if (!$hasRecommendation) {
                    $missingRecommendations[] = [
                        'assignment_id' => $assignment->id,
                        'produit_id' => $assignment->produit,
                        'producteur_id' => $assignment->producteur,
                        'quantite_attendue' => $assignment->expected_quantity
                    ];
                    
                    Log::info("CheckMissingRecommendedMaterials: Produit {$assignment->produit} sans matières recommandées");
                }
            }

            if (empty($missingRecommendations)) {
                $message = "Toutes les productions ont leurs matières recommandées définies";
                Log::info("CheckMissingRecommendedMaterials: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            // Notifier tous les CP
            $chefProductions = User::where('role', 'chef_production')
                ->get();

            $notificationsSent = 0;

            foreach ($chefProductions as $cp) {
                $message = $this->getNotificationMessage($cp->language, count($missingRecommendations));
                
                $request = new Request([
                    'recipient_id' => $cp->id,
                    'subject' => $cp->language === 'en' ? 'Missing Recommended Materials' : 'Matières Recommandées Manquantes',
                    'message' => $message
                ]);
                
                $this->notificationController->send($request);
                $notificationsSent++;
                
                Log::info("CheckMissingRecommendedMaterials: Notification envoyée au CP {$cp->name} (ID: {$cp->id})");
            }

            $message = "Notifications envoyées à $notificationsSent CP(s) pour " . count($missingRecommendations) . " production(s) sans matières recommandées";
            Log::info("CheckMissingRecommendedMaterials: $message");
            $this->logExecution('success', $message, $missingRecommendations, $startTime);

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors de la vérification des matières recommandées: " . $e->getMessage();
            Log::error("CheckMissingRecommendedMaterials: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
    }

    private function getNotificationMessage($language, $count)
    {
        if ($language === 'en') {
            return "Alert: $count production(s) today do not have recommended materials defined. Please review and define recommended materials for better production guidance.";
        }
        
        return "Alerte : $count production(s) aujourd'hui n'ont pas de matières recommandées définies. Veuillez réviser et définir les matières recommandées pour faciliter la production.";
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'CheckMissingRecommendedMaterials',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}
