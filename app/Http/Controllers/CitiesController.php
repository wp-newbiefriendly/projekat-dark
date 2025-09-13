<?php

namespace App\Http\Controllers;

use App\Models\CitiesModel;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10); // default 10
        $cities = CitiesModel::with('weather')
            ->paginate($perPage)
            ->withQueryString(); // da zadrži ?per_page i ostale parametre

        return view('cities', compact('cities'));
    }
}
