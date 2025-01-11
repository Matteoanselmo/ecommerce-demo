<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderThankYouNotification extends Notification implements ShouldQueue {
    use Queueable;

    public $order;
    public $products;

    public function __construct($order, $products) {
        $this->order = $order;
        $this->products = $products;
    }

    public function via($notifiable) {
        return ['mail'];
    }

    public function toMail($notifiable) {
        return (new MailMessage)
            ->subject('Grazie per il tuo ordine!')
            ->greeting('Ciao ' . $notifiable->name . ',')
            ->line('Grazie per aver effettuato un ordine! Ecco i dettagli:')
            ->line('Numero ordine: ' . $this->order->order_number)
            ->line('Totale: €' . number_format($this->order->total_amount / 100, 2))
            ->line('Prodotti:')
            ->line(collect($this->products)->map(fn($p) => $p['name'] . ' x ' . $p['quantity'])->join(', '))
            ->line('Ti ringraziamo per averci scelto!');
    }
}
