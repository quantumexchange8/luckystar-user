<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use App\Models\AccountTypeHasLeverage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function index()
    {
        $account_types = AccountType::where('status', 'active')
            ->get()
            ->toArray();

        $leverages = AccountTypeHasLeverage::with('accountType', 'settingLeverage')->get();


        return Inertia::render('Accounts/Listing/AccountListing', [
            'accountTypes' => $account_types,
            'leverageOptions' => $leverages,
        ]);
    }
}
