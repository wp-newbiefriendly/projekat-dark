<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;
use App\Models\CitiesModel;
use App\Models\ForecastModel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->input('city', ''));

        $favoriteCityIds = auth()->check() ? auth()->user()->cityFavorites->pluck('city_id')->toArray() : [];

        if ($q !== '') {
            $needle = mb_strtolower($q);
            $cities = CitiesModel::whereRaw('LOWER(name) LIKE ?', ["%{$needle}%"]) // bez with za sada
            ->orderBy('name')
                ->get();

            if ($cities->isEmpty()) {
                // API verifikacija i kreiranje grada
                $resp = Http::get(env('WEATHER_API_URL') . 'v1/forecast.json', [
                    'key' => env('WEATHER_API_KEY'),
                    'q'   => $q,
                    'days'=> 1,
                    'aqi' => 'no',
                ]);

                $data = $resp->json();
                if ($resp->ok() && !isset($data['error'])) {
                    $apiName = $data['location']['name'] ?? $q;
                    $city = CitiesModel::whereRaw('LOWER(name) = ?', [mb_strtolower($apiName)])->first();
                    if (!$city) {
                        $city = CitiesModel::create(['name' => $apiName]);
                    }

                    // Obavezno osveži za novokreirani grad
                    Artisan::call('weather:get-real', ['city' => $city->name]);
                    $cities = collect([$city]);
                } else {
                    return redirect()->route('welcome')
                        ->with('error', "Grad '{$q}' ne postoji. Pokušajte drugi unos.");
                }
            } else {
                // VAŽNO: za SVAKI pronađeni grad osveži današnji forecast
                foreach ($cities as $c) {
                    Artisan::call('weather:get-real', ['city' => $c->name]);
                }
            }

            // Sad ponovo učitaj sa svežim todaysForecast
            $cities = CitiesModel::with('todaysForecast')
                ->whereIn('id', $cities->pluck('id'))
                ->orderBy('name')
                ->get();

            return view('search_results', [
                'cities' => $cities,
                'q' => $q,
                'favoriteCityIds' => $favoriteCityIds,
            ]);
        }
        // Ako je upit prazan — prikaži sve
        $cities = CitiesModel::orderBy('name')->get();
        return view('search_results', ['cities' => $cities, 'q' => $q, 'favoriteCityIds' => $favoriteCityIds]);
    }
        public function show (CitiesModel $city)
        {
            $weatherService = new WeatherService();
            $jsonResponse = $weatherService->getSunsetAndSunrise($city->name);

            $sunrise = $jsonResponse['astronomy']['astro']['sunrise'];
            $sunset = $jsonResponse['astronomy']['astro']['sunset'];


            return view('city_forecast', compact('city', 'weatherService', 'sunrise', 'sunset'));
        }



}
