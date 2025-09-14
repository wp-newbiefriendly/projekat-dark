<?php

namespace App\Http\Controllers;

use App\Models\CitiesModel;
use App\Models\UserCitiesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserCitiesController extends Controller
{
    public function favorite(Request $request, int $city_id)
    {
        if (!auth()->check()) {
            return back()->with('error','Morate se ulogovati!');
        }

        $city = CitiesModel::find($city_id);
        if (!$city) {
            return back()->with('error','Grad ne postoji.');
        }

        $userId = auth()->id();

        $fav = UserCitiesModel::where('user_id', $userId)
            ->where('city_id', $city->id)
            ->first();

        if ($fav) {
            $fav->delete();
            return back()->with('success', "Grad '{$city->name}' uklonjen iz favorita.");
        }

        UserCitiesModel::create([
            'user_id' => $userId,
            'city_id' => $city->id,
        ]);

        return back()->with('success', "Grad '{$city->name}' dodat u favorite!");
    }
}
