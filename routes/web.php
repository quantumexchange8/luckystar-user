<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownlaodController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\ReportController;
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
        Route::get('get_trading_account_data', [AccountController::class, 'get_trading_account_data'])->name('get_trading_account_data');
    });

    /**
     * ==============================
     *           Strategy
     * ==============================
     */
    Route::prefix('strategy')->group(function () {
        Route::get('/', [StrategyController::class, 'index'])->name('strategy');

        Route::prefix('detail')->group(function () {
            Route::get('/get_strategy_detail', [StrategyController::class, 'strategyDetail'])->name('strategy.detail.strategyDetail');
        });
    });

    /**
     * ==============================
     *           Referral
     * ==============================
     */
    Route::prefix('referral')->group(function () {

        // Structure
        Route::prefix('structure')->group(function () {
            Route::get('/get_structure', [ReferralController::class, 'structureIndex'])->name('referral.structure.structureIndex');
        });

        // Sales
        Route::prefix('sakes')->group(function () {
            Route::get('/get_sales', [ReferralController::class, 'salesIndex'])->name('referral.sales.salesIndex');
        });
    });

    /**
     * ==============================
     *        Download Center
     * ==============================
     */
    Route::prefix('download')->group(function () {
        Route::get('/', [DownlaodController::class, 'index'])->name('download.index');
    });

    /**
     * ==============================
     *            Report
     * ==============================
     */
    Route::prefix('report')->group(function () {
        Route::get('/get_transaction', [ReportController::class, 'transactionIndex'])->name('report.transaction');
        Route::get('/get_trade_history', [ReportController::class, 'tradeHistoryIndex'])->name('report.trade_history');
        Route::get('/get_bonus', [ReportController::class, 'bonusIndex'])->name('report.bonus');
    });

    /**
     * ==============================
     *           Profile
     * ==============================
     */
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
});

require __DIR__.'/auth.php';
