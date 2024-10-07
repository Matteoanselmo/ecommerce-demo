<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscountableSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        // Array per tenere le associazioni di sconti su prodotti e categorie
        $discountables = [];

        // Generiamo 20 associazioni di sconti (10 sui prodotti e 10 sulle categorie)
        for ($i = 1; $i <= 20; $i++) {
            // 50% di probabilità di applicare lo sconto su un prodotto o una categoria
            if (rand(0, 1) === 0) {
                // Applica lo sconto su un prodotto
                $discountables[] = [
                    'discount_id' => rand(1, 3), // Scegli uno sconto esistente (1-3)
                    'discountable_id' => rand(1, 6), // Scegli un prodotto esistente (1-6)
                    'discountable_type' => 'App\Models\Product', // Tipo: Prodotto
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            } else {
                // Applica lo sconto su una categoria
                $discountables[] = [
                    'discount_id' => rand(1, 3), // Scegli uno sconto esistente (1-3)
                    'discountable_id' => rand(1, 6), // Scegli una categoria esistente (1-6)
                    'discountable_type' => 'App\Models\Category', // Tipo: Categoria
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Inseriamo tutte le associazioni nella tabella discountables
        DB::table('discountables')->insert($discountables);
    }
}
