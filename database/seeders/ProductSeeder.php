<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Prodotti di esempio
        $products = [
            [
                'name' => 'Maschera anti polvere',
                'description' => 'Maschera protettiva contro polveri e aerosoli.',
                'subcategory_id' => 1, // Assumendo che esista
                'category_id' => 1, // Assumendo che esista
                'price' => 19.99,
                'categorydetails_id' => 1, // Assumendo che esista
                'stock_quantity' => 100
            ],
            [
                'name' => 'Guanti in lattice',
                'description' => 'Guanti in lattice di alta qualità per protezione chimica.',
                'subcategory_id' => 2,
                'category_id' => 2,
                'price' => 12.50,
                'categorydetails_id' => 2,
                'stock_quantity' => 200
            ],
            [
                'name' => 'Casco protettivo',
                'description' => 'Casco robusto per protezione in ambienti di lavoro pericolosi.',
                'subcategory_id' => 3,
                'category_id' => 1,
                'price' => 30.00,
                'categorydetails_id' => null,
                'stock_quantity' => 150
            ],
            [
                'name' => 'Occhiali di sicurezza',
                'description' => 'Occhiali protettivi anti-graffio e anti-appannamento.',
                'subcategory_id' => 4,
                'category_id' => 1,
                'price' => 15.75,
                'categorydetails_id' => 3,
                'stock_quantity' => 80
            ],
            [
                'name' => 'Tuta protettiva',
                'description' => 'Tuta completa per isolamento da sostanze chimiche e agenti biologici.',
                'subcategory_id' => 5,
                'category_id' => 2,
                'price' => 42.00,
                'categorydetails_id' => 5,
                'stock_quantity' => 50
            ],
            [
                'name' => 'Scarpe antinfortunistiche',
                'description' => 'Scarpe con punta rinforzata per massima sicurezza sul lavoro.',
                'subcategory_id' => 6,
                'category_id' => 2,
                'price' => 89.99,
                'categorydetails_id' => 6,
                'stock_quantity' => 70
            ]
        ];

        foreach ($products as $product) {
            DB::table('products')->insert([
                'name' => $product['name'],
                'description' => $product['description'],
                'subcategory_id' => $product['subcategory_id'],
                'category_id' => $product['category_id'],
                'price' => $product['price'],
                'categorydetails_id' => $product['categorydetails_id'],
                'stock_quantity' => $product['stock_quantity'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
