<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscountableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Esempi di applicazioni di sconti
        $discountables = [
            [
                'discount_id' => 1, // Assumi che questo sconto esista
                'discountable_id' => 1, // Assumi che questo prodotto esista
                'discountable_type' => 'App\Models\Product' // Cambia a seconda del tuo namespace e modello
            ],
            [
                'discount_id' => 2,
                'discountable_id' => 2, // Assumi che questa categoria esista
                'discountable_type' => 'App\Models\Category' // Cambia a seconda del tuo namespace e modello
            ],
            [
                'discount_id' => 3,
                'discountable_id' => 3,
                'discountable_type' => 'App\Models\Product'
            ],
            // Aggiungi altri record a seconda delle necessità
        ];

        foreach ($discountables as $discountable) {
            DB::table('discountables')->insert([
                'discount_id' => $discountable['discount_id'],
                'discountable_id' => $discountable['discountable_id'],
                'discountable_type' => $discountable['discountable_type'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
