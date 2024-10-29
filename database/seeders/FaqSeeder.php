<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        // Recupera tutti i prodotti dal database
        $products = DB::table('products')->pluck('id');

        // Testo di esempio per le domande e risposte delle FAQ
        $faqExamples = [
            [
                'question' => 'Qual è la durata di questo prodotto?',
                'answer' => 'La durata dipende dalle condizioni di utilizzo, ma generalmente dura almeno 1 anno.'
            ],
            [
                'question' => 'Questo prodotto è certificato?',
                'answer' => 'Sì, il prodotto ha ottenuto tutte le certificazioni di sicurezza richieste.'
            ],
            [
                'question' => 'Qual è il materiale principale?',
                'answer' => 'Il materiale principale è un mix di polimeri e fibre resistenti.'
            ],
            [
                'question' => 'È disponibile in diverse taglie?',
                'answer' => 'Sì, questo prodotto è disponibile in varie taglie.'
            ],
            [
                'question' => 'Posso usare questo prodotto all’aperto?',
                'answer' => 'Sì, il prodotto è progettato per resistere anche in ambienti esterni.'
            ],
        ];

        // Per ogni prodotto, crea almeno 2 FAQ
        foreach ($products as $productId) {
            for ($i = 0; $i < 2; $i++) {
                // Scegli una FAQ casuale dall'elenco
                $faq = $faqExamples[array_rand($faqExamples)];

                // Inserisci la FAQ nel database collegandola al prodotto
                DB::table('faqs')->insert([
                    'product_id' => $productId,
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
