<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function index()
    {
        $account_types = AccountType::where('status', 'active')
            ->get()
            ->toArray();

        return Inertia::render('Accounts/Listing/AccountListing', [
            'accountTypes' => $account_types
        ]);
    }
}
