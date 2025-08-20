<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddCitiesToCities extends Seeder
{
    public function run(): void
    {
        $cities = [
            'Amsterdam', 'Athens', 'Belgrade', 'Berlin', 'Bratislava', 'Brussels',
            'Bucharest', 'Budapest', 'Copenhagen', 'Dublin', 'Edinburgh', 'Florence',
            'Frankfurt', 'Geneva', 'Gothenburg', 'Hamburg', 'Helsinki', 'Istanbul',
            'Krakow', 'Lisbon', 'Ljubljana', 'London', 'Lyon', 'Madrid', 'Milan',
            'Monaco', 'Moscow', 'Munich', 'Naples', 'Nice', 'Oslo', 'Paris',
            'Porto', 'Prague', 'Reykjavik', 'Riga', 'Rome', 'Rotterdam', 'Sarajevo',
            'Seville', 'Skopje', 'Sofia', 'Split', 'Stockholm', 'Tallinn', 'Thessaloniki',
            'Tirana', 'Turin', 'Valencia', 'Venice', 'Vienna', 'Vilnius', 'Warsaw',
            'Zagreb', 'Zurich',
            'Antwerp', 'Basel', 'Bergen', 'Bilbao', 'Bologna', 'Bordeaux', 'Bruges',
            'Cambridge', 'Cologne', 'Dortmund', 'Dresden', 'Gdansk', 'Ghent', 'Graz',
            'Granada', 'Hannover', 'Innsbruck', 'Leeds', 'Liverpool', 'Luxembourg',
            'Malaga', 'Manchester', 'Marseille', 'Montpellier', 'Nicosia', 'Nuremberg',
            'Palermo', 'Poznan', 'Salzburg', 'Sheffield', 'Stuttgart', 'Tallaght',
            'Toulouse', 'Trieste', 'Utrecht', 'Valletta', 'Verona', 'Vilvoorde',
            'Wroclaw', 'Zaragoza', 'Subotica', 'Nis', 'Zajecar', 'Bor'
        ];

        $count = count($cities);

        foreach ($cities as $index => $city) {
            DB::table('cities')->insert([
                'name' => $city,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // progress bar
            $progress = intval((($index + 1) / $count) * 50); // 50 znakova širina
            $bar = str_repeat("█", $progress) . str_repeat(" ", 50 - $progress);
            $percent = round((($index + 1) / $count) * 100);

            echo "\r[" . $bar . "] $percent% ($index/$count)";
        }

        echo "\n✅ Ubaceno ukupno $count gradova u tabelu cities.\n";
    }
}
