<?php

namespace Database\Seeders;

use App\Models\CityTemperatureModel;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Weather extends Seeder
{
    public function run(): void
    {

        for ($i = 0; $i < 1; $i++) {

            // Pitanje za ime grada
            $city = $this->command->ask('Unesite ime grada:');
            if (empty($city)) {
                $this->command->error('❌ Niste uneli ime grada!');
                return; // prekidamo operaciju
            }
            // Proveri da li vec postoji ime grada
            if (CityTemperatureModel::where('city', $city)->exists()) {
                $this->command->error("❌ Grad '$city' već postoji u bazi!");
                return; // prekida izvršavanje
            }

            // Pitanje za temperaturu
            $temperature = $this->command->ask('Unesite temperaturu za ' . $city . ':');
            if ($temperature === null || $temperature === '') {
                $this->command->error("❌ Niste uneli temperaturu!");
                return;
            }

            // Upis u bazu
            CityTemperatureModel::create([
                'city' => $city,
                'temperatures' => $temperature,
            ]);

            $this->command->info("✔ Grad '$city' sa temperaturom $temperature dodat u bazu.");
        }
    }
}
