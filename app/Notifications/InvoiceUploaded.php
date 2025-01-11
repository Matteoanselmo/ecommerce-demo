<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceUploaded extends Notification implements ShouldQueue {
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
            ->line('La fattura / ricevuta per il tuo ordine è stata caricata.')
            ->action('Visualizza fattura', $this->order->fattura)
            ->line('Grazie per aver scelto il nostro servizio!');
    }
}
