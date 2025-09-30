<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForecastModel extends Model
{
    const TABLE_NAME = 'forecasts';

    protected $table = 'forecasts';
    protected $fillable = ['city_id', 'temperature', 'forecast_date', 'weather_type', 'probability'];

    const WEATHERS = ["rainy", "sunny", "snowy", "cloudy"];

    public function city()
    {
        return $this->belongsTo(CitiesModel::class, 'city_id');
    }
    public function getTempClassAttribute()
    {
        return match (true) {
            $this->temperature <= 0 => 'temp-cold',
            $this->temperature <= 15 => 'temp-cool',
            $this->temperature <= 25 => 'temp-warm',
            default => 'temp-hot',
        };
    }


}
