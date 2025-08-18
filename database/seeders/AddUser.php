<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AddUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     $amount = $this->command->ask('How many users would you like?', 500);
     $whichpassword = $this->command->ask('Which passwords would you like to generate?', 'password');

     $faker = Factory::create();

     $this->command->getOutput()->progressStart($amount);

     for ($i = 0; $i < $amount; $i++)
     {
         User::create([
             'name' => $faker->name,
             'email' => $faker->email,
             'password' => Hash::make($whichpassword),
             'role' => 'user'
         ]);
         $this->command->getOutput()->progressAdvance(step:1);
     }
     $this->command->getOutput()->progressFinish();
    }
}
