<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        // Lista di categorie da inserire
        $categories = [
            ['name' => 'Viso', 'icon' => 'mdi mdi-face'],
            ['name' => 'Mani', 'icon' => 'mdi mdi-hand'],
            ['name' => 'Capo', 'icon' => 'mdi mdi-hat-fedora'],
            ['name' => 'Corpo', 'icon' => 'mdi mdi-human'],
            ['name' => 'Piedi', 'icon' => 'mdi mdi-shoe-sneaker'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'icon' => $category['icon'], // Inserimento dell'icona
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
