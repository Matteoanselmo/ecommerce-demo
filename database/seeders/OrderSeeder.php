<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        $orderNumber = 100000; // Numero di ordine iniziale

        for ($i = 1; $i <= 41; $i++) {
            DB::table('orders')->insert([
                'status' => ['confirmed', 'in_progress', 'on_delivery', 'delivered', 'returned'][array_rand(['confirmed', 'in_progress', 'on_delivery', 'delivered', 'returned'])], // Stato casuale
                'shipping_number' => Str::random(10), // Numero di spedizione casuale
                'order_date' => now()->subDays(rand(1, 30)), // Data ordine random tra oggi e 30 giorni fa
                'total_amount' => rand(1000, 5000), // Importo totale casuale decimale tra 100.0 e 500.0
                'user_id' => rand(1, 5), // Assumi che ci siano almeno 5 utenti
                'order_number' => $orderNumber++, // Numero di ordine incrementale

                // Nuovi campi
                'data' => now()->subDays(rand(5, 35))->format('Y-m-d'), // Data casuale tra 5 e 35 giorni fa
                'shipped_in' => now()->addDays(rand(1, 10))->format('Y-m-d'), // Data spedizione casuale nei prossimi 10 giorni
                'payment' => ['credit_card', 'paypal', 'bank_transfer'][array_rand(['credit_card', 'paypal', 'bank_transfer'])], // Metodo di pagamento casuale
                'details' => Str::random(20), // Dettagli ordine casuali
                'fattura' => null, // Lasciato vuoto se non si conosce un URL valido
            ]);
        }
    }
}
