<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification implements ShouldQueue {
    use Queueable;

    public $order;
    public $user;

    public function __construct($order, $user) {
        $this->order = $order;
        $this->user = $user;
    }

    public function via($notifiable) {
        return ['mail'];
    }

    public function toMail($notifiable) {
        return (new MailMessage)
            ->subject('Nuovo ordine ricevuto')
            ->greeting('Ciao Admin,')
            ->line('È stato effettuato un nuovo ordine da ' . $this->user->name . '.')
            ->line('Numero ordine: ' . $this->order->order_number)
            ->line('Totale: €' . number_format($this->order->total_amount / 100, 2))
            ->action('Visualizza ordine', url('/admin/dashboard/orders/'));
    }
}
