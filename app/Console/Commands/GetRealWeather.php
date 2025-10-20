<?php

namespace App\Console\Commands;

use App\Models\CitiesModel;
use App\Models\ForecastModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

class GetRealWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:get-real {city?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to get real weather data with OpenWeatherMap API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $city = $this->argument('city');

        $dbCity = CitiesModel::where(['name' => $city])->first();

        if($dbCity === null)
        {
            $dbCity = CitiesModel::create(['name' => $city]);
        }


        $apiKey = env('WEATHER_API_KEY');

        $response = Http::get(env("WEATHER_API_URL")."v1/forecast.json", [
            'key' => $apiKey,
            'q' => $city,
            'aqi' => 'no',
            'days' => '14',
            'lang' => 'sr'
        ]);

        $jsonResponse = $response->json();
        if (isset($jsonResponse['error']))
        {
            $this->output->error($jsonResponse['error']['message']);
            return Command::FAILURE;
        }

//        if($dbCity->todaysForecast !== null)
//        {
//          $this->output->warning("Today's forecast already exists for this city.");
//          return;
//        }

        $forecastDay = $jsonResponse["forecast"]["forecastday"][0];

        // trenutna realna temperatura i vreme(condition) https://www.weatherapi.com/weather/q/

//        $weatherType = $forecastCurrent["condition"]["text"];

        $forecastDate = $forecastDay["date"];
        $nowtemperature = $jsonResponse["current"]["temp_c"];
//        $temperature = $forecastDay["day"]["avgtemp_c"];
        $weatherType = $forecastDay["day"]["condition"]["text"];
        $chanceOfRain = $forecastDay["day"]["daily_chance_of_rain"];


        // Pravilno ažuriranje ili upis današnjeg zapisa
        ForecastModel::updateOrCreate(
            [
                'city_id'       => $dbCity->id,
                'forecast_date' => $forecastDate,
            ],
            [
                'temperature'    => $nowtemperature,  // trenutna - $temperature je AVG_TEMP
                'weather_type'   => strtolower($weatherType),
                'chance_of_rain' => $chanceOfRain,
            ]
        );

        $this->output->success("Forecast for city '$city' added successfully.");
    }
}
