<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CitiesModel extends Model
{
    protected $table = 'cities';

    protected $fillable = ['name'];

    // Jedan grad ima jedan weather zapis (temperature)
    public function weather()
    {
        return $this->hasOne(WeatherModel::class, 'city_id');
    }

    // Jedan grad ima vise prognoza (forecasts) - city_id, temperature, date
    public function forecasts()
    {
        return $this->hasMany(ForecastModel::class, 'city_id')
            ->orderBy('forecast_date'); // datumi sortirani unutar grada
    }
    public function todaysForecast()
    {
        return $this->hasOne(ForecastModel::class, 'city_id')
            ->whereDate('forecast_date', Carbon::now());
    }

}
