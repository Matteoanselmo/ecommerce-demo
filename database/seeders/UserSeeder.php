<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creare l'utente admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password123'), // Ricorda di cambiare la password in produzione
        ]);

        // Creare più utenti normali
        $numberOfUsers = 10; // Numero di utenti 'user' da creare
        for ($i = 1; $i <= $numberOfUsers; $i++) {
            User::create([
                'name' => "Regular User $i",
                'email' => "user$i@example.com",
                'role' => 'user',
                'password' => Hash::make('password123'), // Ricorda di cambiare la password in produzione
            ]);
        }
    }
}
