<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AdminOrderPushNotification extends Notification implements ShouldQueue {
    use Queueable;

    public $order;

    public function __construct($order) {
        $this->order = $order;
    }

    public function via($notifiable) {
        return ['database'];
    }

    public function toArray($notifiable) {
        return [
            'title' => 'Nuovo ordine ricevuto',
            'message' => 'L\'ordine #' . $this->order->order_number . ' è stato effettuato.',
            'url' => '/admin/dashboard/orders/',
        ];
    }
}
