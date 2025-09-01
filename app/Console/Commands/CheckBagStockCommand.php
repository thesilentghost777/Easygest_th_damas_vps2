<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Bag;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;

class CheckBagStockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bags:check-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier le stock des sacs et envoyer un signalement au PDG si le stock est bas';

    /**
     * Les contrôleurs pour les notifications et messages
     */
    protected $notificationController;
    protected $messageController;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(NotificationController $notificationController, MessageController $messageController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
        $this->messageController = $messageController;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $startTime = microtime(true);
        
        Log::info('CheckBagStockCommand: Début de l\'exécution de la vérification du stock des sacs');
        $this->info('Vérification du stock des sacs...');

        try {
            // Récupérer tous les sacs avec un stock bas
            Log::info('CheckBagStockCommand: Recherche des sacs avec stock bas');
            $lowStockBags = Bag::where('stock_quantity', '<=', \DB::raw('alert_threshold'))
                               ->get();

            $totalBags = Bag::count();
            Log::info("CheckBagStockCommand: {$totalBags} sacs au total dans la base de données");
            Log::info("CheckBagStockCommand: {$lowStockBags->count()} sacs détectés avec stock bas");

            if ($lowStockBags->isEmpty()) {
                Log::info('CheckBagStockCommand: Aucun sac en stock bas détecté - Fin de l\'exécution');
                $this->info('Aucun sac en stock bas détecté.');
                return Command::SUCCESS;
            }

            // Log des sacs en stock bas
            foreach ($lowStockBags as $bag) {
                Log::warning("CheckBagStockCommand: Sac en stock bas détecté", [
                    'id' => $bag->id,
                    'name' => $bag->name,
                    'stock_quantity' => $bag->stock_quantity,
                    'alert_threshold' => $bag->alert_threshold,
                    'price' => $bag->price
                ]);
            }

            // Préparer le message de signalement
            Log::info('CheckBagStockCommand: Construction du message de signalement');
            $message = $this->buildStockAlertMessage($lowStockBags);

            // Envoyer le signalement
            Log::info('CheckBagStockCommand: Tentative d\'envoi du signalement au PDG');
            $signalementRequest = new Request([
                'message' => $message,
                'category' => 'report'
            ]);

            $this->messageController->store_messageX($signalementRequest);

            Log::info('CheckBagStockCommand: Signalement envoyé avec succès au PDG', [
                'bags_count' => $lowStockBags->count(),
                'message_length' => strlen($message)
            ]);

            $this->info('Signalement envoyé avec succès au PDG.');
            $this->info("Nombre de sacs en stock bas : " . $lowStockBags->count());
            
            // Afficher la liste des sacs concernés
            $this->table(
                ['ID', 'Nom', 'Stock actuel', 'Seuil d\'alerte'],
                $lowStockBags->map(function ($bag) {
                    return [
                        $bag->id,
                        $bag->name,
                        $bag->stock_quantity,
                        $bag->alert_threshold
                    ];
                })->toArray()
            );

        } catch (\Exception $e) {
            Log::error('CheckBagStockCommand: Erreur lors de l\'exécution de la commande', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            $this->error('Erreur lors de l\'envoi du signalement : ' . $e->getMessage());
            return Command::FAILURE;
        }

        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        Log::info("CheckBagStockCommand: Fin de l'exécution - Durée: {$executionTime}ms");

        return Command::SUCCESS;
    }

    /**
     * Construire le message d'alerte pour le stock bas
     *
     * @param \Illuminate\Database\Eloquent\Collection $lowStockBags
     * @return string
     */
    private function buildStockAlertMessage($lowStockBags)
    {
        Log::info('CheckBagStockCommand: Construction du message d\'alerte', [
            'bags_count' => $lowStockBags->count()
        ]);

        $message = "🚨 ALERTE STOCK - " . now()->format('d/m/Y à H:i') . "\n\n";
        $message .= "Bonjour Monsieur le DG,\n\n";
        $message .= "Nous vous informons que " . $lowStockBags->count() . " référence(s) de sacs ";
        $message .= ($lowStockBags->count() > 1) ? "sont en stock bas :\n\n" : "est en stock bas :\n\n";

        foreach ($lowStockBags as $bag) {
            $message .= "• {$bag->name}\n";
            $message .= "  - Stock actuel : {$bag->stock_quantity} unité(s)\n";
            $message .= "  - Seuil d'alerte : {$bag->alert_threshold} unité(s)\n";
            $message .= "  - Prix unitaire : {$bag->price} FCFA\n\n";
        }

        $message .= "Veuillez envisager un réapprovisionnement pour éviter toute rupture de stock.\n\n";
        $message .= "Cordialement,\n";
        $message .= "Système de gestion des stocks";

        Log::info('CheckBagStockCommand: Message d\'alerte construit', [
            'message_length' => strlen($message),
            'bags_included' => $lowStockBags->pluck('name')->toArray()
        ]);

        return $message;
    }
}