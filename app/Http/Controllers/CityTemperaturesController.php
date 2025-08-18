<?php

namespace App\Http\Controllers;

use App\Models\CityTemperatureModel;
use Illuminate\Http\Request;

class CityTemperaturesController extends Controller
{
    // Svi + obrisani gradovi
    public function showCities()
    {
        $cities = CityTemperatureModel::all(); // aktivni
        $trashedCities = CityTemperatureModel::onlyTrashed()->get(); // obrisani

        return view('cities', compact('cities', 'trashedCities'));
    }
    public function allShowPrognoza()
    {
        $cities = CityTemperatureModel::all();
        return view('weather', compact('cities'));
    }

    public function showAddCityForm() {
        return view('addCities');
    }
    public function storeCity(Request $request) {
        $request->validate([
            'city' => 'required|unique:cities|max:255',
            'temperatures' => 'nullable|numeric|min:-50|max:50'
        ]);

        \App\Models\CityTemperatureModel::create($request->all());

        return redirect()->route(route:"cities")->with('success', 'Grad dodat!');
    }

    public function showEditCityForm(CityTemperatureModel $city)
    {

        return view('editCities', compact('city'));
    }
    public function updateCity(Request $request, CityTemperatureModel $city)
    {
        $request->validate([
            'city' => 'required|unique:cities,city,' . $city->id . '|max:255',
            'temperatures' => 'nullable|numeric|min:-50|max:50'
        ]);

        // OVDE se ažuriraju podaci iz forme
        $city->city = $request->city;
        $city->temperatures = $request->temperatures;
        $city->save();

        return redirect('/admin/cities')
            ->with('success', 'Grad ažuriran pod brojem ID: ' . $city->id);
    }
    public function deleteCity($city)
    {
        $singleCity = CityTemperatureModel::findOrFail($city);
        $singleCity->delete();

        session()->put('undoCity', $singleCity->id);
        return redirect()->back();
    }
    public function undoCity($id)
    {
        $city = CityTemperatureModel::withTrashed()->findOrFail($id);

        if ($city->trashed()) {
            $city->restore();
        }

        return redirect()->route('cities')->with('success', 'Grad je vraćen!');
    }
}
