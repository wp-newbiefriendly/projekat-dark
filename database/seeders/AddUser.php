<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AddUser extends Seeder
{
    public function run(): void
    {
        $email = $this->command->ask('Unesite email:');
        if (empty($email)) {
            throw new \Exception("❌ Niste uneli email!");
        }

        // Provera exist - ako postoji
        if (User::where('email', $email)->exists()) {
            throw new \Exception("❌ User sa emailom '$email' već postoji u bazi!");
        }

        $username = $this->command->ask('Unesite username:');
        if (empty($username)) {
            throw new \Exception("❌ Niste uneli username!");
        }

        $password = $this->command->ask('Unesite lozinku:');
        if (empty($password)) {
            throw new \Exception("❌ Niste uneli lozinku!");
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
