<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        $locations = Location::with('servers.pricing')->get();
        return view('pages.server', compact('locations'));
    }
}
