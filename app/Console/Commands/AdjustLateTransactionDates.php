<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdjustLateTransactionDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:adjust-late-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ajuste les dates de vente des transactions créées entre 00h et 02h du matin à la veille à 23h35';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('=== DÉBUT DE LA COMMANDE AdjustLateTransactionDates ===');
        
        try {
            // Date du jour courant
            $today = Carbon::today();
            
            // Plage horaire à vérifier (00h00 à 02h00 du jour courant)
            $startTime = $today->copy()->startOfDay(); // 00:00:00
            $endTime = $today->copy()->addHours(2);    // 02:00:00
            
            // Date de la veille à 23h35
            $yesterdayAt2335 = $today->copy()->subDay()->setTime(23, 35, 0);
            
            Log::info("Paramètres de la commande", [
                'date_execution' => now()->format('Y-m-d H:i:s'),
                'plage_debut' => $startTime->format('Y-m-d H:i:s'),
                'plage_fin' => $endTime->format('Y-m-d H:i:s'),
                'nouvelle_date_vente' => $yesterdayAt2335->format('Y-m-d'),
                'fuseau_horaire' => config('app.timezone')
            ]);
            
            $this->info("Vérification des transactions créées entre {$startTime->format('Y-m-d H:i:s')} et {$endTime->format('Y-m-d H:i:s')}");
            
            // Rechercher les enregistrements créés entre 00h et 02h du jour courant
            $transactionsToUpdate = DB::table('transaction_ventes')
                ->whereBetween('created_at', [$startTime, $endTime])
                ->get();
            
            Log::info("Résultat de la recherche", [
                'nombre_transactions_trouvees' => $transactionsToUpdate->count(),
                'plage_recherche' => "{$startTime->format('Y-m-d H:i:s')} - {$endTime->format('Y-m-d H:i:s')}"
            ]);
            
            if ($transactionsToUpdate->isEmpty()) {
                $this->info('Aucune transaction trouvée dans la plage horaire spécifiée.');
                Log::info('Aucune transaction à traiter - Fin de la commande');
                return 0;
            }
            
            $this->info("Nombre de transactions trouvées : " . $transactionsToUpdate->count());
            
            // Log détaillé des transactions trouvées
            $transactionDetails = [];
            foreach ($transactionsToUpdate as $transaction) {
                $transactionDetails[] = [
                    'id' => $transaction->id,
                    'produit' => $transaction->produit,
                    'serveur' => $transaction->serveur,
                    'quantite' => $transaction->quantite,
                    'prix' => $transaction->prix,
                    'date_vente_actuelle' => $transaction->date_vente,
                    'created_at' => $transaction->created_at,
                    'type' => $transaction->type
                ];
            }
            
            Log::info("Détails des transactions à modifier", [
                'transactions' => $transactionDetails
            ]);
            
            // Mettre à jour les dates de vente
            $updatedCount = DB::table('transaction_ventes')
                ->whereBetween('created_at', [$startTime, $endTime])
                ->update([
                    'date_vente' => $yesterdayAt2335->format('Y-m-d'),
                    'updated_at' => now()
                ]);
            
            Log::info("Mise à jour effectuée", [
                'nombre_transactions_mises_a_jour' => $updatedCount,
                'nouvelle_date_vente' => $yesterdayAt2335->format('Y-m-d'),
                'heure_mise_a_jour' => now()->format('Y-m-d H:i:s')
            ]);
            
            $this->info("Nombre de transactions mises à jour : {$updatedCount}");
            $this->info("Nouvelle date de vente appliquée : {$yesterdayAt2335->format('Y-m-d')}");
            
            // Log détaillé pour debug
            $this->line('--- Détails des transactions mises à jour ---');
            foreach ($transactionsToUpdate as $transaction) {
                $this->line("ID: {$transaction->id} - Créée le: {$transaction->created_at} - Ancienne date_vente: {$transaction->date_vente}");
            }
            
            // Log de résumé final
            Log::info("Résumé de l'exécution", [
                'statut' => 'SUCCESS',
                'transactions_trouvees' => $transactionsToUpdate->count(),
                'transactions_mises_a_jour' => $updatedCount,
                'duree_execution' => now()->diffInSeconds($startTime) . ' secondes',
                'date_execution' => now()->format('Y-m-d H:i:s')
            ]);
            
            Log::info('=== FIN RÉUSSIE DE LA COMMANDE AdjustLateTransactionDates ===');
            
            return 0;
            
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'exécution de AdjustLateTransactionDates", [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'date_execution' => now()->format('Y-m-d H:i:s')
            ]);
            
            $this->error('Erreur lors de l\'exécution de la commande : ' . $e->getMessage());
            
            Log::info('=== FIN EN ERREUR DE LA COMMANDE AdjustLateTransactionDates ===');
            
            return 1;
        }
    }
}