<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Objective;
use App\Http\Controllers\ObjectiveController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ObjectivesExpireCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'objectives:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier et mettre à jour le statut des objectifs (expirés ou accomplis)';

    /**
     * Instance du contrôleur des objectifs
     *
     * @var ObjectiveController
     */
    protected $objectiveController;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->objectiveController = new ObjectiveController();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Début de la vérification des objectifs...');
        Log::info('Début de la commande objectives:expire');

        $today = Carbon::today();
        
        // Récupérer tous les objectifs actifs
        $activeObjectives = Objective::where('is_active', true)->get();
        
        $this->info("Nombre d'objectifs actifs trouvés: " . $activeObjectives->count());
        Log::info('Objectifs actifs trouvés', ['count' => $activeObjectives->count()]);

        $expiredCount = 0;
        $completedCount = 0;
        $updatedCount = 0;

        foreach ($activeObjectives as $objective) {
            try {
                $this->info("Traitement de l'objectif ID: {$objective->id} - {$objective->title}");
                Log::info('Traitement objectif', [
                    'id' => $objective->id,
                    'title' => $objective->title,
                    'end_date' => $objective->end_date,
                    'target_amount' => $objective->target_amount
                ]);

                // Mettre à jour la progression de l'objectif avant les vérifications
                $this->updateObjectiveProgress($objective);
                $updatedCount++;

                // Recharger l'objectif pour avoir les dernières données
                $objective->refresh();

                $endDate = Carbon::parse($objective->end_date)->endOfDay();
                
                // Récupérer la dernière progression
                $latestProgress = $objective->progress()->latest()->first();
                
                $currentAmount = $latestProgress ? $latestProgress->current_amount : 0;
                $currentProfit = $latestProgress ? $latestProgress->profit : 0;
                $progressPercentage = $latestProgress ? $latestProgress->progress_percentage : 0;

                Log::info('Données de progression', [
                    'objective_id' => $objective->id,
                    'current_amount' => $currentAmount,
                    'current_profit' => $currentProfit,
                    'progress_percentage' => $progressPercentage,
                    'goal_type' => $objective->goal_type
                ]);

                // Vérifier si l'objectif est accompli
                $isCompleted = false;
                
                if ($objective->goal_type === 'revenue') {
                    $isCompleted = $currentAmount >= $objective->target_amount;
                } else { // profit
                    $isCompleted = $currentProfit >= $objective->target_amount;
                }

                // Marquer comme accompli si ce n'est pas déjà fait
                if ($isCompleted && !$objective->is_achieved) {
                    $objective->update(['is_achieved' => true]);
                    $completedCount++;
                    $this->info("✅ Objectif {$objective->id} marqué comme ACCOMPLI");
                    Log::info('Objectif marqué comme accompli', ['id' => $objective->id]);
                }

                // Vérifier si l'objectif est expiré
                if ($today->gt($endDate)) {
                    $objective->update(['is_active' => false]);
                    $expiredCount++;
                    $this->warn("⏰ Objectif {$objective->id} marqué comme EXPIRÉ");
                    Log::info('Objectif marqué comme expiré', [
                        'id' => $objective->id,
                        'end_date' => $endDate->format('Y-m-d'),
                        'today' => $today->format('Y-m-d')
                    ]);
                }

                // Afficher le statut actuel
                if ($objective->is_achieved) {
                    $this->line("   Status: ✅ Accompli ({$progressPercentage}%)");
                } elseif (!$objective->is_active) {
                    $this->line("   Status: ⏰ Expiré");
                } else {
                    $this->line("   Status: 🔄 En cours ({$progressPercentage}%)");
                }

            } catch (\Exception $e) {
                $this->error("Erreur lors du traitement de l'objectif {$objective->id}: " . $e->getMessage());
                Log::error('Erreur traitement objectif', [
                    'objective_id' => $objective->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        // Résumé des résultats
        $this->info("\n=== RÉSUMÉ ===");
        $this->info("📊 Objectifs traités: {$updatedCount}");
        $this->info("✅ Objectifs accomplis: {$completedCount}");
        $this->info("⏰ Objectifs expirés: {$expiredCount}");

        Log::info('Fin de la commande objectives:expire', [
            'updated_count' => $updatedCount,
            'completed_count' => $completedCount,
            'expired_count' => $expiredCount
        ]);

        $this->info('Vérification des objectifs terminée avec succès!');
        return Command::SUCCESS;
    }

    /**
     * Mettre à jour la progression d'un objectif
     * (Version simplifiée de la méthode du ObjectiveController)
     *
     * @param Objective $objective
     * @return void
     */
    private function updateObjectiveProgress(Objective $objective)
    {
        try {
            // Utiliser la méthode existante du contrôleur si elle est accessible
            if (method_exists($this->objectiveController, 'updateObjectiveProgress')) {
                // Si la méthode est publique
                $this->objectiveController->updateObjectiveProgress($objective);
            } else {
                // Sinon, utiliser reflection pour accéder à la méthode privée
                $reflection = new \ReflectionClass($this->objectiveController);
                $method = $reflection->getMethod('updateObjectiveProgress');
                $method->setAccessible(true);
                $method->invoke($this->objectiveController, $objective);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de progression', [
                'objective_id' => $objective->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}