<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductReviewSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        // Recupera tutti gli ID dei prodotti esistenti
        $productIds = DB::table('products')->pluck('id');
        $userIds = range(1, 11); // ID degli utenti da 1 a 11

        // Esempi di descrizioni per le recensioni
        $reviewDescriptions = [
            'Ottimo prodotto, molto utile e ben fatto.',
            'Buona qualità, ma potrebbe migliorare in alcuni dettagli.',
            'Non completamente soddisfatto, ma fa il suo lavoro.',
            'Assolutamente raccomandato per chi cerca qualità e sicurezza.',
            'Prodotto eccellente, ha superato le mie aspettative.',
            'Molto comodo e resistente, lo uso quotidianamente.',
            'Rapporto qualità-prezzo eccellente, soddisfatto dell\'acquisto.',
            'La protezione è buona, ma si rovina facilmente.',
            'Perfetto per l\'utilizzo in ambienti lavorativi difficili.',
            'Materiale resistente, ma non molto comodo da indossare a lungo.',
        ];

        // Genera almeno 3 recensioni per ogni prodotto
        foreach ($productIds as $productId) {
            for ($i = 0; $i < 3; $i++) {
                DB::table('product_reviews')->insert([
                    'user_id' => $userIds[array_rand($userIds)], // Seleziona un ID utente casuale
                    'product_id' => $productId,
                    'description' => $reviewDescriptions[array_rand($reviewDescriptions)], // Seleziona una descrizione casuale
                    'rating_star' => rand(30, 50) / 10, // Rating casuale tra 3.0 e 5.0
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
