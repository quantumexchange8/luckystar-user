<?php

namespace App\Http\Controllers;

use App\Models\AccountTypeHasLeverage;
use App\Models\Country;
use App\Models\TradingAccount;
use App\Models\TradingSubscription;
use Auth;
use Illuminate\Http\Request;

class SelectOptionController extends Controller
{
    public function getCountries()
    {
        $countries = Country::select('id', 'name', 'phone_code', 'iso2', 'emoji', 'translations', 'currency', 'currency_symbol')
            ->get();

        return response()->json([
            'countries' => $countries,
        ]);
    }

    public function getLeverages($id)
    {
        $leverages = AccountTypeHasLeverage::with('setting_leverage')
            ->where('account_type_id', $id)
            ->get();

        return response()->json([
            'leverages' => $leverages,
        ]);
    }

    public function getInvestorAccounts(Request $request)
    {
        $subscribedMetaLogins = TradingSubscription::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'active'])
            ->pluck('meta_login')
            ->toArray();

        $accounts = TradingAccount::where([
            'user_id' => Auth::id(),
            'account_type_id' => $request->type,
            'margin_leverage' => $request->leverage,
        ])
            ->when(!empty($subscribedMetaLogins), function ($query) use ($subscribedMetaLogins) {
                $query->whereNotIn('meta_login', $subscribedMetaLogins);
            })
            ->get();

        return response()->json([
            'accounts' => $accounts,
        ]);
    }
}
