<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\ListenerLog;
use App\Http\Controllers\NotificationController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class SeasonalGreetings extends Command
{
    protected $signature = 'greetings:seasonal {--occasion=}';
    protected $description = 'Send seasonal greetings to all users';

    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
    }

    public function handle()
    {
        $startTime = microtime(true);
        $occasion = $this->option('occasion');
        
        Log::info("SeasonalGreetings: Début des vœux saisonniers - Occasion: $occasion");

        try {
            $users = User::all();

            if ($users->isEmpty()) {
                $message = "Aucun utilisateur trouvé";
                Log::warning("SeasonalGreetings: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $notificationsSent = 0;

            foreach ($users as $user) {
                $messageData = $this->getGreetingMessage($user->language, $occasion);
                
                $request = new Request([
                    'recipient_id' => $user->id,
                    'subject' => $messageData['subject'],
                    'message' => $messageData['message']
                ]);
                
                $this->notificationController->send($request);
                $notificationsSent++;
                
                Log::info("SeasonalGreetings: Vœux $occasion envoyés à {$user->name} (ID: {$user->id})");
            }

            $message = "Vœux $occasion envoyés à $notificationsSent utilisateur(s)";
            Log::info("SeasonalGreetings: $message");
            $this->logExecution('success', $message, ['occasion' => $occasion, 'notifications_sent' => $notificationsSent], $startTime);

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors de l'envoi des vœux saisonniers: " . $e->getMessage();
            Log::error("SeasonalGreetings: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
    }

    private function getGreetingMessage($language, $occasion)
    {
        $messages = [
            'labor-day' => [
                'fr' => [
                    'subject' => 'Joyeuse Fête du Travail',
                    'message' => 'Joyeuse Fête du Travail ! Nous vous remercions pour votre dévouement et votre contribution à la croissance de notre entreprise. Ensemble, nous continuons de bâtir un avenir prospère.'
                ],
                'en' => [
                    'subject' => 'Happy Labor Day',
                    'message' => 'Happy Labor Day! We thank you for your dedication and contribution to our company\'s growth. Together, we continue to build a prosperous future.'
                ]
            ],
            'christmas' => [
                'fr' => [
                    'subject' => 'Joyeux Noël',
                    'message' => 'Joyeux Noël ! Merci pour votre travail exceptionnel cette année. Que cette période de fêtes vous apporte joie et bonheur, et que l\'année à venir soit pleine de succès pour notre entreprise.'
                ],
                'en' => [
                    'subject' => 'Merry Christmas',
                    'message' => 'Merry Christmas! Thank you for your exceptional work this year. May this holiday season bring you joy and happiness, and may the coming year be full of success for our company.'
                ]
            ],
            'new-year' => [
                'fr' => [
                    'subject' => 'Bonne Année',
                    'message' => 'Bonne Année ! Nous vous remercions pour tout le travail accompli et nous nous réjouissons de poursuivre ensemble cette belle aventure entrepreneuriale. Que cette nouvelle année soit synonyme de croissance et de réussite pour notre entreprise.'
                ],
                'en' => [
                    'subject' => 'Happy New Year',
                    'message' => 'Happy New Year! We thank you for all the work accomplished and we look forward to continuing this beautiful entrepreneurial adventure together. May this new year be synonymous with growth and success for our company.'
                ]
            ],
            'april-fools' => [
                'fr' => [
                    'subject' => 'Prime Spéciale - Poisson d\'Avril',
                    'message' => 'Félicitations ! Vous avez reçu une prime spéciale de 20 000 FCFA ! 🎉... Poisson d\'Avril ! 😄 Merci pour votre bonne humeur et votre travail qui contribuent à l\'atmosphère positive de notre entreprise.'
                ],
                'en' => [
                    'subject' => 'Special Bonus - April Fools',
                    'message' => 'Congratulations! You have received a special bonus of 20,000 FCFA! 🎉... April Fools! 😄 Thank you for your good humor and work that contribute to the positive atmosphere of our company.'
                ]
            ]
        ];

        return $messages[$occasion][$language] ?? $messages[$occasion]['fr'];
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'SeasonalGreetings',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}
