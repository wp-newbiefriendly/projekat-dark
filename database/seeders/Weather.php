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

    public function run(): void
    {
      $cities = DB::table("cities")->get();

        $count  = $cities->count();


        foreach ($cities as $index => $city) {
          DB::table("weather")->insert([
              'city_id' => $city->id,
              'temperature' => rand(20, 35), // trenutna random temperatura
              'created_at' => now(),
              'updated_at' => now(),
          ]);

      }
        $progress = intval((($index+1) / $count) * 50);
        $bar      = str_repeat("█", $progress) . str_repeat(" ", 50 - $progress);
        $percent  = round((($index+1)/$count)*100);

        echo "\r[".$bar."] $percent% | ".$city->name." ($index/$count)";
    }
}
