<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        $locations = Location::with('servers.pricing', 'servers.serverType')->get();

        $locations->each(function ($location) {
            $location->servers = $location->servers->sortBy(function ($server) {
                return $server->serverType->cpu_cores;
            });
        });

        return view('pages.server', compact('locations'));
    }
}
