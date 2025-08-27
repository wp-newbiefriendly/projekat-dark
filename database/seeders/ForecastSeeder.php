<?php

namespace Database\Seeders;

use App\Models\ForecastModel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ForecastSeeder extends Seeder
{
    public function run(): void
    {
        $cities = DB::table('cities')->get();
        $count  = $cities->count();

        foreach ($cities as $index => $city) {

            // čuvamo prethodnu temperaturu za grad
            $prevTemp = null;

            // 5 prognoza po gradu
            for ($i = 0; $i < 30; $i++) {
                $weatherType = ForecastModel::WEATHERS[rand(0,3)];
                $probability = in_array($weatherType, ['rainy', 'snowy'])
                    ? rand(20, 100)
                    : null;

                // ➊ Granice po tipu
                // 1) Opseg po tipu
                switch ($weatherType) {
                    case 'sunny':  [$min, $max] = [-20, 45]; break;
                    case 'cloudy': [$min, $max] = [-20, 15]; break;
                    case 'rainy':  [$min, $max] = [-30, 10]; break;
                    case 'snowy':  [$min, $max] = [-20, 1];  break; // ❄️ uvek -20..+1
                }

// 2) Predlog temperature
                if ($prevTemp === null) {
                    // Prvi dan: uvek u okviru izabranog tipa → nema više snowy 14°C
                    $proposed = rand($min, $max);
                } else {
                    // Ako je isti tip vremena → “drhti” oko prethodne ±5
                    // Ako se tip promenio → i dalje koristi mali korak (postepeno)
                    $korak   = ($weatherType === ($prevWeatherType ?? null)) ? rand(-5, 5) : rand(-5, 5);
                    $proposed = $prevTemp + $korak;
                }

// 3) OBAVEZNI CLAMP — garantuje invarijantu opsega po tipu
                $temperature = max($min, min($max, $proposed));

// 4) (opciono) fizički “hard” limit da ništa ne pobegne suludo
                $temperature = max(-40, min(50, $temperature));

// 5) zapamti za sledeći dan
                $prevTemp        = $temperature;
                $prevWeatherType = $weatherType;


                // (opciono) ukupna fizička granica da ne pobegne previše mimo realnog sveta
                $temperature = max(-40, min(50, $temperature));

                  // set za sledeću iteraciju
                $prevTemp        = $temperature;
                $prevWeatherType = $weatherType;


                ForecastModel::create([
                    'city_id'       => $city->id,
                    'temperature'   => $temperature,
                    'forecast_date' => Carbon::now()->addDays($i)->format('Y-m-d'),
                    'weather_type'  => $weatherType,
                    'probability'   => $probability,
                ]);

                $prevTemp = $temperature;
            }

            // progress bar
            $progress = intval((($index + 1) / max(1, $count)) * 50);
            $bar      = str_repeat("█", $progress) . str_repeat(" ", 50 - $progress);
            $percent  = round((($index + 1) / max(1, $count)) * 100);
            echo "\r[" . $bar . "] $percent% | " . $city->name . " (" . ($index + 1) . "/$count)";
        }

        echo "\n✅ Ubacene prognoze za $count gradova.\n";
    }
}
