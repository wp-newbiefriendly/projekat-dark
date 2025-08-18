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
            if ($city === null) {
                $this->command->getOutput()->error('Niste uneli ime grada!');
            }

            // Pitanje za temperaturu
            $temperature = $this->command->ask('Unesite temperaturu za ' . $city . ':');
            if ($temperature === null || $temperature === '') {
                throw new \Exception("❌ Niste uneli temperaturu!");
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
