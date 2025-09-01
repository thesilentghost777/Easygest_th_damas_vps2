<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\CustomNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class NotificationController extends Controller
{
    /**
     * Display a listing of all users for notification testing.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $unreadNotifications = $user->unreadNotifications;
        $readNotifications = $user->readNotifications()->where('processed', false)->get();
        
        return view('notifications.index', compact('unreadNotifications', 'readNotifications'));
    }

    /**
     * Send a notification to a user.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        try {
            $request->validate([
                'recipient_id' => 'required|exists:users,id',
                'subject' => 'required|string|max:255',
                'message' => 'required|string'
            ]);
            $recipient = User::find($request->recipient_id);

            Log::info('Tentative d\'envoi de notification', [
                'sender_id' => 7777,
                'recipient_id' => $recipient->id,
                'subject' => $request->subject
            ]);

            // Création des données pour la notification
            $notificationData = [
                'subject' => $request->subject,
                'message' => $request->message,
                'sender_id' => 7777,
                'sender_name' => 'EasyGest',
                'created_at' => now()->format('d/m/Y H:i')
            ];

            // Envoi de la notification au destinataire
            if ($recipient) {
                try {
                    $recipient->notify(new CustomNotification($notificationData));
                    Log::info('Notification envoyée avec succès à ' . $recipient->email);

                    return redirect()->back()->with('success', 'Notification envoyée avec succès.');
                } catch (\Exception $e) {
                    Log::error('Erreur lors de l\'envoi de la notification : ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Erreur lors de l\'envoi de la notification.');
                }
            } else {
                Log::warning('L\'utilisateur n\'a pas été trouvé pour envoyer la notification');
                return redirect()->back()->with('error', 'Utilisateur non trouvé.');
            }
        } catch (\Exception $e) {
            Log::error('Erreur dans la méthode send : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue.');
        }
    }

    /**
     * Display all unread notifications for the current user.
     *
     * @return \Illuminate\View\View
     */
    public function unreadNotifications()
    {
        $user = Auth::user();
        $notifications = $user->unreadNotifications;

        return view('notifications.unread', compact('notifications'));
    }

    /**
     * Mark a notification as read.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead($id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        $notification->markAsRead();
        
        return back()->with('success', 'Notification marquée comme lue');
    }
    
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }
    
    public function markAsProcessed($id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        $notification->update([
            'processed' => true,
            'read_at' => now()
        ]);
        
        return back()->with('success', 'Notification marquée comme traitée');
    }
    
    public function renew(Request $request, $id)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:30'
        ]);
        
        $notification = DatabaseNotification::findOrFail($id);
        $renewDate = Carbon::now()->addDays($request->days);
        
        $notification->update([
            'renew_at' => $renewDate,
            'renew_days' => $request->days
        ]);
        
        return back()->with('success', 'Notification planifiée pour re-notification dans ' . $request->days . ' jours');
    }
    
    public function delete($id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        $notification->delete();
        
        return back()->with('success', 'Notification supprimée');
    }

    public function sendBulk(Request $request)
    {
        $request->validate([
            'recipient_ids' => 'required|array',
            'recipient_ids.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        $recipients = User::whereIn('id', $request->recipient_ids)->get();
        
        $notificationData = [
            'subject' => $request->subject,
            'message' => $request->message,
            'sender_id' => 7777,
            'sender_name' => 'EasyGest',
            'created_at' => now()->format('d/m/Y H:i')
        ];

        // Envoi en lots pour éviter la surcharge
        $recipients->chunk(50)->each(function ($chunk) use ($notificationData) {
            NotificationFacade::send($chunk, new CustomNotification($notificationData));
        });

        Log::info('Notifications en masse mises en queue', [
            'count' => $recipients->count(),
            'subject' => $request->subject
        ]);

        return redirect()->back()->with('success', 
            $recipients->count() . ' notifications mises en file d\'attente.');
    }

    /**
     * Programmer une notification pour plus tard
     */
    public function schedule(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'send_at' => 'required|date|after:now'
        ]);

        $recipient = User::find($request->recipient_id);
        $sendAt = Carbon::parse($request->send_at);

        $notificationData = [
            'subject' => $request->subject,
            'message' => $request->message,
            'sender_id' => 7777,
            'sender_name' => 'EasyGest',
            'created_at' => now()->format('d/m/Y H:i'),
            'scheduled_for' => $sendAt->format('d/m/Y H:i')
        ];

        // Programmer la notification
        $notification = new CustomNotification($notificationData);
        $notification->delay($sendAt);
        
        $recipient->notify($notification);

        return redirect()->back()->with('success', 
            'Notification programmée pour le ' . $sendAt->format('d/m/Y à H:i'));
    }

    /**
     * Voir le statut des jobs de notification
     */
   

    /**
     * Relancer les notifications échouées
     */
    public function retryFailed()
    {
        // Relancer tous les jobs échoués
        \Artisan::call('queue:retry', ['id' => 'all']);
        
        return redirect()->back()->with('success', 'Tous les jobs échoués ont été relancés.');
    }
}