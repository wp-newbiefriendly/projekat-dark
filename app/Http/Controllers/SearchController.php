<?php

namespace App\Http\Controllers;

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
            // Prvo pokušaj da nađeš u bazi po delimičnom nazivu kao i do sada
            $needle = mb_strtolower($q);
            $cities = CitiesModel::with('todaysForecast')
                ->whereRaw('LOWER(name) LIKE ?', ["%{$needle}%"])
                ->orderBy('name')
                ->get();

            // Ako NIJE nađeno ništa u bazi, proveri API – i samo ako API potvrdi, kreiraj grad
            if ($cities->isEmpty()) {
                $resp = Http::get(env('WEATHER_API_URL') . 'v1/forecast.json', [
                    'key' => env('WEATHER_API_KEY'),
                    'q' => $q,
                    'days' => 1,
                    'aqi' => 'no',
                ]);

                $data = $resp->json();
                if ($resp->ok() && !isset($data['error'])) {
                    $apiName = $data['location']['name'] ?? $q; // normalizovano ime iz API

                    // Kreiraj grad ako baš ne postoji case-insensitive sa tim API imenom
                    $city = CitiesModel::whereRaw('LOWER(name) = ?', [mb_strtolower($apiName)])->first();
                    if (!$city) {
                        $city = CitiesModel::create(['name' => $apiName]);
                    }

                    // Opcija: odmah povuci današnji forecast za taj grad
                    Artisan::call('weather:get-real', ['city' => $city->name]);

                    // Učini da se prikaže tek kreirani grad u rezultatima
                    $cities = collect([$city->fresh('todaysForecast')]);
                } else {
                    // API ga ne poznaje → ne upisuj u bazu, samo poruka korisniku
                    return redirect()->route('welcome')
                        ->with('error', "Grad '{$q}' ne postoji. Pokušajte drugi unos.");
                }
            }

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
            $response = Http::get(env("WEATHER_API_URL")."v1/astronomy.json", [
                'key' => env('WEATHER_API_KEY'),
                'q' => $city->name,
                'aqi' => 'no',
                'lang' => 'sr'
            ]);

            $jsonResponse = $response->json();

            $sunrise = $jsonResponse['astronomy']['astro']['sunrise'];
            $sunset = $jsonResponse['astronomy']['astro']['sunset'];


            return view('city_forecast', compact('city', 'response', 'sunrise', 'sunset'));
        }



}
