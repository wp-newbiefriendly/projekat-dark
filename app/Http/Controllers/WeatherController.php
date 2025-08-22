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
        $cities = WeatherModel::with('city')->paginate($perPage);
        $trashedWeather = WeatherModel::onlyTrashed()->get(); // obrisani

        return view('cities', compact('cities', 'trashedWeather', 'totalCities'));
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
