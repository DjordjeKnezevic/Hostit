<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\Pricing;
use App\Models\Location;
use App\Models\Subscription;
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

    public function rent(Request $request)
    {
        $locations = Location::with('servers.serverType', 'servers.pricing')->get();
        $selectedServerId = $request->query('server');
        $selectedLocationId = null;
        $selectedPricing = null;
        $selectedServer = null;
        $servers = null;
        $pricingPlans = null;

        if ($selectedServerId) {
            $selectedServer = Server::with('location', 'serverType', 'pricing')->find($selectedServerId);
            $selectedLocationId = $selectedServer->location_id;
            $selectedPricing = $selectedServer->pricing->first();

            $servers = Server::where('location_id', $selectedLocationId)->get();
            $pricingPlans = $selectedServer->pricing;
        }

        return view('pages.rent-server', compact('locations', 'servers', 'pricingPlans', 'selectedServer', 'selectedLocationId', 'selectedPricing'));
    }


    public function getServers(Request $request)
    {
        $locationId = $request->query('location');
        $servers = Server::where('location_id', $locationId)->get();

        if (!$locationId) {
            return '<option value="">Please select a location first</option>';
        } else if ($servers->isEmpty()) {
            return '<option value="">No servers available for this location</option>';
        }

        return view('partials.servers', compact('servers'))->render();
    }


    public function getServerPricing(Request $request)
    {
        $serverId = $request->query('server');
        $pricingPlans = Pricing::where('service_id', $serverId)->where('service_type', 'App\Models\Server')->get();

        if (!$serverId) {
            return '<option value="">Please select a server first</option>';
        } else if ($pricingPlans->isEmpty()) {
            return '<option value="">No pricing plans available for this server</option>';
        }

        return view('partials.pricing', compact('pricingPlans'))->render();
    }

    public function locationDetails($locationId)
    {
        $location = Location::findOrFail($locationId);
        return view('partials.location_details', compact('location'));
    }

    public function serverDetails($serverId)
    {
        $server = Server::with('serverType')->findOrFail($serverId);
        return view('partials.server_details', compact('server'));
    }

    public function pricingDetails($pricingId)
    {
        $pricing = Pricing::findOrFail($pricingId);
        return view('partials.pricing_details', compact('pricing'));
    }


    public function processRenting(Request $request)
    {
        $validatedData = $request->validate([
            'location' => 'required|exists:locations,id',
            'server' => 'required|exists:servers,id',
            'pricing' => 'required|exists:pricing,id'
        ]);

        $user = auth()->user();
        $server = Server::findOrFail($validatedData['server']);
        $pricing = Pricing::findOrFail($validatedData['pricing']);

        $subscription = new Subscription([
            'user_id' => $user->id,
            'service_id' => $server->id,
            'service_type' => 'App\Models\Server',
            'pricing_id' => $pricing->id,
            'start_date' => now(),
        ]);

        $subscription->save();

        return redirect()->route('profile')->with('status', 'Server rental successful.');
    }
}
