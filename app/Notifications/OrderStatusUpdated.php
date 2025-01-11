<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification implements ShouldQueue {
    use Queueable;

    protected $order;

    public function __construct($order) {
        $this->order = $order;
    }

    public function via($notifiable) {
        return ['mail'];
    }

    public function toMail($notifiable) {
        return (new MailMessage)
            ->greeting('Ciao ' . $notifiable->name . ',')
            ->line('Lo stato del tuo ordine è cambiato.')
            ->line('Controlla la tua dashboard per maggiori dettagli.')
            ->action('Vai alla dashboard', url('/dashboard/orders/' . $this->order->id))
            ->line('Grazie per aver scelto il nostro servizio!');
    }
}
