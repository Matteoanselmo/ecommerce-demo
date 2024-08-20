<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Sottocategorie specifiche per i prodotti di protezione sul lavoro
        $subCategories = [
            ['name' => 'Maschere', 'category_id' => 1], // Assumendo che 1 sia l'ID per Attrezzature
            ['name' => 'Guanti', 'category_id' => 2],  // Assumendo che 2 sia l'ID per Indumenti
            ['name' => 'Caschi', 'category_id' => 1],
            ['name' => 'Occhiali', 'category_id' => 3],
            ['name' => 'Tute', 'category_id' => 4],
            ['name' => 'Scarpe', 'category_id' => 5]
        ];

        foreach ($subCategories as $subCat) {
            DB::table('sub_categories')->insert([
                'name' => $subCat['name'],
                'category_id' => $subCat['category_id'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
