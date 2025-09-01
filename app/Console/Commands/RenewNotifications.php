<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\DatabaseNotification;
use Carbon\Carbon;

class RenewNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:renew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renouvelle les notifications programmées pour re-notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Recherche des notifications à renouveler...');
        
        $now = Carbon::now();
        
        $notifications = DatabaseNotification::whereNotNull('renew_at')
                    ->where('renew_at', '<=', $now)
                    ->get();
                    
        if ($notifications->isEmpty()) {
            Log::info('Aucune notification à renouveler.');
            return 0;
        }
        
        Log::info('Renouvellement de ' . $notifications->count() . ' notifications...');
        
        foreach ($notifications as $notification) {
            // Créer une copie de la notification
            $newNotification = new DatabaseNotification();
            $newNotification->id = (string) \Illuminate\Support\Str::uuid();
            $newNotification->type = $notification->type;
            $newNotification->notifiable_type = $notification->notifiable_type;
            $newNotification->notifiable_id = $notification->notifiable_id;
            $newNotification->data = $notification->data;
            $newNotification->created_at = now();
            $newNotification->updated_at = now();
            $newNotification->save();
            
            // Marquer l'ancienne notification comme traitée
            $notification->update([
                'read_at' => now(),
                'processed' => true,
                'renew_at' => null,
                'renew_days' => null
            ]);
            
            $this->line('Notification #' . $notification->id . ' renouvelée avec succès.');
        }
        
        Log::info('Toutes les notifications ont été renouvelées avec succès.');
        
        return 0;
    }
}
