<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function transactionIndex()
    {
        return Inertia::render('Report/Listing/Transaction/TransactionListing');
    }

    public function TradeHistoryIndex()
    {
        return Inertia::render('Report/Listing/TradeHistory/TradeHistoryListing');
    }

    public function BonusIndex()
    {
        return Inertia::render('Report/Listing/Bonus/BonusListing');
    }
}
