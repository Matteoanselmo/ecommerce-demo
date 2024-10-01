<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupportTicket;

class SupportTicketSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $tickets = [
            // Ticket per user_id 6
            [
                'user_id' => 6,
                'product' => 'Scarpe Antinfortunistiche Modello X',
                'subject' => 'Problema con la taglia',
                'description' => 'La taglia ordinata non corrisponde a quella indicata sul sito.',
                'status' => 'Aperto',
            ],
            [
                'user_id' => 6,
                'product' => 'Guanti Protettivi Modello Y',
                'subject' => 'Richiesta di reso',
                'description' => 'Il cliente desidera restituire i guanti perché non soddisfano le sue aspettative.',
                'status' => 'In Attesa',
            ],
            [
                'user_id' => 6,
                'product' => 'Casco da lavoro Modello Z',
                'subject' => 'Prodotto difettoso',
                'description' => 'Il casco presenta un difetto visibile nella parte anteriore.',
                'status' => 'Chiuso',
            ],

            // Ticket per user_id 7
            [
                'user_id' => 7,
                'product' => 'Giacca Impermeabile Modello A',
                'subject' => 'Domanda sulla disponibilità',
                'description' => 'Il cliente chiede quando sarà disponibile la giacca nella taglia L.',
                'status' => 'Aperto',
            ],
            [
                'user_id' => 7,
                'product' => 'Scarpe Antinfortunistiche Modello X',
                'subject' => 'Problema di consegna',
                'description' => 'Le scarpe non sono ancora state consegnate, nonostante il tracking mostri lo stato "consegnato".',
                'status' => 'In Attesa',
            ],

            // Ticket per user_id 8
            [
                'user_id' => 8,
                'product' => 'Occhiali di protezione Modello B',
                'subject' => 'Problema con il pagamento',
                'description' => 'Il cliente non riesce a completare il pagamento tramite carta di credito.',
                'status' => 'Aperto',
            ],
            [
                'user_id' => 8,
                'product' => 'Tuta Protettiva Modello C',
                'subject' => 'Domanda sulle caratteristiche',
                'description' => 'Il cliente chiede maggiori informazioni sui materiali utilizzati nella tuta.',
                'status' => 'Chiuso',
            ]
        ];

        foreach ($tickets as $ticket) {
            SupportTicket::create($ticket);
        }
    }
}
