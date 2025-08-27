<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CitiesModel;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->input('city', ''));

        // 1) Ako je prazno → prikaži SVE gradove
        if ($q === '') {
            $cities = CitiesModel::orderBy('name')->get();          // ili paginate(200)
            return view('search_results', ['cities' => $cities, 'q' => $q]);
        }

        // 2) Pretraga "sadrži" (case-insensitive i bezbedno)
        $needle = mb_strtolower($q);
        $cities = CitiesModel::whereRaw('LOWER(name) LIKE ?', ["%{$needle}%"])
            ->orderBy('name')
            ->get();                                                // ili paginate(100)->withQueryString()

        // 3) Ako nema rezultata → nazad na početnu sa porukom
        if ($cities->isEmpty()) {
            return redirect()->route('home')
                ->with('error', 'Grad nije pronađen. Pokušajte drugi unos.');
        }

        return view('search_results', ['cities' => $cities, 'q' => $q]);
    }

}
