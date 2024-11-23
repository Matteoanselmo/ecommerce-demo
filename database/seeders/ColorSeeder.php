<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $colors = [
            ['name' => '#FF0000'],
            ['name' => '#00FF00'],
            ['name' => '#0000FF'],
            ['name' => '#FFFF00'],
            ['name' => '#000000'],
            ['name' => '#FFFFFF'],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }
    }
}
