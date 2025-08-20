<?php

namespace Database\Seeders;

use App\Models\CityTemperatureModel;
use Illuminate\Database\Seeder;

class BulkWeatherSeeder extends Seeder
{
    public function run(): void
    {
        $prognoza = [
            "Beograd" => 30,
            "Nis" => 33,
            "Zajecar" => 31,
            "Negotin" => 28,
            "Bor" => 26
        ];
        // Skracena verzija firstOrCreate - proveri da li postoji, ako ne ubaci u bazu
        foreach ($prognoza as $city => $temperatures) {
            $weather = CityTemperatureModel::firstOrCreate(
                ["city" => $city],
                ["temperatures" => $temperatures]
            );
            // Info - Uspesno, Error - Vec postoji
            if ($weather->wasRecentlyCreated) {
                $this->command->info("Grad '$city' uspešno dodat.");
            } else {
                $this->command->error("Grad '$city' već postoji!");
            }
        }
    }
}
