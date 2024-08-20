<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Esempi di recensioni dei prodotti
        $reviews = [
            [
                'user_id' => 1, // Assicurati che questo utente esista
                'product_id' => 1, // Assicurati che questo prodotto esista
                'description' => 'Ottima maschera, molto comoda e resistente.',
                'rating_star' => 4.5,
            ],
            [
                'user_id' => 1,
                'product_id' => 2,
                'description' => 'Guanti durevoli e sicuri, raccomando!',
                'rating_star' => 5.0,
            ],
            [
                'user_id' => 2,
                'product_id' => 1,
                'description' => 'Buona qualità ma un po\' stretta per il mio viso.',
                'rating_star' => 3.5,
            ],
            // Aggiungi altre recensioni come necessario
        ];

        foreach ($reviews as $review) {
            DB::table('product_reviews')->insert([
                'user_id' => $review['user_id'],
                'product_id' => $review['product_id'],
                'description' => $review['description'],
                'rating_star' => $review['rating_star'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
