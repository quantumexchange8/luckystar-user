<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DownlaodController extends Controller
{
    public function index() {
        return Inertia::render('Download/Listing/DownloadListing');
    }
}
