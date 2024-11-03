<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Definire le taglie per ogni categoria con i nomi specificati
        $sizes = [
            1 => ['Small', 'Medium', 'Large'],     // Categoria ID 1 - Viso
            2 => ['S', 'M', 'L', 'XL'],            // Categoria ID 2 - Mani
            3 => ['54', '56', '58', '60'],         // Categoria ID 3 - Capo
            4 => ['XS', 'S', 'M', 'L', 'XL', 'XXL'], // Categoria ID 4 - Corpo
            5 => ['38', '39', '40', '41', '42', '43', '44'], // Categoria ID 5 - Piedi
        ];

        // Creare ogni taglia per ciascuna categoria
        foreach ($sizes as $categoryId => $sizeNames) {
            foreach ($sizeNames as $name) {
                Size::create([
                    'name' => $name,
                    'category_id' => $categoryId,
                ]);
            }
        }
    }
}
