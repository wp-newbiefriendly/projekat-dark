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

// 2) RACUN TEMPERATURE SA PRAVILIMA:
//    - prvi dan: slobodan start (npr. -5..45 da izgleda realno)
//    - ostali dani: maksimalna promena ±5 u odnosu na juče
//    - ako je jučerašnja temp van opsega za novi tip: pomeraj se KA opsegu korakom do 5
//    - čim uđeš u opseg: dozvoli “drhtanje” ±5, ali i dalje unutar opsega

                if ($prevTemp === null) {
                    // DAN 1: može šta god (po želji suzi raspon)
                    $temperature = rand(-5, 45);
                } else {
                    // dozvoljeni dnevni korak
                    $step = rand(1, 5);

                    if ($prevTemp < $min) {
                        // juče ispod opsega → diži se ka donjoj granici, ali max +5
                        $temperature = $prevTemp + $step;              // NEMA clamp-a na $min da ne “teleportuje”
                        // (sledećih dana će nastaviti da se penje dok ne uđe u [min,max])
                    } elseif ($prevTemp > $max) {
                        // juče iznad opsega → spuštaj se ka gornjoj granici, ali max -5
                        $temperature = $prevTemp - $step;              // NEMA clamp-a na $max da ne “teleportuje”
                    } else {
                        // juče je bilo u opsegu → drhtanje ±5, ali ostani u granicama tipa
                        $proposed    = $prevTemp + rand(-5, 5);
                        $temperature = max($min, min($max, $proposed)); // clamp unutar [min,max]
                    }
                }

// (opciono) hard fizička granica da ne ode previše suludo
                $temperature = max(-50, min(55, $temperature));

// zapamti za sledeći dan
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
