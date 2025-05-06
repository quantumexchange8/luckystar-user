<?php

namespace App\Http\Controllers;

use App\Models\AccountTypeHasLeverage;
use App\Models\Country;
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
}
