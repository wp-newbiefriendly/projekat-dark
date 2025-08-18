<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AddUser extends Seeder
{
    public function run(): void
    {
        // Unesi email - **required**
        $email = $this->command->ask('Unesite email:');
        if (empty($email)) {
            $this->command->error('❌ Niste uneli email!');
            return; // prekidamo operaciju
        }
        // Provera exist - ako postoji isti email u bazi!
        if (User::where('email', $email)->exists()) {
            $this->command->error("❌ User sa email-om '$email' već postoji u bazi!");
            return;
        }
        // Unesi username - **required**
        $username = $this->command->ask('Unesite username:');
        if (empty($username)) {
            $this->command->error('❌ Niste uneli username!');
            return;
        }
        // Unesi password - **required**
        $password = $this->command->ask('Unesite lozinku:');
        if (empty($password)) {
            $this->command->error('❌ Niste uneli lozinku!');
            return;
        }

        User::create([
            'name' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'user',
        ]);

        $this->command->info("✔ User '$email' uspešno dodat u bazu.");
    }
}
