<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CityTemperatureModel extends Model
{
    use SoftDeletes;

    protected $table = "cities";

    protected $fillable =
        ['city', 'temperatures'];
}
