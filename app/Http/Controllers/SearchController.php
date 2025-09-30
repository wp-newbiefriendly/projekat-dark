<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CitiesModel;
use App\Models\UserCitiesModel;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->input('city', ''));

        $favoriteCityIds = auth()->check() ? auth()->user()->cityFavorites->pluck('city_id')->toArray() : [];

        // 1) Ako je prazno → prikaži SVE gradove
        if ($q === '') {
            $cities = CitiesModel::orderBy('name')->get();
            return view('search_results',
                ['cities' => $cities, 'q' => $q, 'favoriteCityIds' => $favoriteCityIds]);
        }

        // 2) Pretraga "sadrži" (case-insensitive i bezbedno)
        $needle = mb_strtolower($q);
        $cities = CitiesModel::with('todaysForecast')
            ->whereRaw('LOWER(name) LIKE ?', ["%{$needle}%"])
            ->orderBy('name')
            ->get(); // ili paginate()->withQueryString()                                            // ili paginate(100)->withQueryString()

        // 3) Ako nema rezultata → nazad na početnu sa porukom
        if ($cities->isEmpty()) {
            return redirect()->route('home')
                ->with('error', 'Grad nije pronađen. Pokušajte drugi unos.');
        }


        return view('search_results', compact('cities', 'q', 'favoriteCityIds'));
    }

}
