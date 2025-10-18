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

        $response = Http::get("https://api.weatherapi.com/v1/current.json", [
            'key' => $apiKey,
            'q' => $city,
            'aqi' => 'no',
            'lang' => 'ar',
        ]);

        $jsonResponse = $response->json();
        if (isset($jsonResponse['error']))
        {
            $this->output->error($jsonResponse['error']['message']);
        }
        dd($jsonResponse);

    }
}
