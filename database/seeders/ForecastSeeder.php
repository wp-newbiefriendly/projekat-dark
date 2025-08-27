<?php

namespace Database\Seeders;

use App\Models\ForecastModel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ForecastSeeder extends Seeder
{
    // Podesivo
    private const DAYS = 30;     // koliko dana upisujemo
    private const STEP = 5;      // dnevno pomeranje temperature (±STEP)

    // Definitivni opsezi po tipu vremena
    private const TYPE_RANGES = [
        'sunny'  => [-20, 45],
        'cloudy' => [-20, 15],
        'rainy'  => [-30, 10],
        'snowy'  => [-20, 1],
    ];

    // Globalne fizičke granice (safety)
    private const HARD_MIN = -50;
    private const HARD_MAX = 55;

    /** Vrati sve tipove koji dozvoljavaju datu temperaturu */
    private function allowedTypesForTemp(int $t): array
    {
        $ok = [];
        foreach (self::TYPE_RANGES as $type => [$min, $max]) {
            if ($t >= $min && $t <= $max) $ok[] = $type;
        }
        return $ok;
    }

    /** Ako nijedan tip trenutno ne “pokriva” T, uzmi tip čiji je opseg najbliži T */
    private function nearestTypeForTemp(int $t): string
    {
        $bestType = 'sunny';
        $bestDist = PHP_INT_MAX;

        foreach (self::TYPE_RANGES as $type => [$min, $max]) {
            $dist = ($t < $min) ? $min - $t : (($t > $max) ? $t - $max : 0);
            if ($dist < $bestDist) { $bestDist = $dist; $bestType = $type; }
        }
        return $bestType;
    }

    /** Random verovatnoća samo za rainy/snowy */
    private function probabilityFor(string $type): ?int
    {
        return ($type === 'rainy' || $type === 'snowy') ? rand(1, 100) : null;
    }

    /** Jedan “korak” klizanja temperature uz clamp na fizičke granice */
    private function glideTemp(int $prev): int
    {
        $t = rand($prev - self::STEP, $prev + self::STEP);
        return max(self::HARD_MIN, min(self::HARD_MAX, $t));
    }

    public function run(): void
    {
        $cities = DB::table('cities')->select('id', 'name')->get();
        $count  = $cities->count();

        foreach ($cities as $idx => $city) {

            $lastTemp = null;
            $batch    = []; // bulk insert radi brzine

            for ($day = 0; $day < self::DAYS; $day++) {

                if ($lastTemp === null) {
                    // START: nasumičan tip → T iz njegovog opsega
                    $startType = ForecastModel::WEATHERS[array_rand(ForecastModel::WEATHERS)];
                    [$min, $max] = self::TYPE_RANGES[$startType];
                    $t = rand($min, $max);
                } else {
                    // SLEDEĆI DAN: klizanje ±STEP
                    $t = $this->glideTemp($lastTemp);
                }

                // Izbor tipa kompatibilnog sa T (ili najbliži)
                $allowed = $this->allowedTypesForTemp($t);
                $type    = $allowed ? $allowed[array_rand($allowed)] : $this->nearestTypeForTemp($t);

                // Nakon izbora tipa, garantuj granice tog tipa
                [$tMin, $tMax] = self::TYPE_RANGES[$type];
                $t = max($tMin, min($tMax, $t));

                // Složi red za bulk insert
                $batch[] = [
                    'city_id'       => $city->id,
                    'temperature'   => $t,
                    'forecast_date' => Carbon::today()->addDays($day)->toDateString(),
                    'weather_type'  => $type,
                    'probability'   => $this->probabilityFor($type),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];

                $lastTemp = $t;
            }

            // Bulk insert za ovaj grad
            ForecastModel::insert($batch);

            // Konzola: napredak
            $progress = intval((($idx + 1) / max(1, $count)) * 50);
            $bar      = str_repeat('█', $progress) . str_repeat(' ', 50 - $progress);
            $percent  = round((($idx + 1) / max(1, $count)) * 100);
            echo "\r[".$bar."] $percent% | {$city->name} (".($idx + 1)."/$count)";
        }

        echo "\n✅ Ubacene prognoze za {$count} gradova.\n";
    }
}
