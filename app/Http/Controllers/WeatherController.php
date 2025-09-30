<?php

namespace App\Http\Controllers;

use App\Models\CitiesModel;
use App\Models\WeatherModel;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    // Svi + obrisani gradovi
    public function showWeather()
    {
        $totalCities = WeatherModel::count();
        $perPage = request('per_page', 10); // default 10

        $sort = request('sort', 'asc'); // default stari -> novi

        $cities = CitiesModel::with('weather')
            ->orderBy('id', $sort) // OVO dodaje sortiranje
            ->paginate($perPage);
        $allCities = CitiesModel::all();

        $trashedWeather = WeatherModel::onlyTrashed()->get(); // obrisani

        return view('admin.cities', compact('cities', 'trashedWeather', 'totalCities', 'sort', 'allCities'));
    }
    // app/Http/Controllers/CityController.php
    public function quickUpdate(Request $request)
    {
        $request->validate([
            'city_id'     => 'required|exists:cities,id',
            'temperature' => 'required|numeric',
        ]);

        $data = WeatherModel::where(['city_id' => $request->get('city_id')])->first();
        $data->temperature = $request->get('temperature');
        $data->save();

        return back()->with('success', 'Azuriran grad "' . $data->city->name . '" sa temperaturom  "' . $data->temperature . '"');
    }



    public function allShowWeather()
    {
        $prognoza = WeatherModel::with('city')->get(); // optimizovano u modelu "city"
        return view('weather', compact('prognoza'));
    }

    public function showAddCityForm()
    {
        return view('admin.addCities');
    }

    public function storeCity(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'temperature' => 'nullable|numeric|min:-50|max:50',
        ]);
        // prvo sačuvaj u tabelu cities
        $city = new CitiesModel();
        $city->name = $request->name;
        $city->save();
        // zatim dodaj temperaturu u weather i poveži sa city_id
        $weather = new WeatherModel();
        $weather->city_id = $city->id;
        $weather->temperature = $request->temperature;
        $weather->save();

        return redirect('/admin/cities')->with('success', 'Grad uspesno dodat!');
    }

    public function showEditCityForm(WeatherModel $cities)
    {
        return view('admin.editCities', compact('cities'));
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
    public function forceDeleteCity($id)
    {
        $city = WeatherModel::withTrashed()->findOrFail($id);
        $city->forceDelete(); // briše zauvek

        return redirect()->back()->with('success', 'Grad trajno obrisan!');
    }

    public function undoCity($id)
    {
        $cities = WeatherModel::withTrashed()->with('city')->findOrFail($id);

        if ($cities->trashed()) {
            $cities->restore();
        }

        return redirect()->route('cities')
            ->with("success", "Grad '{$cities->city->name}' je vraćen!");
    }

}
