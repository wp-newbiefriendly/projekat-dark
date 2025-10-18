<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetRealWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:get-real {city}';

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
        $apiKey = env('WEATHER_API_KEY');

//      $location = 'London';
        $city = $this->argument('city');

        $url = "https://api.weatherapi.com/v1/current.json?key=$apiKey&q=$city";

        $response = Http::get($url);

        dd($response->json());

//        dd([
//            'Location Name' => $response['location']['name'],
//            'Region' => $response['location']['region'],
//            'Country' => $response['location']['country'],
//            'Last Updated' => $response['current']['last_updated'],
//            'Temperature' => $response['current']['temp_c'] . '°C'
//        ]);
    }
}
