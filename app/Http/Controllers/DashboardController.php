<?php

namespace App\Http\Controllers;

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
}
