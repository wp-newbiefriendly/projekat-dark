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
    public static function getColorByTemperature(int $temperature): string
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
    public static function getWeatherData(?string $type, int $temperature): array
    {
        $key  = strtolower(trim((string)$type));
        $icon = self::WEATHER_ICONS[$key] ?? self::WEATHER_ICONS['cloudy'];
        $color = self::getColorByTemperature($temperature);

        return ['icon' => $icon, 'color' => $color];
    }

    /** --- SVG ikone (18x18) --- */
    protected static function svgSunny(): string
    {
        return <<<SVG
<span class="wt-icon wt-sunny" title="Sunny" aria-label="Sunny">
<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" role="img">
  <path d="M6.76 4.84l-1.8-1.79-1.41 1.41 1.79 1.8 1.42-1.42zm10.48 0l1.8-1.79 1.41 1.41-1.79 1.8-1.42-1.42zM12 4V1h-0v3h0zm0 19v-3h0v3h0zM4 13H1v-2h3v2zm19 0h-3v-2h3v2zM6.76 19.16l-1.42 1.42-1.79-1.8 1.41-1.41 1.8 1.79zM19.45 18.37l1.79 1.8-1.41 1.41-1.8-1.79 1.42-1.42zM12 7a5 5 0 100 10 5 5 0 000-10z"/>
</svg>
</span>
SVG;
    }

    protected static function svgRainy(): string
    {
        return <<<SVG
<span class="wt-icon wt-rainy" title="Rainy" aria-label="Rainy">
<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" role="img">
  <path d="M6 14a5 5 0 010-10 6 6 0 0111.31 2.48A4 4 0 1120 14H6zM8 17l-1 3h2l1-3H8zm5 0l-1 3h2l1-3h-2zm5 0l-1 3h2l1-3h-2z"/>
</svg>
</span>
SVG;
    }

    protected static function svgSnowy(): string
    {
        return <<<SVG
<span class="wt-icon wt-snowy" title="Snowy" aria-label="Snowy">
<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" role="img">
  <path d="M11 2h2v5l2.5-2.5 1.4 1.4L13 10l3.9 3.6-1.4 1.4L13 12.9V22h-2v-9.1L8.5 15l-1.4-1.4L11 10 7.1 6.9l1.4-1.4L11 7V2z"/>
</svg>
</span>
SVG;
    }

    protected static function svgCloud(): string
    {
        return <<<SVG
<span class="wt-icon wt-cloud" title="Cloudy" aria-label="Cloudy">
<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" role="img">
  <path d="M6 19a5 5 0 010-10 6 6 0 0111.31 2.48A4 4 0 1120 19H6z"/>
</svg>
</span>
SVG;
    }
}
