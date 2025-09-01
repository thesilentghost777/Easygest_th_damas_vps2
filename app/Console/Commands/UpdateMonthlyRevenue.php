<?php

namespace App\Console\Commands;

use App\Models\Complexe;
use App\Models\ListenerLog;
use App\Models\Transaction;
use App\Http\Controllers\NotificationController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UpdateMonthlyRevenue extends Command
{
    protected $signature = 'revenue:update-monthly';
    protected $description = 'Update monthly and annual revenue and send alerts for changes';

    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
    }

    public function handle()
    {
        $startTime = microtime(true);
        Log::info("UpdateMonthlyRevenue: Début de la mise à jour des revenus mensuels");

        try {
            $complexe = Complexe::first();
            
            if (!$complexe) {
                $message = "Aucun complexe trouvé pour la mise à jour des revenus";
                Log::warning("UpdateMonthlyRevenue: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $currentMonth = Carbon::now('Africa/Douala')->month;
            $currentYear = Carbon::now('Africa/Douala')->year;

            // Calculer le nouveau revenu mensuel (moyenne avec le précédent)
            $oldMonthlyRevenue = $complexe->revenu_mensuel;
            $currentMonthlyRevenue = $this->calculateCurrentMonthRevenue();
            $newMonthlyRevenue = ($oldMonthlyRevenue + $currentMonthlyRevenue) / 2;

            // Calculer le revenu annuel (cumul des mois de l'année)
            $newAnnualRevenue = $complexe->revenu_annuel;
            Log::info("Revenue annuel 1 : $newAnnualRevenue" );

            if ($currentMonth === 1) {
                // Si c'est le premier mois de l'année, on réinitialise le revenu annuel
                $newAnnualRevenue = $currentMonthlyRevenue;
            } else {
                // Sinon, on ajoute le revenu du mois courant à l'annuel
                $newAnnualRevenue += $currentMonthlyRevenue;
                Log::info("Revenue annuel 2 : $newAnnualRevenue" );
            }

            // Calculer la différence pour l'alerte
            $difference = $newMonthlyRevenue - $oldMonthlyRevenue;
            $changeType = $difference > 0 ? 'augmentation' : 'baisse';
            $changeAmount = abs($difference);

            // Mettre à jour le complexe
            $complexe->update([
                'revenu_mensuel' => $newMonthlyRevenue,
                'revenu_annuel' => $newAnnualRevenue
            ]);

            // Envoyer alerte si changement significatif (plus de 50000 FCFA)
            if ($changeAmount > 50000) {
                $this->sendChangeAlert($changeType, $changeAmount, $newMonthlyRevenue);
            }

            $details = [
                'ancien_revenu_mensuel' => $oldMonthlyRevenue,
                'nouveau_revenu_mensuel' => $newMonthlyRevenue,
                'revenu_annuel' => $newAnnualRevenue,
                'difference' => $difference,
                'type_changement' => $changeType,
                'revenu_mois_courant' => $currentMonthlyRevenue
            ];

            $message = "Mise à jour réussie des revenus - Revenu mensuel: {$newMonthlyRevenue} FCFA, Différence: {$difference} FCFA";
            Log::info("UpdateMonthlyRevenue: $message");
            $this->logExecution('success', $message, $details, $startTime);

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors de la mise à jour des revenus: " . $e->getMessage();
            Log::error("UpdateMonthlyRevenue: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
    }

    /**
     * Calcule le revenu du mois courant basé sur les transactions 'income'
     */
    private function calculateCurrentMonthRevenue()
    {
        $currentDate = Carbon::now('Africa/Douala');
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        try {
            $monthlyRevenue = Transaction::where('type', 'income')
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            Log::info("UpdateMonthlyRevenue: Revenu calculé pour le mois courant: {$monthlyRevenue} FCFA");
            
            return $monthlyRevenue ?? 0;
        } catch (\Exception $e) {
            Log::error("UpdateMonthlyRevenue: Erreur lors du calcul du revenu mensuel: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Calcule le revenu annuel cumulé basé sur les transactions 'income'
     */
    private function calculateAnnualRevenue($year)
    {
        $startOfYear = Carbon::create($year, 1, 1, 0, 0, 0, 'Africa/Douala');
        $endOfYear = Carbon::create($year, 12, 31, 23, 59, 59, 'Africa/Douala');

        try {
            $annualRevenue = Transaction::where('type', 'income')
                ->whereBetween('date', [$startOfYear, $endOfYear])
                ->sum('amount');

            Log::info("UpdateMonthlyRevenue: Revenu calculé pour l'année {$year}: {$annualRevenue} FCFA");
            
            return $annualRevenue ?? 0;
        } catch (\Exception $e) {
            Log::error("UpdateMonthlyRevenue: Erreur lors du calcul du revenu annuel: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Calcule le revenu d'un mois spécifique (utile pour les statistiques)
     */
    private function calculateMonthRevenue($year, $month)
    {
        $startOfMonth = Carbon::create($year, $month, 1, 0, 0, 0, 'Africa/Douala');
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        try {
            $monthRevenue = Transaction::where('type', 'income')
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            return $monthRevenue ?? 0;
        } catch (\Exception $e) {
            Log::error("UpdateMonthlyRevenue: Erreur lors du calcul du revenu pour {$month}/{$year}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtient les statistiques détaillées des revenus mensuels de l'année
     */
    private function getMonthlyRevenueBreakdown($year)
    {
        try {
            $monthlyBreakdown = Transaction::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(amount) as revenue')
            )
            ->where('type', 'income')
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'))
            ->orderBy('month')
            ->get();

            return $monthlyBreakdown->pluck('revenue', 'month')->toArray();
        } catch (\Exception $e) {
            Log::error("UpdateMonthlyRevenue: Erreur lors de la récupération du détail mensuel: " . $e->getMessage());
            return [];
        }
    }

    private function sendChangeAlert($type, $amount, $newRevenue)
    {
        $users = \App\Models\User::whereIn('role', ['dg', 'pdg'])->get();

        foreach ($users as $user) {
            $message = $this->getChangeMessage($user->language, $type, $amount, $newRevenue);
            
            $request = new Request([
                'recipient_id' => $user->id,
                'subject' => $user->language === 'en' ? 'Revenue Change Alert' : 'Alerte Changement Revenus',
                'message' => $message
            ]);
            
            $this->notificationController->send($request);
            
            Log::info("UpdateMonthlyRevenue: Alerte envoyée à {$user->name} (ID: {$user->id})");
        }
    }

    private function getChangeMessage($language, $type, $amount, $newRevenue)
    {
        if ($language === 'en') {
            return "Revenue Alert: Monthly revenue shows a {$type} of " . number_format($amount, 0, ',', ' ') . " FCFA. New monthly average: " . number_format($newRevenue, 0, ',', ' ') . " FCFA.";
        }
        
        return "Alerte Revenus: Le revenu mensuel montre une {$type} de " . number_format($amount, 0, ',', ' ') . " FCFA. Nouvelle moyenne mensuelle: " . number_format($newRevenue, 0, ',', ' ') . " FCFA.";
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'UpdateMonthlyRevenue',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}