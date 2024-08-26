<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        $this->call([
            UserSeeder::class,
            OrderSeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            CategoryDetailsSeeder::class,
            ProductSeeder::class,
            OrderProductSeeder::class,
            ProductReviewSeeder::class,
            DiscountSeeder::class,
            DiscountableSeeder::class,
            ProductImageSeeder::class
        ]);
    }
}
