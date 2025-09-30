<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeatherModel extends Model
{
    use SoftDeletes;

    const TABLE_NAME = 'weather';

    protected $table = "weather";

    protected $fillable =
        ['city_id', 'temperature'];

    public function city()
    {
        return $this->belongsTo(CitiesModel::class,'city_id','id');

    }
}

