<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSizeSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Recupera i prodotti
        $products = Product::where('id', '<=', 45)->get();

        foreach ($products as $product) {
            // Recupera la categoria associata al prodotto
            $category = $product->category;

            // Controlla se la categoria esiste e ha taglie associate
            if ($category && $category->sizes->isNotEmpty()) {
                // Recupera le taglie della categoria del prodotto
                $sizes = $category->sizes;

                // Seleziona un sottoinsieme casuale delle taglie (es: 0 fino al massimo delle taglie disponibili)
                $selectedSizes = $sizes->random(rand(0, $sizes->count()));

                // Assegna solo le taglie selezionate al prodotto con uno stock casuale
                foreach ($selectedSizes as $size) {
                    DB::table('product_sizes')->insert([
                        'product_id' => $product->id,
                        'size_id' => $size->id,
                        'stock' => rand(10, 50), // Stock casuale tra 10 e 50
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
