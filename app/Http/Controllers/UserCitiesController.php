<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserCitiesController extends Controller
{
    public function favorite(int $city_id)
    {
        if (!auth()->check()) {
            return redirect()->back()->with('error', 'Morate se ulogovati!');
        }
        die('123');
    }
}
