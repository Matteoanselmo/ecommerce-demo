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
            CategorySeeder::class,
            SubCategorySeeder::class,
            CategoryDetailsSeeder::class,
            BrandSeeder::class,
            ColorSeeder::class,
            ProductSeeder::class,
            ProductReviewSeeder::class,
            DiscountSeeder::class,
            DiscountableSeeder::class,
            ProductImageSeeder::class,
            SupportTicketSeeder::class,
            FaqSeeder::class,
            WishlistSeeder::class,
            SizeSeeder::class,
            ProductSizeSeeder::class,
            CertificationSeeder::class,
            UserAddressSeeder::class,
            BillingAddressSeeder::class,
            OrderSeeder::class,
            OrderProductSeeder::class,
        ]);
    }
}
