<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;

class UserAddressSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Configura Faker per l'italiano
        $faker = \Faker\Factory::create('it_IT');

        // Lista delle province italiane
        $provinces = [
            'Agrigento',
            'Alessandria',
            'Ancona',
            'Aosta',
            'Arezzo',
            'Ascoli Piceno',
            'Asti',
            'Avellino',
            'Bari',
            'Barletta-Andria-Trani',
            'Belluno',
            'Benevento',
            'Bergamo',
            'Biella',
            'Bologna',
            'Bolzano',
            'Brescia',
            'Brindisi',
            'Cagliari',
            'Caltanissetta',
            'Campobasso',
            'Caserta',
            'Catania',
            'Catanzaro',
            'Chieti',
            'Como',
            'Cosenza',
            'Cremona',
            'Crotone',
            'Cuneo',
            'Enna',
            'Fermo',
            'Ferrara',
            'Firenze',
            'Foggia',
            'Forlì-Cesena',
            'Frosinone',
            'Genova',
            'Gorizia',
            'Grosseto',
            'Imperia',
            'Isernia',
            'La Spezia',
            'L\'Aquila',
            'Latina',
            'Lecce',
            'Lecco',
            'Livorno',
            'Lodi',
            'Lucca',
            'Macerata',
            'Mantova',
            'Massa-Carrara',
            'Matera',
            'Messina',
            'Milano',
            'Modena',
            'Monza e della Brianza',
            'Napoli',
            'Novara',
            'Nuoro',
            'Oristano',
            'Padova',
            'Palermo',
            'Parma',
            'Pavia',
            'Perugia',
            'Pesaro e Urbino',
            'Pescara',
            'Piacenza',
            'Pisa',
            'Pistoia',
            'Pordenone',
            'Potenza',
            'Prato',
            'Ragusa',
            'Ravenna',
            'Reggio Calabria',
            'Reggio Emilia',
            'Rieti',
            'Rimini',
            'Roma',
            'Rovigo',
            'Salerno',
            'Sassari',
            'Savona',
            'Siena',
            'Siracusa',
            'Sondrio',
            'Sud Sardegna',
            'Taranto',
            'Teramo',
            'Terni',
            'Torino',
            'Trapani',
            'Trento',
            'Treviso',
            'Trieste',
            'Udine',
            'Varese',
            'Venezia',
            'Verbano-Cusio-Ossola',
            'Vercelli',
            'Verona',
            'Vibo Valentia',
            'Vicenza',
            'Viterbo'
        ];

        $users = User::all();

        foreach ($users as $user) {
            $addressesCount = rand(1, 3); // Numero di indirizzi casuale per ogni utente

            for ($i = 0; $i < $addressesCount; $i++) {
                UserAddress::create([
                    'user_id' => $user->id,
                    'recipient_name' => $faker->name, // Nome del destinatario
                    'company_name' => $faker->optional()->company, // Nome azienda o presso
                    'address' => $faker->streetName, // Nome della via
                    'house_number' => $faker->buildingNumber, // Numero civico
                    'postal_code' => $faker->postcode, // CAP
                    'country' => 'Italia', // Nazione fissa
                    'state' => $faker->randomElement($provinces), // Provincia scelta casualmente
                    'city' => $faker->city, // Città
                    'phone_number' => $faker->phoneNumber, // Numero di telefono
                    'is_primary' => $i === 0, // Il primo indirizzo è impostato come principale
                ]);
            }
        }
    }
}
