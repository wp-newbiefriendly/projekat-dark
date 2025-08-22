<?php

use App\Http\Controllers\ForecastCityController;
use App\Http\Controllers\WeatherController;
use App\Http\Middleware\AdminCheckMiddleware;
use Illuminate\Support\Facades\Route;

// Stranice za Usere
Route::get('/', function () {
    return view('welcome');
});
//FORECAST
Route::get('/forecast/{city}', [ForecastCityController::class, 'show']);

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
        Route::get('/cities/edit/{cities}', [WeatherController::class, 'showEditCityForm'])
         ->name('editCities');
        // Update nakon edit gradove
        Route::put('/cities/update/{cities}', [WeatherController::class, 'updateCity'])
        ->name('updateCities');
        // Izbrisi grad
        Route::get('/cities/delete/{cities}', [WeatherController::class, 'deleteCity'])
            ->name('deleteCities');
        // Force iz Trashed "delete"
        Route::get('/cities/force-delete/{id}', [WeatherController::class, 'forceDeleteCity'])
            ->name('forceDeleteCity');
        // Undo grad
        Route::get('/cities/undo/{cities}', [WeatherController::class, 'undoCity']);


    });

require __DIR__.'/auth.php';
