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
                'status' => ['confirmed', 'returned'][array_rand(['confirmed', 'returned'])], // Stato casuale tra 'confirmed' e 'returned'
                'shipping_number' => Str::random(10), // Genera un numero di spedizione casuale
                'order_date' => now()->subDays(rand(1, 30)), // Data ordine random tra oggi e 30 giorni fa
                'total_amount' => rand(1000, 5000) / 10, // Importo totale casuale decimale tra 100.0 e 500.0
                'user_id' => rand(1, 5), // Assumi che ci siano almeno 5 utenti
                'order_number' => $orderNumber++, // Assegna il numero di ordine incrementale
            ]);
        }
    }
}
