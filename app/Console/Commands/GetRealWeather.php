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
        ]);

        $jsonResponse = $response->json();
        if (isset($jsonResponse['error']))
        {
            $this->output->error($jsonResponse['error']['message']);
        }

        if($dbCity->todaysForecast !== null)
        {
          $this->output->warning("Today's forecast already exists for this city.");
          return;
        }

        $forecastDate = $jsonResponse["forecast"]["forecastday"][0]["date"];
        $temperature = $jsonResponse["forecast"]["forecastday"][0]["day"]["avgtemp_c"];
        $weatherType = $jsonResponse["forecast"]["forecastday"][0]["day"]["condition"]["text"];
        $chanceOfRain = $jsonResponse["forecast"]["forecastday"][0]["day"]["daily_chance_of_rain"];

//        dd($forecastDate,$temperature,$weatherType,$chanceOfRain);

        $forecast = [
            'city_id' => $dbCity->id,
            'forecast_date' => $forecastDate,
            'temperature' => $temperature,
            'weather_type' => strtolower($weatherType),
            'chance_of_rain' => $chanceOfRain,
        ];

        ForecastModel::create($forecast);
        $this->output->success("Forecast for city '$city' added successfully.");
    }
}
