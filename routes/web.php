<?php

use App\Http\Controllers\ForecastCityController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminCheckMiddleware;
use App\Http\Controllers\WeatherController;

// Stranice za Usere
Route::get('/', function () {
    return view('welcome');
});
//FORECAST
Route::get('/forecast/{city}', [ForecastCityController::class, 'forecastCity']);

// Auth Stranice:
// Prognoza
Route::get('/prognoza', [App\Http\Controllers\WeatherController::class, 'allShowWeather'])
    ->name('weather') ->middleware('auth');

// Stranice za Admine
Route::middleware(['auth', AdminCheckMiddleware::class])
    ->prefix('admin')
    ->group(function () {
        // Admin - CitiesTemperatures
        // Svi gradovi
        Route::get('/cities', [WeatherController::class, 'showWeather'])
            ->name('cities');
        // Dodaj gradove
        Route::get('/add-cities', [WeatherController::class, 'showAddCityForm'])
            ->name('addCities');
        // Azuriraj u bazu 'post'
        Route::post('/add-cities', [WeatherController::class, 'storeCity']);
        // Edit gradove
        Route::get('/cities/edit/{weather}', [WeatherController::class, 'showEditCityForm'])
         ->name('editCities');
        // Update nakon edit gradove
        Route::put('/cities/update/{weather}', [WeatherController::class, 'updateCity'])
        ->name('updateCities');
        // Izbrisi grad
        Route::get('/cities/delete/{weather}', [WeatherController::class, 'deleteCity'])
            ->name('deleteCities');
        // Undo grad
        Route::get('/cities/undo/{weather}', [WeatherController::class, 'undoCity']);


    });

require __DIR__.'/auth.php';
