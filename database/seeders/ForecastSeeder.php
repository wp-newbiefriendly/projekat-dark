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

            // 1) Reset istorije po gradu
            $lastTemperature = null;

            // 30 dana prognoze po gradu
            for ($i = 0; $i < 30; $i++) {

                // 2) Izbor tipa i verovatnoće
                $weatherType = ForecastModel::WEATHERS[rand(0, 3)];
                $probability = in_array($weatherType, ['rainy', 'snowy']) ? rand(1, 100) : null;

                // 3) Opseg po tipu
                switch ($weatherType) {
                    case 'sunny':  [$min, $max] = [-20, 45]; break;
                    case 'cloudy': [$min, $max] = [-20, 15]; break;
                    case 'rainy':  [$min, $max] = [-30, 10]; break;
                    case 'snowy':  [$min, $max] = [-20, 1];  break;
                }

                // 4) Temperatura: start u opsegu; zatim presek ili klizanje ±5
                if ($lastTemperature === null) {
                    $temperature = rand($min, $max);
                } else {
                    $low  = max($min, $lastTemperature - 5);
                    $high = min($max, $lastTemperature + 5);

                    if ($low <= $high) {
                        // imamo presek: drhtanje unutar preseka
                        $temperature = rand($low, $high);
                    } else {
                        // nema preseka: klizi ka opsegu max 5°
                        if ($lastTemperature < $min) {
                            $temperature = min($min, $lastTemperature + 5);
                        } else { // $lastTemperature > $max
                            $temperature = max($max, $lastTemperature - 5);
                        }
                    }
                }

                // 5) Fizička granica (opciono, ostavi)
                $temperature = max(-50, min(55, $temperature));

                // 6) Sigurnosna mreža po tipu (ne bi trebalo da zatreba, ali čuva od regresija)
                if ($weatherType === 'snowy' && $temperature > 1)   $temperature = 1;
                if ($weatherType === 'rainy' && $temperature > 10)  $temperature = 10;
                if ($weatherType === 'cloudy' && $temperature > 15) $temperature = 15;

                // 7) Upis
                ForecastModel::create([
                    'city_id'       => $city->id,
                    'temperature'   => $temperature,
                    'forecast_date' => Carbon::now()->addDays($i)->format('Y-m-d'),
                    'weather_type'  => $weatherType,
                    'probability'   => $probability,
                ]);

                // 8) Ažuriraj za sledeći dan (UVEK)
                $lastTemperature = $temperature;
            }

            // progress bar (kozmetika)
            $progress = intval((($index + 1) / max(1, $count)) * 50);
            $bar      = str_repeat('█', $progress) . str_repeat(' ', 50 - $progress);
            $percent  = round((($index + 1) / max(1, $count)) * 100);
            echo "\r[".$bar."] $percent% | ".$city->name." (".($index + 1)."/$count)";
        }

        echo "\n✅ Ubacene prognoze za $count gradova.\n";
    }
}
