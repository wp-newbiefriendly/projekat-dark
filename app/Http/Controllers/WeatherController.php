<?php

namespace App\Http\Controllers;

use App\Models\WeatherModel;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    // Svi + obrisani gradovi
    public function showWeather()
    {
        $perPage = request('per_page', 10); // default 10
        $weather = WeatherModel::paginate($perPage);

        $trashedWeather = WeatherModel::onlyTrashed()->get(); // obrisani

        return view('cities', compact('weather', 'trashedWeather'));
    }

    public function allShowWeather()
    {
        $weather = WeatherModel::all();
        return view('weather', compact('weather'));
    }

    public function showAddCityForm()
    {
        return view('addCities');
    }

    public function storeCity(Request $request)
    {
        $request->validate([
            'city' => 'required|unique:weather|max:255',
            'temperatures' => 'nullable|numeric|min:-50|max:50'
        ]);

        WeatherModel::create($request->all());

        return redirect()->route(route: "cities")->with('success', 'Grad dodat!');
    }

    public function showEditCityForm(WeatherModel $weather)
    {
        return view('editCities', compact('weather'));
    }

    public function updateCity(Request $request, WeatherModel $weather)
    {
        $request->validate([
            'city' => 'required|unique:weather,city,' . $weather->id . '|max:255',
            'temperatures' => 'nullable|numeric|min:-50|max:50',
        ]);

        $weather->city = $request->city;
        $weather->temperatures = $request->temperatures;
        $weather->save();

        return redirect('/admin/cities')
            ->with('success', 'Grad ažuriran pod brojem ID: ' . $weather->id);
    }

    public function deleteCity($weather)
    {
        $singleCity = WeatherModel::findOrFail($weather);
        $singleCity->delete();

        session()->put('undoCity', $singleCity->id);
        return redirect()->back();
    }

    public function undoCity($id)
    {
        $weather = WeatherModel::withTrashed()->findOrFail($id);

        if ($weather->trashed()) {
            $weather->restore();
        }

        return redirect()->route('cities')->with('success', 'Grad je vraćen!');
    }
}
