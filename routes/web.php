<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SelectOptionController;
use App\Http\Controllers\StrategyController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

Route::get('/', function () {
    return Redirect::route('login');
});

Route::get('locale/{locale}', function ($locale) {
    App::setLocale($locale);
    Session::put("locale", $locale);

    return redirect()->back();
});

Route::get('/get_countries', [SelectOptionController::class, 'getCountries'])->name('getCountries');

Route::middleware('auth')->group(function () {
    // select option
    Route::get('/get_leverages/{id}', [SelectOptionController::class, 'getLeverages'])->name('getLeverages');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /**
     * ==============================
     *           Account
     * ==============================
     */
    Route::prefix('account')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('account');

        Route::post('createAccount', [AccountController::class, 'createAccount'])->name('createAccount');
    });

     /**
     * ==============================
     *           Account
     * ==============================
     */
    Route::prefix('strategy')->group(function () {
        Route::get('/', [StrategyController::class, 'index'])->name('strategy');
    });

    /**
     * ==============================
     *           Profile
     * ==============================
     */
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
});

require __DIR__.'/auth.php';
