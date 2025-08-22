<?php

namespace App\Http\Controllers;

use App\Models\ForecastModel;
use App\Models\CitiesModel;

class ForecastCityController extends Controller
{
    public function show(CitiesModel $city)
    {
        return view('forecast', compact('city',));
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
