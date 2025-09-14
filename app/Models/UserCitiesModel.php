<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCitiesModel extends Model
{
    protected $table = 'user_cities';

    protected $fillable = ['city_id', 'user_id'];
}

//public function usercitiesCities()
//{
//    return $this->belongsTo(CitiesModel::class, 'city_id');
//}
//public function usercitiesUsers()
//{
//    return $this->belongsTo(User::class, 'user_id');
//}
