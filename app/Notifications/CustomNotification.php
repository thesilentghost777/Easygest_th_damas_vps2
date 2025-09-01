<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class CustomNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;
    public $tries = 3;
    public $timeout = 60;
    public $delay;

    /**
     * Create a new notification instance.
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->onQueue('notifications');
        
        // Set priority based on data
        if (isset($data['priority'])) {
            switch ($data['priority']) {
                case 'high':
                    $this->onQueue('high-priority');
                    break;
                case 'low':
                    $this->onQueue('low-priority');
                    break;
                default:
                    $this->onQueue('notifications');
            }
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        Log::info('Préparation de l\'email de notification pour ' . $notifiable->email);
        
        return (new MailMessage)
            ->subject($this->data['subject'])
            ->greeting('Salut!')
            ->line('Message: ' . $this->data['message'])
            ->line('Envoyé par: ' . $this->data['sender_name'])
            ->line('Date: ' . $this->data['created_at'])
            ->action('Voir toutes les notifications', url('/notifications'))
            ->line('Merci d\'utiliser notre application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return array_merge($this->data, [
            'queued_at' => now(),
            'priority' => $this->data['priority'] ?? 'normal'
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Notification failed', [
            'exception' => $exception->getMessage(),
            'data' => $this->data
        ]);
    }
}
