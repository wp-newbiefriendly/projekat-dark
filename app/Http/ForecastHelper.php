<?php

namespace App\Http;

class ForecastHelper
{
    /** Mapiranje tipa vremena → Font Awesome klase (ikonica) */
    const WEATHER_ICONS = [
        'sunny'  => 'fa-solid fa-sun text-warning',
        'rainy'  => 'fa-solid fa-cloud-rain text-primary',
        'snowy'  => 'fa-solid fa-snowflake text-info',
        'cloudy' => 'fa-solid fa-cloud text-secondary', // fallback
    ];

    /** Boja bedža po temperaturi (koristi se kao inline CSS var(--temp-color)) */
    public static function getColorByTemperature($temperature)
    {
        if ($temperature <= 0)          return 'lightblue';
        if ($temperature <= 15)         return 'blue';
        if ($temperature <= 25)         return 'green';
        return 'red';
    }

    /**
     * Vrati podatke za prikaz (ikonica + boja) na osnovu weather_type + temperature
     * Primer povratka: ['icon' => 'fa-solid fa-sun text-warning', 'color' => 'green']
     */
    public static function getWeatherData($type, int $temperature)
    {
//        if(in_array($type, self::WEATHER_ICONS))
//        {
//            return self::WEATHER_ICONS[$type];
//        }
        $key  = strtolower(trim((string)$type));
        $icon = self::WEATHER_ICONS[$key] ?? self::WEATHER_ICONS['cloudy'];
        $color = self::getColorByTemperature($temperature);

        return ['icon' => $icon, 'color' => $color];
    }
}
