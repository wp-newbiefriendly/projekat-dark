<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ForecastSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Izvlačimo sve redove iz 'cities' tabele (id, name, created_at, updated_at...)
        $cities = DB::table('cities')->get();

        // 2. Brojimo koliko ukupno ima gradova (koristi se za progress bar)
        $count = $cities->count();

        // 3. Petlja: ide kroz sve gradove, $index = redni broj (0,1,2...), $city = objekat (id, name, ...)
        foreach ($cities as $index => $city) {

            // 4. Unutar svakog grada dodajemo 5 prognoza (za 5 različitih dana)
            for ($i = 0; $i < 5; $i++) {
                DB::table('forecasts')->insert([
                    // spajamo forecast sa gradom preko foreign key-a
                    'city_id' => $city->id,

                    // nasumična temperatura između 25 i 35
                    'temperature' => rand(25, 35),

                    // datum = 19.08.2025 + $i dana
                    'date' => Carbon::create(2025, 8, 19)->addDays($i)->format('Y-m-d'),

                    // timestampi
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 5. Računamo progress za svaki grad
            $progress = intval((($index + 1) / $count) * 50); // širina bara = 50 znakova
            $bar = str_repeat("█", $progress) . str_repeat(" ", 50 - $progress); // puni i prazni delovi
            $percent = round((($index + 1) / $count) * 100); // procenat završenog posla

            // 6. Prikazujemo progress bar u jednom redu, zajedno sa imenom grada
            echo "\r[" . $bar . "] $percent% | " . $city->name . " ($index/$count)";
        }

        // 7. Kada se sve završi, ispiše završnu poruku
        echo "\n✅ Ubacene prognoze za $count gradova.\n";
    }
}
