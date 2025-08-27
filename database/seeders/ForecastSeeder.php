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

                switch ($weatherType) {
                    case 'sunny':  [$min, $max] = [-20, 45]; break;
                    case 'cloudy': [$min, $max] = [-20, 15]; break;
                    case 'rainy':  [$min, $max] = [-30, 10]; break;
                    case 'snowy':  [$min, $max] = [-20, 1];  break;  // ❄️ sneg UVEK -20..+1
                }

                if ($prevTemp === null) {
                    // 1) Prvi dan – dozvoli šta god da izađe (slobodan start)
                    $temperature = rand(-5, 25);
                } else {
                    // 2) Definiši koliki korak dnevno dozvoljavaš
                    $step = rand(1, 5);

                    if ($prevTemp < $min) {
                        // 2a) Ako je prethodna ispod tipa → približi se donjoj granici
                        $temperature = min($min, $prevTemp + $step);
                    } elseif ($prevTemp > $max) {
                        // 2b) Ako je prethodna iznad tipa → približi se gornjoj granici
                        $temperature = max($max, $prevTemp - $step);
                    } else {
                        // 2c) Ako je u okviru tipa → pomeri se ±5 ali uvek drži unutar tipa
                        $proposed    = $prevTemp + rand(-5, 5);
                        $temperature = max($min, min($max, $proposed));
                    }
                }


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
