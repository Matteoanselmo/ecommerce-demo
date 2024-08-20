<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i = 1; $i <= 6; $i++) {
            DB::table('orders')->insert([
                'status' => 'confirmed', // Utilizza vari stati se necessario
                'shipping_number' => Str::random(10), // Genera un numero di spedizione casuale
                'order_date' => now()->subDays(rand(1, 30)), // Data ordine random tra oggi e 30 giorni fa
                'total_amount' => rand(100, 500), // Importo totale casuale tra 100 e 500
                'user_id' => rand(1, 5) // Assumi che ci siano almeno 5 utenti
            ]);
        }
    }
}
