<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Prendi tutti i prodotti e assegna loro delle immagini fake
        $products = Product::all();


        foreach ($products as $product) {
            for ($i = 0; $i < 3; $i++) { // Ad esempio, assegna 3 immagini a ciascun prodotto
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => 'https://cdn.vuetifyjs.com/images/parallax/material2', // Solo il percorso senza estensione
                    'extension' => 'jpg', // Estensione dell'immagine
                ]);
            }
        }
    }
}
