<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class StrategyController extends Controller
{
    public function index()
    {
        return Inertia::render('Strategy/Listing/StrategyListing');
    }

    public function strategyDetail()
    {
        return Inertia::render('Strategy/Listing/Detail/StrategyDetail');
    }
}
