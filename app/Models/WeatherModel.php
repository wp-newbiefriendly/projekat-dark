<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeatherModel extends Model
{
    use SoftDeletes;

    protected $table = "weather";

    protected $fillable =
        ['city_id', 'temperature'];

    public function city()
    {
        return $this->hasOne(related: CitiesModel::class, foreignKey: "id", localKey: "city_id");
    }
}

