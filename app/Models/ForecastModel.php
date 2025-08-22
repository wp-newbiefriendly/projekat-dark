<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForecastModel extends Model
{
    protected $table = 'forecasts';
    protected $fillable = ['city_id', 'temperature', 'forecast_date'];

    public function city()
    {
        return $this->belongsTo(CitiesModel::class, 'city_id');
    }
}
