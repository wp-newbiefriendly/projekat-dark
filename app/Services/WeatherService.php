<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{

    public function getForecast($city)
    {
        $response = Http::get(env("WEATHER_API_URL")."v1/forecast.json", [
            'key' => env('WEATHER_API_KEY'),
            'q' => $city,
            'aqi' => 'no',
            'days' => '14',
            'lang' => 'en'
        ]);

        return $response->json();

    }
    public function getSunsetAndSunrise($city)
    {
        $response = Http::get(env("WEATHER_API_URL")."v1/astronomy.json", [
            'key' => env('WEATHER_API_KEY'),
            'q' => $city,
            'aqi' => 'no',
            'days' => '14',
            'lang' => 'en'
        ]);

        return $response->json();
    }

}
