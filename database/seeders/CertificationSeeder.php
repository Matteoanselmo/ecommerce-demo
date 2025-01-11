<?php

namespace Database\Seeders;

use App\Models\Certification;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CertificationSeeder extends Seeder {

    public function run() {

        $certifications = [
            'ISO 9001',
            'ISO 14001',
            'OHSAS 18001',
            'CE Marking',
            'RoHS Compliance'
        ];

        foreach ($certifications as $certificationName) {
            Certification::create(['name' => $certificationName]);
        }

        // Ottieni tutte le certificazioni create
        $allCertifications = Certification::all();

        // Assegna un numero casuale di certificazioni a ciascun prodotto
        $products = Product::all();

        foreach ($products as $product) {
            // Seleziona casualmente un numero di certificazioni da 1 a 3 per ogni prodotto
            $certificationsToAttach = $allCertifications->random(rand(1, 3))->pluck('id');
            $product->certifications()->attach($certificationsToAttach);
        }
    }
}
