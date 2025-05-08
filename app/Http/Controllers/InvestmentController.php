<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class InvestmentController extends Controller
{
    public function index()
    {
        return Inertia::render('Investment/Listing/InvestmentTable');
    }
}
