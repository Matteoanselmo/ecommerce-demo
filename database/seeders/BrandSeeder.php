<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $brands = [
            ['name' => 'SafetyWear'],
            ['name' => 'ProSecure'],
            ['name' => 'WorkShield'],
            ['name' => 'SafeStyle'],
            ['name' => 'GuardianGear'],
            ['name' => 'HardHat Apparel'],
            ['name' => 'SecureLine'],
            ['name' => 'WorkSafe Pro'],
            ['name' => 'DurableGear'],
            ['name' => 'SafetyFirst'],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
