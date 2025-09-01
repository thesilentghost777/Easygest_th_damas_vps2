<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\ReposConges;
use App\Models\ListenerLog;
use App\Http\Controllers\NotificationController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CheckEmployeeRestDays extends Command
{
    protected $signature = 'employees:check-rest-days';
    protected $description = 'Remind CP to define rest days for employees who do not have them';

    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
    }

    public function handle()
    {
        $startTime = microtime(true);
        Log::info("CheckEmployeeRestDays: Début de la vérification des jours de repos des employés");

        try {
            // Récupérer les employés qui n'ont pas de jours de repos définis
            $employeesWithoutRestDays = User::whereNot('secteur', 'administration')
            ->whereNotIn('id', function ($query) {
                $query->select('employe_id')->from('repos_conges');
            })->get();

            if ($employeesWithoutRestDays->isEmpty()) {
                $message = "Tous les employés ont leurs jours de repos définis";
                Log::info("CheckEmployeeRestDays: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            // Notifier tous les CP
            $chefProductions = User::where('role', 'chef_production')->get();

            if ($chefProductions->isEmpty()) {
                $message = "Aucun chef de production trouvé";
                Log::warning("CheckEmployeeRestDays: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $notificationsSent = 0;
            $details = [];

            foreach ($employeesWithoutRestDays as $employee) {
                $details[] = [
                    'employee_id' => $employee->id,
                    'nom' => $employee->name,
                    'secteur' => $employee->secteur
                ];
            }

            foreach ($chefProductions as $cp) {
                $message = $this->getRestDaysMessage($cp->language, count($employeesWithoutRestDays));
                
                $request = new Request([
                    'recipient_id' => $cp->id,
                    'subject' => $cp->language === 'en' ? 'Employee Rest Days Reminder' : 'Rappel Jours de Repos Employés',
                    'message' => $message
                ]);
                
                $this->notificationController->send($request);
                $notificationsSent++;
                
                Log::info("CheckEmployeeRestDays: Notification envoyée au CP {$cp->name} (ID: {$cp->id})");
            }

            $message = "Rappel envoyé à $notificationsSent CP(s) pour " . count($employeesWithoutRestDays) . " employé(s) sans jours de repos";
            Log::info("CheckEmployeeRestDays: $message");
            $this->logExecution('success', $message, $details, $startTime);

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors de la vérification des jours de repos: " . $e->getMessage();
            Log::error("CheckEmployeeRestDays: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
    }

    private function getRestDaysMessage($language, $count)
    {
        if ($language === 'en') {
            return "Reminder: $count employee(s) do not have their rest days or vacation days defined. Please define rest days or vacation periods for these employees.";
        }
        
        return "Rappel : $count employé(s) n'ont pas leurs jours de repos ou congés définis. Veuillez définir les jours de repos ou périodes de congés pour ces employés.";
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'CheckEmployeeRestDays',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}
