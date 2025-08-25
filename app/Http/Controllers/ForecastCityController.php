<?php

namespace App\Http\Controllers;

use App\Models\ForecastModel;
use App\Models\CitiesModel;
use App\Models\WeatherModel;
use Illuminate\Http\Request;

class ForecastCityController extends Controller
{
    public function show(CitiesModel $city)
    {
        // Dodat: Sort, PerPage, WeatherType, allcities sa forecast relacijom za "forecast.blade"
        $sort = request('sort', 'asc'); // default stari -> novi
        $perPage = request('per_page', 12); // default 10
        $allCities = CitiesModel::with('forecasts')
            ->orderBy('id', $sort) // OVO dodaje sortiranje
            ->paginate($perPage);
        return view('admin.forecast', compact('city','allCities', 'sort'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'city_id'     => 'required|exists:cities,id',
            'temperature' => 'required|numeric|min:-50|max:50|',
            'weather_type' => 'required|string',
            'probability' => 'numeric|min:0|max:100',
            'forecast_date' => 'required|date',
        ]);

        ForecastModel::create($request->all());

        return back()->with('success', 'Azurirano');
    }

    public function forecastCity($city)
    {
        $city = ucfirst(strtolower($city));

        // Naš niz gradova sa prognozom za 5 dana
        $prognoza = [
            "Beograd" => [22, 24, 25, 20, 18],
            "Sarajevo" => [20, 24, 22, 22, 25],
            "Nis" => [30, 31, 29, 28, 27],
            "Zajecar" => [32, 30, 28, 29, 31],
        ];

        // Provera da li grad postoji
        if (array_key_exists($city, $prognoza)) {  // $city - je kljuc - "grad"
            $temperatures = $prognoza[$city];     // objasnjavamo da je $temperatures - value - ili temperatura
            $error = null;
        } else {
            $temperatures = [];
            $error = "Grad '{$city}' ne postoji u prognozi!";
        }

        $days = [];
        for ($i = 0; $i < 5; $i++) {
            $days[] = now()->addDays($i)->locale('sr')->translatedFormat('l');
        }

        return view('forecast', [
            'city' => $city,
            'temperatures' => $temperatures,
            'error' => $error,
            'days' => $days
        ]);

    }
}
