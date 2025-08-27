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

                // 1) Opseg po tipu vremena
                switch ($weatherType) {
                    case 'sunny':  [$min, $max] = [-20, 45]; break;
                    case 'cloudy': [$min, $max] = [-20, 15]; break;
                    case 'rainy':  [$min, $max] = [-30, 10]; break;
                    case 'snowy':  [$min, $max] = [-20, 1];  break;
                }

// 2) Temperatura uz striktno poštovanje opsega tipa
                if ($prevTemp === null) {
                    // DAN 1: kreni odmah iz opsega tipa (da ne može snowy da startuje iznad 1)
                    $temperature = rand($min, $max);
                } else {
                    // prozor dozvoljene promene oko jučerašnje: ±5,
                    // ali presečen opsegom trenutnog tipa vremena
                    $low  = max($min, $prevTemp - 5);
                    $high = min($max, $prevTemp + 5);

                    if ($low > $high) {
                        // ako je juče bilo predaleko od opsega tipa i nema preseka sa ±5,
                        // "približi" se najbližoj granici tipa za taj dan (snap na ivicu)
                        $temperature = ($prevTemp < $min) ? $min : $max;
                    } else {
                        // inače normalno “drhtanje” unutar preseka
                        $temperature = rand($low, $high);
                    }
                }

// fizička granica (opciono)
                $temperature = max(-50, min(55, $temperature));
                $prevTemp    = $temperature;


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
