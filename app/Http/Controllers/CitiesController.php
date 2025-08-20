<?php

namespace App\Http\Controllers;

use App\Models\CitiesModel;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
   public function index() {
       $cities = CitiesModel::paginate(10);

       return view('cities', compact('cities'));
   }
}
