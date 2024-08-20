<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Dettagli per le sottocategorie specifiche
        $details = [
            ['name' => 'Protezione da particelle', 'subcategory_id' => 1], // Assumendo che 1 sia l'ID per "Maschere"
            ['name' => 'Resistenza alta', 'subcategory_id' => 2],           // Assumendo che 2 sia l'ID per "Guanti"
            ['name' => 'Protezione UV', 'subcategory_id' => 4],             // Assumendo che 4 sia l'ID per "Occhiali"
            ['name' => 'Isolamento termico', 'subcategory_id' => 2],
            ['name' => 'Impermeabilità', 'subcategory_id' => 5],            // Assumendo che 5 sia l'ID per "Tute"
            ['name' => 'Antiscivolo', 'subcategory_id' => 6]                // Assumendo che 6 sia l'ID per "Scarpe"
        ];

        foreach ($details as $detail) {
            DB::table('category_details')->insert([
                'name' => $detail['name'],
                'subcategory_id' => $detail['subcategory_id'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
