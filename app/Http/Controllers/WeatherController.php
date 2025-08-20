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
        $cities = WeatherModel::with('city')->paginate($perPage);
        $trashedWeather = WeatherModel::onlyTrashed()->get(); // obrisani

        return view('cities', compact('cities', 'trashedWeather'));
    }


    public function allShowWeather()
    {
        $prognoza = WeatherModel::all();
        return view('weather', compact('prognoza'));
    }

    public function showAddCityForm()
    {
        return view('addCities');
    }

    public function storeCity(Request $request)
    {
        $request->validate([
            'city_name' => 'required|string|max:255',
            'temperature' => 'nullable|numeric|min:-50|max:50',
        ]);

        WeatherModel::create($request->all());

        return redirect()->route(route: "cities")->with('success', 'Grad dodat!');
    }

    public function showEditCityForm(WeatherModel $cities)
    {
        return view('editCities', compact('cities'));
    }

    public function updateCity(Request $request, WeatherModel $cities)
    {
        $request->validate([
            'city_name' => 'required|string|max:255',
            'temperature' => 'nullable|numeric|min:-50|max:50',
        ]);

        $city = $cities->city;   // pristup relaciji
        $city->name = $request->city_name;
        $city->save();
        $cities->temperature = $request->temperature;
        $cities->save();

        return redirect('/admin/cities')
            ->with('success', 'Grad ažuriran pod brojem ID: ' . $cities->city_id);
    }

    public function deleteCity($cities)
    {
        $singleCity = WeatherModel::findOrFail($cities);
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
