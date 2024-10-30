<?php

namespace Database\Seeders;

use App\Models\Wishlist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        for ($i = 1; $i <= 40; $i++) {
            $userId = rand(1, 11); // Assumi che ci siano almeno 11 utenti
            $productId = rand(1, 45); // Assumi che ci siano almeno 45 prodotti

            Wishlist::firstOrCreate([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
        }
    }
}
