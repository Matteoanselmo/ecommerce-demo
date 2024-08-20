<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Esempio di record per la tabella pivot order_product
        $orderProducts = [
            [
                'order_id' => 1, // Assicurati che questo ordine esista
                'product_id' => 1, // Assicurati che questo prodotto esista
                'price_at_order' => 19.99,
                'order_quantity' => 3
            ],
            [
                'order_id' => 1,
                'product_id' => 3,
                'price_at_order' => 30.00,
                'order_quantity' => 2
            ],
            [
                'order_id' => 2,
                'product_id' => 2,
                'price_at_order' => 12.50,
                'order_quantity' => 5
            ],
            // Aggiungi altri record a seconda delle necessità
        ];

        foreach ($orderProducts as $entry) {
            DB::table('order_product')->insert([
                'order_id' => $entry['order_id'],
                'product_id' => $entry['product_id'],
                'price_at_order' => $entry['price_at_order'],
                'order_quantity' => $entry['order_quantity'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
