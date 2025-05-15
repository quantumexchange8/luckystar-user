<?php

namespace App\Http\Controllers;

use App\Models\TopUpProfile;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $wallets = request()->user()->wallets;
        return Inertia::render('Dashboard/Dashboard', [
            'wallets' => $wallets,
        ]);
    }

    public function getTopUpProfile() 
    {
        $topUpProfiles = TopUpProfile::where('status', 'active')->get();
      
        return response()->json([
            'topUpProfiles' => $topUpProfiles,
        ]);
    }
}
