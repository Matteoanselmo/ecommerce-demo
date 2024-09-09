<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        // Prodotti di esempio
        $products = [
            [
                'name' => 'Maschera anti polvere',
                'description' => '<p><strong>Maschera protettiva</strong> contro polveri e aerosol. Ideale per lavorare in ambienti polverosi.</p>
                <ul>
                    <li>Filtro antipolvere efficiente</li>
                    <li>Struttura leggera e comoda</li>
                    <li>Fasce regolabili per una perfetta aderenza</li>
                </ul>',
                'subcategory_id' => 1,
                'category_id' => 1,
                'price' => 1999, // Prezzo come intero, rappresentando 19.99
                'categorydetails_id' => 1,
                'stock_quantity' => 100
            ],
            [
                'name' => 'Guanti in lattice',
                'description' => '<p><em>Guanti in lattice di alta qualità</em> per protezione chimica. Resistenti e confortevoli.</p>
                <ul>
                    <li>Materiale: 100% lattice naturale</li>
                    <li>Resistenti a prodotti chimici e abrasioni</li>
                    <li>Flessibili e aderenti per una presa sicura</li>
                </ul>',
                'subcategory_id' => 2,
                'category_id' => 2,
                'price' => 1250, // Prezzo come intero, rappresentando 12.50
                'categorydetails_id' => 2,
                'stock_quantity' => 200
            ],
            [
                'name' => 'Casco protettivo',
                'description' => '<p><strong>Casco robusto</strong> per protezione in ambienti di lavoro pericolosi. Garantisce sicurezza ottimale.</p>
                <ul>
                    <li>Materiale: policarbonato resistente agli urti</li>
                    <li>Cinturino regolabile</li>
                    <li>Design ergonomico per massimo comfort</li>
                </ul>',
                'subcategory_id' => 3,
                'category_id' => 1,
                'price' => 3000, // Prezzo come intero, rappresentando 30.00
                'categorydetails_id' => null,
                'stock_quantity' => 150
            ],
            [
                'name' => 'Occhiali di sicurezza',
                'description' => '<p><strong>Occhiali protettivi</strong> anti-graffio e anti-appannamento. Perfetti per lavori di precisione.</p>
                <ul>
                    <li>Lenti trasparenti con trattamento anti-graffio</li>
                    <li>Montatura leggera in plastica</li>
                    <li>Compatibili con maschere protettive</li>
                </ul>',
                'subcategory_id' => 4,
                'category_id' => 1,
                'price' => 1575, // Prezzo come intero, rappresentando 15.75
                'categorydetails_id' => 3,
                'stock_quantity' => 80
            ],
            [
                'name' => 'Tuta protettiva',
                'description' => '<p><strong>Tuta completa</strong> per isolamento da sostanze chimiche e agenti biologici. Impermeabile e resistente.</p>
                <ul>
                    <li>Materiale: polipropilene impermeabile</li>
                    <li>Chiusura con zip e cappuccio</li>
                    <li>Resistente agli spruzzi di liquidi chimici</li>
                </ul>',
                'subcategory_id' => 5,
                'category_id' => 2,
                'price' => 4200, // Prezzo come intero, rappresentando 42.00
                'categorydetails_id' => 5,
                'stock_quantity' => 50
            ],
            [
                'name' => 'Scarpe antinfortunistiche Base',
                'description' => '<p><strong>Scarpe antinfortunistiche</strong> con punta rinforzata e suola antiscivolo. Ottime per lavoratori edili.</p>
                <ul>
                    <li>Materiale: pelle resistente all\'acqua</li>
                    <li>Punta in acciaio per massima protezione</li>
                    <li>Suola antiscivolo per una presa sicura</li>
                </ul>',
                'subcategory_id' => 6,
                'category_id' => 2,
                'price' => 8999, // Prezzo come intero, rappresentando 89.99
                'categorydetails_id' => 6,
                'stock_quantity' => 70
            ],
            [
                'name' => 'Gilet alta visibilità',
                'description' => '<p><strong>Gilet catarifrangente</strong> per lavori notturni. Aumenta la visibilità e la sicurezza sul lavoro.</p>
                <ul>
                    <li>Materiale: tessuto traspirante</li>
                    <li>Strisce riflettenti per visibilità a 360°</li>
                    <li>Taglia unica con chiusura regolabile</li>
                </ul>',
                'subcategory_id' => 5,
                'category_id' => 3,
                'price' => 1000, // Prezzo come intero, rappresentando 10.00
                'categorydetails_id' => 5,
                'stock_quantity' => 150
            ],
            [
                'name' => 'Cuffie antirumore',
                'description' => '<p><strong>Cuffie antirumore</strong> per protezione dell\'udito. Ideali per lavori in ambienti rumorosi.</p>
                <ul>
                    <li>Attenuazione del rumore fino a 30 dB</li>
                    <li>Cuscinetti in schiuma per massimo comfort</li>
                    <li>Regolabili per una vestibilità perfetta</li>
                </ul>',
                'subcategory_id' => 4,
                'category_id' => 4,
                'price' => 3500, // Prezzo come intero, rappresentando 35.00
                'categorydetails_id' => 1,
                'stock_quantity' => 100
            ],
            [
                'name' => 'Giacca impermeabile',
                'description' => '<p><strong>Giacca impermeabile</strong> per protezione da pioggia e vento. Comoda e leggera.</p>
                <ul>
                    <li>Materiale: tessuto impermeabile in PVC</li>
                    <li>Chiusura con zip e cappuccio regolabile</li>
                    <li>Tasche multiple per una maggiore praticità</li>
                </ul>',
                'subcategory_id' => 5,
                'category_id' => 3,
                'price' => 5500, // Prezzo come intero, rappresentando 55.00
                'categorydetails_id' => 4,
                'stock_quantity' => 60
            ],
            [
                'name' => 'Tappo auricolari in silicone',
                'description' => '<p><strong>Tappi auricolari morbidi e comodi</strong>. Adatti per proteggere l\'udito in ambienti rumorosi.</p>
                <ul>
                    <li>Materiale: silicone ipoallergenico</li>
                    <li>Riutilizzabili e facili da pulire</li>
                    <li>Confezione da 5 paia</li>
                </ul>',
                'subcategory_id' => 1,
                'category_id' => 4,
                'price' => 500, // Prezzo come intero, rappresentando 5.00
                'categorydetails_id' => 2,
                'stock_quantity' => 300
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->insert([
                'name' => $product['name'],
                'description' => $product['description'],
                'subcategory_id' => $product['subcategory_id'],
                'category_id' => $product['category_id'],
                'price' => $product['price'], // Ora è intero
                'categorydetails_id' => $product['categorydetails_id'],
                'stock_quantity' => $product['stock_quantity'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
