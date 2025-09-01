<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\ListenerLog;
use App\Http\Controllers\NotificationController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class DailyGuidanceReminders extends Command
{
    protected $signature = 'guidance:daily-reminders {--feature=}';
    protected $description = 'Send daily guidance reminders for application features';

    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
    }

    public function handle()
    {
        $startTime = microtime(true);
        $feature = $this->option('feature');
        
        Log::info("DailyGuidanceReminders: Début des rappels de guidage journalier - Feature: $feature");

        try {
            $method = 'handle' . ucfirst(str_replace('-', '', $feature));
            
            if (!method_exists($this, $method)) {
                $message = "Méthode $method non trouvée pour la fonctionnalité $feature";
                Log::error("DailyGuidanceReminders: $message");
                $this->logExecution('failed', $message, [], $startTime);
                return 1;
            }

            return $this->$method($startTime);
        } catch (\Exception $e) {
            $message = "Erreur lors des rappels de guidage: " . $e->getMessage();
            Log::error("DailyGuidanceReminders: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
    }

    private function handleFlashRecap($startTime)
    {
        $chefProductions = User::where('role', 'chef_production')->get();
        return $this->sendReminders($chefProductions, 'flash_recap', $startTime);
    }

    private function handleAssignationJour($startTime)
    {
        $chefProductions = User::where('role', 'chef_production')->get();
        return $this->sendReminders($chefProductions, 'assignation_jour', $startTime);
    }

    private function handleAchatDepense($startTime)
    {
        $chefProductions = User::where('role', 'chef_production')->get();
        return $this->sendReminders($chefProductions, 'achat_depense', $startTime);
    }

    private function handleTableProduction($startTime)
    {
        $chefProductions = User::where('role', 'chef_production')->get();
        return $this->sendReminders($chefProductions, 'table_production', $startTime);
    }

    private function handlePerformanceProduit($startTime)
    {
        $users = User::whereIn('role', ['chef_production', 'dg', 'pdg'])->get();
        return $this->sendReminders($users, 'performance_produit', $startTime);
    }

    private function handleSuggererProduction($startTime)
    {
        $dgs = User::where('role', 'dg')->get();
        return $this->sendReminders($dgs, 'suggerer_production', $startTime);
    }

    private function handleFinance($startTime)
    {
        $managers = User::whereIn('role', ['dg', 'pdg'])->get();
        return $this->sendReminders($managers, 'finance', $startTime);
    }

    private function handleStatistiques($startTime)
    {
        $users = User::whereIn('role', ['chef_production', 'dg', 'pdg'])->get();
        return $this->sendReminders($users, 'statistiques', $startTime);
    }

    private function handleRessourceHumaine($startTime)
    {
        $managers = User::whereIn('role', ['dg', 'pdg'])->get();
        return $this->sendReminders($managers, 'ressource_humaine', $startTime);
    }

    private function handleSalaires($startTime)
    {
        $dgs = User::where('role', 'dg')->get();
        return $this->sendReminders($dgs, 'salaires', $startTime);
    }

    private function handleSherlockCopilot($startTime)
    {
        $managers = User::whereIn('role', ['dg', 'pdg'])->get();
        return $this->sendReminders($managers, 'sherlock_copilot', $startTime);
    }

    private function handleConseillerSherlock($startTime)
    {
        $managers = User::whereIn('role', ['dg', 'pdg'])->get();
        return $this->sendReminders($managers, 'conseiller_sherlock', $startTime);
    }

    private function sendReminders($users, $featureType, $startTime)
    {
        if ($users->isEmpty()) {
            $message = "Aucun utilisateur trouvé pour $featureType";
            Log::info("DailyGuidanceReminders: $message");
            $this->logExecution('skipped', $message, [], $startTime);
            return 0;
        }

        $notificationsSent = 0;

        foreach ($users as $user) {
            $messageData = $this->getFeatureMessage($user->language, $featureType);
            
            $request = new Request([
                'recipient_id' => $user->id,
                'subject' => $messageData['subject'],
                'message' => $messageData['message']
            ]);
            
            $this->notificationController->send($request);
            $notificationsSent++;
            
            Log::info("DailyGuidanceReminders: Notification $featureType envoyée à {$user->name} (ID: {$user->id})");
        }

        $message = "Rappels $featureType envoyés à $notificationsSent utilisateur(s)";
        Log::info("DailyGuidanceReminders: $message");
        $this->logExecution('success', $message, ['feature' => $featureType, 'notifications_sent' => $notificationsSent], $startTime);
        
        return 0;
    }

    private function getFeatureMessage($language, $featureType)
    {
        $messages = [
            'flash_recap' => [
                'fr' => [
                    'subject' => 'Rappel Flash Recap Production',
                    'message' => 'Rappel : Veuillez consulter la fonctionnalité "Flash Recap Production" pour connaître le bilan général d\'hier et identifier les potentiels vols de matières, gaspillages ou erreurs d\'assignation.'
                ],
                'en' => [
                    'subject' => 'Flash Recap Production Reminder',
                    'message' => 'Reminder: Please check the "Flash Recap Production" feature to know yesterday\'s overall summary and identify potential material theft, waste, or assignment errors.'
                ]
            ],
            'assignation_jour' => [
                'fr' => [
                    'subject' => 'Rappel Assignation du Jour',
                    'message' => 'Rappel : Veuillez consulter la fonctionnalité "Assignation du Jour" pour vous assurer qu\'il n\'y a aucune erreur d\'assignation.'
                ],
                'en' => [
                    'subject' => 'Daily Assignment Reminder',
                    'message' => 'Reminder: Please check the "Daily Assignment" feature to ensure there are no assignment errors.'
                ]
            ],
            'achat_depense' => [
                'fr' => [
                    'subject' => 'Rappel Achat et Dépense',
                    'message' => 'Rappel : Veuillez consulter la fonctionnalité "Achat et Dépense" pour enregistrer toutes les transactions de la journée et pouvoir facilement rendre compte au DG.'
                ],
                'en' => [
                    'subject' => 'Purchase and Expense Reminder',
                    'message' => 'Reminder: Please check the "Purchase and Expense" feature to record all daily transactions and easily report to the DG.'
                ]
            ],
            'table_production' => [
                'fr' => [
                    'subject' => 'Rappel Table de Production',
                    'message' => 'Rappel : Veuillez consulter la fonctionnalité "Table de Production" pour la configurer de sorte que la récupération de matière première soit possible.'
                ],
                'en' => [
                    'subject' => 'Production Table Reminder',
                    'message' => 'Reminder: Please check the "Production Table" feature to configure it so that raw material recovery is possible.'
                ]
            ],
            'performance_produit' => [
                'fr' => [
                    'subject' => 'Rappel Performance Produit',
                    'message' => 'Rappel : Veuillez consulter la fonctionnalité "Performance Produit" qui vous permet d\'étudier les produits et de prendre des décisions stratégiques.'
                ],
                'en' => [
                    'subject' => 'Product Performance Reminder',
                    'message' => 'Reminder: Please check the "Product Performance" feature that allows you to study products and make strategic decisions.'
                ]
            ],
            'suggerer_production' => [
                'fr' => [
                    'subject' => 'Rappel Suggérer une Production',
                    'message' => 'Rappel : Veuillez consulter la fonctionnalité "Suggérer une Production" qui vous permet de suggérer des productions pour les jours à venir et pour le jour courant.'
                ],
                'en' => [
                    'subject' => 'Suggest Production Reminder',
                    'message' => 'Reminder: Please check the "Suggest Production" feature that allows you to suggest productions for upcoming days and the current day.'
                ]
            ],
            'finance' => [
                'fr' => [
                    'subject' => 'Rappel Finance',
                    'message' => 'Rappel : Veuillez consulter la fonctionnalité "Finance" qui vous permet d\'étudier les finances et voir si tout va bien.'
                ],
                'en' => [
                    'subject' => 'Finance Reminder',
                    'message' => 'Reminder: Please check the "Finance" feature that allows you to study finances and see if everything is going well.'
                ]
            ],
            'statistiques' => [
                'fr' => [
                    'subject' => 'Rappel Statistiques',
                    'message' => 'Rappel : Veuillez consulter les "Statistiques" qui vous permettent d\'étudier l\'entreprise et voir si tout va bien.'
                ],
                'en' => [
                    'subject' => 'Statistics Reminder',
                    'message' => 'Reminder: Please check the "Statistics" that allow you to study the company and see if everything is going well.'
                ]
            ],
            'ressource_humaine' => [
                'fr' => [
                    'subject' => 'Rappel Ressources Humaines',
                    'message' => 'Rappel : Veuillez consulter les "Ressources Humaines" qui vous permettent d\'étudier les performances de vos employés.'
                ],
                'en' => [
                    'subject' => 'Human Resources Reminder',
                    'message' => 'Reminder: Please check "Human Resources" that allow you to study your employees\' performance.'
                ]
            ],
            'salaires' => [
                'fr' => [
                    'subject' => 'Rappel Salaires',
                    'message' => 'Rappel : Veuillez consulter la fonctionnalité "Salaires" pour définir les salaires des employés ou pour les augmentations de salaires.'
                ],
                'en' => [
                    'subject' => 'Salaries Reminder',
                    'message' => 'Reminder: Please check the "Salaries" feature to define employee salaries or salary increases.'
                ]
            ],
            'sherlock_copilot' => [
                'fr' => [
                    'subject' => 'Rappel Sherlock Copilot',
                    'message' => 'Rappel : Veuillez consulter la fonctionnalité "Sherlock Copilot" pour poser des questions en rapport avec l\'entreprise et obtenir des réponses.'
                ],
                'en' => [
                    'subject' => 'Sherlock Copilot Reminder',
                    'message' => 'Reminder: Please check the "Sherlock Copilot" feature to ask questions about the company and get answers.'
                ]
            ],
            'conseiller_sherlock' => [
                'fr' => [
                    'subject' => 'Rappel Conseiller Sherlock',
                    'message' => 'Rappel : Veuillez consulter la fonctionnalité "Conseiller Sherlock" pour avoir une analyse détaillée de l\'état de l\'entreprise et des conseils basés sur l\'IA.'
                ],
                'en' => [
                    'subject' => 'Sherlock Advisor Reminder',
                    'message' => 'Reminder: Please check the "Sherlock Advisor" feature for a detailed analysis of the company\'s state and AI-based advice.'
                ]
            ]
        ];

        return $messages[$featureType][$language] ?? $messages[$featureType]['fr'];
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'DailyGuidanceReminders',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}
