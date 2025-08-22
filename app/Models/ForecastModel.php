<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForecastModel extends Model
{
    protected $table = 'forecast';
    protected $fillable = ['city_id', 'temperature', 'date'];

    public function city()
    {
        return $this->belongsTo(CitiesModel::class, 'city_id');
    }
}
