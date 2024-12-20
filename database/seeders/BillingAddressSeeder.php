<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BillingAddress;
use Faker\Factory as Faker;

class BillingAddressSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $faker = Faker::create();

        for ($i = 1; $i <= 11; $i++) {
            // Crea da 1 a 3 indirizzi per ogni utente
            $addressCount = rand(1, 3);

            for ($j = 0; $j < $addressCount; $j++) {
                BillingAddress::create([
                    'user_id' => $i,
                    'name' => $faker->randomElement([
                        $faker->name, // Privato
                        $faker->company // Azienda
                    ]),
                    'tax_id' => $faker->boolean(70) ? $faker->regexify('[A-Z]{2}[0-9]{11}') : null, // 70% probabilità di avere un valore
                    'address' => $faker->streetAddress,
                    'house_number' => $faker->buildingNumber,
                    'postal_code' => $faker->postcode,
                    'city' => $faker->city,
                    'state' => $faker->state,
                    'country' => $faker->country,
                    'phone_number' => $faker->phoneNumber,
                    'is_primary' => $j === 0, // Imposta il primo indirizzo come principale
                ]);
            }
        }
    }
}
