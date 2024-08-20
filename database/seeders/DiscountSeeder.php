<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Sconti di esempio
        $discounts = [
            [
                'name' => 'Sconto di lancio',
                'discount_value' => 10.00,
                'discount_type' => 'percentage',
                'start_date' => '2024-01-01',
                'end_date' => '2024-01-31'
            ],
            [
                'name' => 'Sconto estivo',
                'discount_value' => 5.00,
                'discount_type' => 'percentage',
                'start_date' => '2024-06-01',
                'end_date' => '2024-06-30'
            ],
            [
                'name' => 'Promozione speciale',
                'discount_value' => 20.00,
                'discount_type' => 'fixed',
                'start_date' => '2024-03-15',
                'end_date' => '2024-04-15'
            ],
            // Aggiungi altri sconti a seconda delle necessità
        ];

        foreach ($discounts as $discount) {
            DB::table('discounts')->insert([
                'name' => $discount['name'],
                'discount_value' => $discount['discount_value'],
                'discount_type' => $discount['discount_type'],
                'start_date' => $discount['start_date'],
                'end_date' => $discount['end_date'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
