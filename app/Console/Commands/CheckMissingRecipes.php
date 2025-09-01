<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Recipe;
use App\Models\ListenerLog;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckMissingRecipes extends Command
{
    protected $signature = 'recipes:check-missing';
    protected $description = 'Check for products without defined recipes';

    protected $notificationController;
    protected $messageController;

    public function __construct(NotificationController $notificationController, MessageController $messageController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
        $this->messageController = $messageController;
    }

    public function handle()
    {
        $startTime = microtime(true);
        Log::info("CheckMissingRecipes: Début de la vérification des recettes manquantes");

        try {
            // Récupérer tous les produits qui n'ont pas de recettes
            $productsWithoutRecipes = DB::table('Produit_fixes')
                ->leftJoin('recipes', function($join) {
                    $join->on('Produit_fixes.nom', '=', 'recipes.name')
                         ->where('recipes.active', '=', true);
                })
                ->whereNull('recipes.id')
                ->select('Produit_fixes.code_produit', 'Produit_fixes.nom')
                ->get();

            if ($productsWithoutRecipes->isEmpty()) {
                $message = "Tous les produits ont leurs recettes définies";
                Log::info("CheckMissingRecipes: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            // Notifier les CP
            $chefProductions = User::where('role', 'chef_production')->get();
            $notificationsSent = 0;

            foreach ($chefProductions as $cp) {
                $message = $this->getCPMessage($cp->language, count($productsWithoutRecipes));
                
                $request = new Request([
                    'recipient_id' => $cp->id,
                    'subject' => $cp->language === 'en' ? 'Missing Recipes Alert' : 'Alerte Recettes Manquantes',
                    'message' => $message
                ]);
                
                $this->notificationController->send($request);
                $notificationsSent++;
                
                Log::info("CheckMissingRecipes: Notification envoyée au CP {$cp->name} (ID: {$cp->id})");
            }

            // Signaler au DG
            $signalementMessage = "Alerte : " . count($productsWithoutRecipes) . " produit(s) n'ont pas de recettes définies. Ceci peut compliquer le travail des nouveaux producteurs.";
            
            $signalementRequest = new Request([
                'message' => $signalementMessage,
                'category' => 'report'
            ]);
            
            $this->messageController->store_message($signalementRequest);

            $details = [];
            foreach ($productsWithoutRecipes as $product) {
                $details[] = [
                    'code_produit' => $product->code_produit,
                    'nom' => $product->nom
                ];
            }

            $message = "Notifications envoyées à $notificationsSent CP(s) et signalement au DG pour " . count($productsWithoutRecipes) . " produit(s) sans recettes";
            Log::info("CheckMissingRecipes: $message");
            $this->logExecution('success', $message, $details, $startTime);

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors de la vérification des recettes manquantes: " . $e->getMessage();
            Log::error("CheckMissingRecipes: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
    }

    private function getCPMessage($language, $count)
    {
        if ($language === 'en') {
            return "Alert: $count product(s) do not have defined recipes. Please create recipes to help new producers and standardize production processes. This may be an error if, for example, the recipe name differs from the product name.";
        }
    
        return "Alerte : $count produit(s) n'ont pas de recettes définies. Veuillez créer des recettes pour aider les nouveaux producteurs et standardiser les processus de production. Ceci peut être une erreur si, par exemple, le nom de la recette est différent de celui du produit.";
    }
    

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'CheckMissingRecipes',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}
