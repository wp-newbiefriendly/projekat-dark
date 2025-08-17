<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminCheckMiddleware;
use App\Http\Controllers\CityTemperaturesController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;

// Stranice za Usere
Route::get('/', function () {
    return view('welcome');
});

// Login / Logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Auth Stranice:
// Prognoza
Route::get('/prognoza', [App\Http\Controllers\CityTemperaturesController::class, 'allShowPrognoza'])
    ->name('prognoza') ->middleware('auth');

// Stranice za Admine
Route::middleware(['auth', AdminCheckMiddleware::class])
    ->prefix('admin')
    ->group(function () {
        // Admin - CitiesTemperatures
        // Svi gradovi
        Route::get('/cities', [CityTemperaturesController::class, 'showCities'])
            ->name('cities');
        // Dodaj gradove
        Route::get('/add-cities', [CityTemperaturesController::class, 'showAddCityForm'])
            ->name('addCities');
        // Azuriraj u bazu 'post'
        Route::post('/add-cities', [CityTemperaturesController::class, 'storeCity']);
        // Edit gradove
        Route::get('/cities/edit/{city}', [CityTemperaturesController::class, 'showEditCityForm'])
         ->name('editCities');
        // Update nakon edit gradove
        Route::put('/cities/update/{city}', [CityTemperaturesController::class, 'updateCity'])
        ->name('updateCities');
        // Izbrisi grad
        Route::get('/cities/delete/{city}', [CityTemperaturesController::class, 'deleteCity'])
            ->name('deleteCities');
        // Undo grad
        Route::get('/cities/undo/{city}', [CityTemperaturesController::class, 'undoCity']);

    });

require __DIR__.'/auth.php';
