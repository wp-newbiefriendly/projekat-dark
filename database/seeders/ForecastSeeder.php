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

            // 5 prognoza po gradu
            for ($i = 0; $i < 30; $i++) {
                $weatherType = ForecastsModel::WEATHERS[rand(0, 3)];
                $probability = in_array($weatherType, ['rainy','snowy']) ? rand(1,100) : null;

// 1) Opseg po tipu
                switch ($weatherType) {
                    case 'sunny':  [$min,$max] = [-20, 45]; break;
                    case 'cloudy': [$min,$max] = [-20, 15]; break;
                    case 'rainy':  [$min,$max] = [-30, 10]; break;
                    case 'snowy':  [$min,$max] = [-20, 1];  break;
                }

                if ($lastTemperature === null) {
                    // 2) Start u opsegu tipa
                    $temperature = rand($min, $max);
                } else {
                    // 3) Prozor ±5 oko juče i presek sa opsegom tipa
                    $low  = max($min, $lastTemperature - 5);
                    $high = min($max, $lastTemperature + 5);

                    if ($low <= $high) {
                        // imamo presek → drhtanje unutar preseka
                        $temperature = rand($low, $high);
                    } else {
                        // nema preseka → KLIZI ka opsegu max 5°
                        if ($lastTemperature < $min) {
                            // ispod opsega → podigni, ali najviše do donje granice
                            $temperature = min($min, $lastTemperature + 5);
                        } else {
                            // iznad opsega → spusti, ali najviše do gornje granice
                            $temperature = max($max, $lastTemperature - 5);
                        }
                    }
                }

// (opciono) fizička granica
                $temperature    = max(-50, min(55, $temperature));
                $lastTemperature = $temperature;



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
