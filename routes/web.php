<?php

use App\Http\Controllers\ForecastCityController;
use App\Http\Controllers\WeatherController;
use App\Http\Middleware\AdminCheckMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserCitiesController;


// Stranice za Usere
Route::get('/', [WeatherController::class, 'showUserFavorites'])
    ->name('welcome');

Route::get('/forecast/search', [SearchController::class, 'search'])->name('search.city');
Route::get('/forecast/{name}', [SearchController::class, 'show'])
    ->where('name', '.*')
    ->name('city.forecast');

// Auth Stranice:
// Prognoza
Route::get('/prognoza', [App\Http\Controllers\WeatherController::class, 'allShowWeather'])
    ->name('weather') ->middleware('auth');

// User Cities
Route::get('/user-cities/favorite/{city_id}', [UserCitiesController::class, 'favorite'])
->name('city_favorite');


// Stranice za Admine
Route::middleware(['auth', AdminCheckMiddleware::class])
    ->group(function () {
        // Admin - CitiesTemperatures
        // Svi gradovi
        Route::get('/admin/cities', [WeatherController::class, 'showWeather'])
            ->name('cities');
        //FORECAST
        Route::get('/admin/forecast', [ForecastCityController::class, 'show']);
        Route::post('/admin/forecast/update', [ForecastCityController::class, 'update'])
            ->name('forecasts.update');
        // END FORECAST
        // Dodaj gradove
        Route::get('/admin/add-cities', [WeatherController::class, 'showAddCityForm'])
            ->name('addCities');
        // Azuriraj u bazu 'post'
        Route::post('/admin/add-cities', [WeatherController::class, 'storeCity']);
        // Edit gradove
        Route::get('/admin/cities/edit/{cities}', [WeatherController::class, 'showEditCityForm'])
         ->name('editCities');
        // Update nakon edit gradove
        Route::put('/cities/update/{cities}', [WeatherController::class, 'updateCity'])
        ->name('updateCities');
        // Quick update
        Route::post('/admin/cities/quick-update', [WeatherController::class, 'quickUpdate'])
            ->name('cities.quickUpdate');
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
