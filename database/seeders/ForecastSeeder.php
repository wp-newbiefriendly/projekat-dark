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
                switch ($weatherType) {
                    case 'sunny':  [$min, $max] = [-20, 45]; break;
                    case 'cloudy': [$min, $max] = [-20, 15]; break;
                    case 'rainy':  [$min, $max] = [-30, 10]; break;
                    case 'snowy':  [$min, $max] = [-20, 1];  break;
                }

               // ➋ Prvi dan: može "šta god" (umeren raspon da izgleda realnije)
                if ($prevTemp === null) {
                    $temperature = rand(-5, 25);
                } else {
                    // Koliko sme da se promeni u jednom danu (korak)
                    $step = rand(1, 5);

                    if (!isset($prevWeatherType) || $weatherType === $prevWeatherType) {
                        // ➌ Isti tip vremena → prati prethodnu (±5), ali drži u granicama tipa
                        $proposed    = $prevTemp + rand(-5, 5);
                        $temperature = max($min, min($max, $proposed));
                    } else {
                        // ➍ Promena tipa vremena → POSTEPENO približavanje novom opsegu (bez naglog skoka)
                        if     ($prevTemp > $max) $temperature = $prevTemp - $step;  // spuštaj ka gornjoj granici novog tipa
                        elseif ($prevTemp < $min) $temperature = $prevTemp + $step;  // diži ka donjoj granici novog tipa
                        else                      $temperature = $prevTemp + rand(-3, 3); // već si u opsegu → blago variraj

                        // (namerno NE klampujemo ovde da ne "iscvokne" odmah na granicu;
                        // sledećih dana će se po koracima približavati novom opsegu)
                    }
                }

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
