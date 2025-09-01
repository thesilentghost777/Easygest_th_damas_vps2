<?php

namespace App\Console\Commands;

use App\Models\Matiere;
use App\Models\User;
use App\Models\ListenerLog;
use App\Http\Controllers\NotificationController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CheckMaterialsThresholds extends Command
{
    protected $signature = 'materials:check-thresholds';
    protected $description = 'Check materials that are below minimum threshold and notify managers';

    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
    }

    public function handle()
    {
        $startTime = microtime(true);
        Log::info("CheckMaterialsThresholds: Début de la vérification des seuils de matières");

        try {
            // Récupérer les matières en dessous du seuil minimum
            $lowStockMaterials = Matiere::whereRaw('quantite <= quantite_seuil')
                ->where('quantite_seuil', '>', 0)
                ->get();

            if ($lowStockMaterials->isEmpty()) {
                $message = "Aucune matière en dessous du seuil minimum";
                Log::info("CheckMaterialsThresholds: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            // Notifier DG et CP
            $managers = User::whereIn('role', ['dg', 'chef_production'])->get();

            if ($managers->isEmpty()) {
                $message = "Aucun manager trouvé pour notification";
                Log::warning("CheckMaterialsThresholds: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $notificationsSent = 0;
            $details = [];

            foreach ($lowStockMaterials as $material) {
                $details[] = [
                    'material_id' => $material->id,
                    'nom' => $material->nom,
                    'quantite_disponible' => $material->quantite_disponible,
                    'seuil_minimum' => $material->seuil_minimum,
                    'unite' => $material->unite
                ];
            }

            foreach ($managers as $manager) {
                foreach ($lowStockMaterials as $material) {

                $message = $this->getThresholdMessage($manager->language, $material);

                $request = new Request([
                    'recipient_id' => $manager->id,
                    'subject' => $manager->language === 'en' ? 'Low Stock Alert' : 'Alerte Stock Faible',
                    'message' => $message
                ]);
                
                $this->notificationController->send($request);
                $notificationsSent++;
                
                Log::info("CheckMaterialsThresholds: Notification envoyée au {$manager->role} {$manager->name} (ID: {$manager->id})");
                Log::info("CheckMaterialsThresholds: Détails de la matière -> ID: {$material->id}, Nom: {$material->nom}, Quantité Disponible: {$material->quantite_disponible}, Seuil Minimum: {$material->seuil_minimum}, Unité: {$material->unite}");
                }
                }

            $message = "Alerte envoyée à $notificationsSent manager(s) pour " . count($lowStockMaterials) . " matière(s) en stock faible";
            Log::info("CheckMaterialsThresholds: $message");
            $this->logExecution('success', $message, $details, $startTime);

            return 0;
            
        } catch (\Exception $e) {
            $message = "Erreur lors de la vérification des seuils: " . $e->getMessage();
            Log::error("CheckMaterialsThresholds: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
        
    }

    private function getThresholdMessage($language, $Materials)
    {
        if ($language === 'en') {
            return "Alert: The Material $Materials->nom is below minimum stock threshold. Please consider restocking to avoid production disruptions.";
        }

        return "Alerte : la matière -> $Materials->nom est en dessous du seuil minimum de stock. Veuillez envisager un réapprovisionnement pour éviter les interruptions de production.";
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'CheckMaterialsThresholds',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}
