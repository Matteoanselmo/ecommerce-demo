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
                $isCompany = $faker->boolean(50); // 50% probabilità che sia un'azienda

                BillingAddress::create([
                    'user_id' => $i,
                    'type' => $isCompany ? 'company' : 'private',
                    'address' => $faker->streetAddress,
                    'internal' => $faker->boolean(30) ? $faker->secondaryAddress : null, // 30% probabilità di avere un valore
                    'city' => $faker->city,
                    'postal_code' => $faker->postcode,
                    'state' => $faker->state,
                    'country' => $faker->country,
                    'first_name' => !$isCompany ? $faker->firstName : null, // Solo per privati
                    'last_name' => !$isCompany ? $faker->lastName : null, // Solo per privati
                    'tax_code' => $faker->regexify('[A-Z]{6}[0-9]{2}[A-Z][0-9]{2}[A-Z][0-9]{3}[A-Z]'), // Codice Fiscale valido
                    'company_name' => $isCompany ? $faker->company : null, // Solo per aziende
                    'vat_number' => $isCompany ? $faker->regexify('[A-Z]{2}[0-9]{11}') : null, // Partita IVA (solo aziende)
                    'sdi_code' => $isCompany ? $faker->regexify('[A-Z0-9]{7}') : null, // Codice SDI (solo aziende)
                    'phone_number' => $faker->phoneNumber,
                    'is_primary' => $j === 0, // Imposta il primo indirizzo come principale
                ]);
            }
        }
    }
}
