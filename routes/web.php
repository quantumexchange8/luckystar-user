<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownlaodController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SelectOptionController;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return Redirect::route('login');
});

Route::get('locale/{locale}', function ($locale) {
    App::setLocale($locale);
    Session::put("locale", $locale);

    return redirect()->back();
});

Route::get('/get_countries', [SelectOptionController::class, 'getCountries'])->name('getCountries');
Route::post('deposit_callback', [WalletController::class, 'deposit_callback']);

Route::middleware('auth')->group(function () {
    // select option
    Route::get('/get_leverages/{id}', [SelectOptionController::class, 'getLeverages'])->name('getLeverages');
    Route::get('/getInvestorAccounts', [SelectOptionController::class, 'getInvestorAccounts'])->name('getInvestorAccounts');

    Route::get('deposit_return', [WalletController::class, 'deposit_return']);

    /**
     * ==============================
     *           Dashboard
     * ==============================
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/get_top_up_profile', [DashboardController::class, 'getTopUpProfile'])->name('getTopUpProfile');
    /**
     * ==============================
     *           Account
     * ==============================
     */
    Route::prefix('account')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('account');

        Route::post('createAccount', [AccountController::class, 'createAccount'])->name('createAccount');
        Route::get('get_trading_account_data', [AccountController::class, 'get_trading_account_data'])->name('get_trading_account_data');
        Route::post('accountDeposit', [AccountController::class, 'accountDeposit'])->name('accountDeposit');
    });

    /**
     * ==============================
     *           Strategy
     * ==============================
     */
    Route::prefix('strategy')->group(function () {
        Route::get('/', [StrategyController::class, 'index'])->name('strategy');
        Route::get('getStrategyData', [StrategyController::class, 'getStrategyData'])->name('strategy.getStrategyData');

        Route::post('investStrategy', [StrategyController::class, 'investStrategy'])->name('strategy.investStrategy');

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
            Route::get('/', [ReferralController::class, 'structureIndex'])->name('referral.structure');
            Route::get('/getStructureData', [ReferralController::class, 'getStructureData'])->name('referral.getStructureData');
        });

        // Sales
        Route::prefix('sales')->group(function () {
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
        Route::get('/get_trade_history', [ReportController::class, 'tradeHistoryIndex'])->name('report.trade_history');
        Route::get('/get_bonus', [ReportController::class, 'bonusIndex'])->name('report.bonus');
    });

    /**
     * ==============================
     *          Transaction
     * ==============================
     */
    Route::prefix('transaction')->group(function () {
        Route::get('/get_transaction', [TransactionController::class, 'index'])->name('transaction');
        Route::get('/get_cash_wallet_data', [TransactionController::class, 'getCashWalletData'])->name('transaction.getCashWalletData');
        Route::get('/get_bonus_wallet_data', [TransactionController::class, 'getBonusWalletData'])->name('transaction.getBonusWalletData');
        /**
         * ==============================
         *            Wallet
         * ==============================
         */
        Route::prefix('wallet')->group(function () {
            Route::post('/topUp', [WalletController::class, 'topUp'])->name('transaction.wallet.topUp');
            Route::post('/wallet_transfer', [WalletController::class, 'walletTransfer'])->name('transaction.wallet.transfer');
        });
    });

    /**
     * ==============================
     *          Investment
     * ==============================
     */
    Route::prefix('investment')->group(function () {
        Route::get('/', [InvestmentController::class, 'index'])->name('investment.index');
    });

    /**
     * ==============================
     *           Profile
     * ==============================
     */
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
});

require __DIR__ . '/auth.php';
