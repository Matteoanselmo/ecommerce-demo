<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        // Prodotti di esempio base
        $baseProducts = [
            [
                'name' => 'Maschera anti polvere',
                'description' => '<p><strong>Maschera protettiva</strong> contro polveri e aerosol. Ideale per lavorare in ambienti polverosi.</p>',
                'subcategory_id' => 1,
                'category_id' => 1,
                'price' => 1999, // Prezzo come intero, rappresentando 19.99
                'categorydetails_id' => 1,
                'stock_quantity' => 100
            ],
            [
                'name' => 'Guanti in lattice',
                'description' => '<p><em>Guanti in lattice di alta qualità</em> per protezione chimica. Resistenti e confortevoli.</p>',
                'subcategory_id' => 2,
                'category_id' => 2,
                'price' => 1250, // Prezzo come intero, rappresentando 12.50
                'categorydetails_id' => 2,
                'stock_quantity' => 200
            ],
            [
                'name' => 'Casco protettivo',
                'description' => '<p><strong>Casco robusto</strong> per protezione in ambienti di lavoro pericolosi.</p>',
                'subcategory_id' => 3,
                'category_id' => 1,
                'price' => 3000, // Prezzo come intero, rappresentando 30.00
                'categorydetails_id' => null,
                'stock_quantity' => 150
            ],
            [
                'name' => 'Occhiali di sicurezza',
                'description' => '<p><strong>Occhiali protettivi</strong> anti-graffio e anti-appannamento.</p>',
                'subcategory_id' => 4,
                'category_id' => 1,
                'price' => 1575, // Prezzo come intero, rappresentando 15.75
                'categorydetails_id' => 3,
                'stock_quantity' => 80
            ],
            [
                'name' => 'Tuta protettiva',
                'description' => '<p><strong>Tuta completa</strong> per isolamento da sostanze chimiche e agenti biologici.</p>',
                'subcategory_id' => 5,
                'category_id' => 2,
                'price' => 4200, // Prezzo come intero, rappresentando 42.00
                'categorydetails_id' => 5,
                'stock_quantity' => 50
            ],
            [
                'name' => 'Scarpe antinfortunistiche Base',
                'description' => '<p><strong>Scarpe antinfortunistiche</strong> con punta rinforzata e suola antiscivolo.</p>',
                'subcategory_id' => 6,
                'category_id' => 2,
                'price' => 8999, // Prezzo come intero, rappresentando 89.99
                'categorydetails_id' => 6,
                'stock_quantity' => 70
            ],
        ];

        // Generare 50 prodotti variando i valori degli attributi
        for ($i = 0; $i < 50; $i++) {
            // Scegli un prodotto casuale dalla lista dei prodotti base
            $baseProduct = $baseProducts[array_rand($baseProducts)];

            // Crea un nuovo nome per differenziare i prodotti
            $newProductName = $baseProduct['name'] . ' - Variante ' . ($i + 1);

            // Inserisce il prodotto nel database
            DB::table('products')->insert([
                'name' => $newProductName,
                'description' => $baseProduct['description'],
                'subcategory_id' => rand(1, 6), // SubCategory massimo 6
                'category_id' => rand(1, 5), // Category massimo 5
                'price' => rand(500, 10000), // Prezzo casuale tra 5.00 e 100.00 rappresentato come intero
                'categorydetails_id' => rand(1, 6), // CategoryDetails massimo 6
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
