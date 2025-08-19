<?php

namespace Database\Seeders;

use App\Models\WeatherModel;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Weather extends Seeder
{
    // NAPOMENA:
    // Nisam napravio Novi seeder UserWeatherSeeder - ovaj postojeci smo izmenili da bude kao vezba "UserWeatherSeeder"
    // Novi je BulkWeatherSeeder za dodavanje ispisanih podataka GRAD i TEMPERATURA iz varijable u bazu
    public function run(): void
    {
      $cities = DB::table("cities")->get();

        $count  = $cities->count();


        foreach ($cities as $city) {
          DB::table("weather")->insert([
              'city_id' => $city->id,
              'temperature' => rand(20, 35), // trenutna random temperatura
              'created_at' => now(),
              'updated_at' => now(),
          ]);

      }
        $progress = intval((($index+1) / $count) * 50); // širina bara = 50 znakova
        $bar      = str_repeat("█", $progress) . str_repeat(" ", 50 - $progress); // puni i prazni delovi
        $percent  = round((($index+1)/$count)*100); // procenat završenog posla

        // 6. Prikazujemo progress bar u jednom redu, zajedno sa imenom grada!
        echo "\r[".$bar."] $percent% | ".$city->name." ($index/$count)";
    }
}
