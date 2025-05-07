<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ReferralController extends Controller
{
    public function structureIndex()
    {
        return Inertia::render('Referral/Listing/Structure/StructureListing');
    }

    public function salesIndex()
    {
        return Inertia::render('Referral/Listing/Sales/SalesListing');
    }
}
