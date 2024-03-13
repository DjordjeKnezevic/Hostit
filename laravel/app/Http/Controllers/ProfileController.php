<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Server;
use App\Models\Invoice;
use App\Models\Pricing;
use App\Models\ServerStatus;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\RegionResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $servers = null;
        $user = auth()->user();
        if (Auth::user()->hasVerifiedEmail()) {
            $servers = $this->fetchAllUserServers($user->id);
        }
        return view('pages.profile', compact('servers'));
    }

    public function processRenting(Request $request)
    {

        $validatedData = $request->validate([
            'location' => 'required|exists:locations,id',
            'server' => 'required|exists:servers,id',
            'pricing' => 'required|exists:pricing,id',
        ]);

        DB::transaction(function () use ($validatedData) {


            $user = auth()->user();
            $server = Server::findOrFail($validatedData['server']);
            $pricing = Pricing::findOrFail($validatedData['pricing']);

            $regionResource = RegionResource::where('location_id', $server->location_id)->firstOrFail();

            if (
                $server->serverType->cpu_cores > $regionResource->remaining_cpu_cores ||
                $server->serverType->ram > $regionResource->remaining_ram ||
                $server->serverType->storage > $regionResource->remaining_storage ||
                $server->serverType->network_speed > $regionResource->remaining_bandwidth
            ) {
                throw new \Exception('Not enough resources available in this region.');
            }

            $regionResource->remaining_cpu_cores -= $server->serverType->cpu_cores;
            $regionResource->remaining_ram -= $server->serverType->ram;
            $regionResource->remaining_storage -= $server->serverType->storage;
            $regionResource->remaining_bandwidth -= $server->serverType->network_speed;
            $regionResource->save();


            $subscription = Subscription::create([
                'user_id' => $user->id,
                'service_id' => $server->id,
                'service_type' => 'App\Models\Server',
                'pricing_id' => $pricing->id,
                'start_date' => now(),
            ]);

            $dueDate = now();
            $status = 'pending';

            if ($pricing->period === 'hourly') {
                $dueDate = now()->endOfMonth();
            } else {
                $status = 'paid';
            }

            Invoice::create([
                'subscription_id' => $subscription->id,
                'amount_due' => $pricing->price,
                'amount_paid' => $pricing->period === 'hourly' ? 0 : $pricing->price,
                'due_date' => $dueDate,
                'status' => $status,
            ]);

            ServerStatus::create([
                'subscription_id' => $subscription->id,
                'status' => 'good',
                'last_started_at' => now(),
            ]);
        });

        return redirect()->route('profile')->with('status', 'Server rental successful.');
    }

    public function showRentedServers()
    {
        $user = auth()->user();
        $servers = $this->fetchAllUserServers($user->id);

        return view('components.servers', compact('servers'));
    }

    protected function fetchAllUserServers($id)
    {
        $servers = Subscription::where('user_id', $id)
            ->where('service_type', 'App\Models\Server')
            ->with(['service', 'pricing', 'serverStatus'])
            ->get();

        return $servers;
    }

    public function startServer(Request $request, $server)
    {
        $subscription = Subscription::where('user_id', auth()->id())->findOrFail($server);
        $status = $subscription->serverStatus;
        $now = Carbon::now();

        if ($status->last_stopped_at) {
            $downtime = $now->diffInSeconds($status->last_stopped_at);
            $status->increment('downtime', $downtime);
        }

        $status->update([
            'status' => 'good',
            'last_started_at' => $now,
            'last_stopped_at' => null,
        ]);

        $regionResource = RegionResource::where('location_id', $subscription->service->location_id)->first();
        $regionResource->remaining_cpu_cores -= $subscription->service->serverType->cpu_cores;
        $regionResource->remaining_ram -= $subscription->service->serverType->ram;
        $regionResource->remaining_storage -= $subscription->service->serverType->storage;
        $regionResource->remaining_bandwidth -= $subscription->service->serverType->network_speed;
        $regionResource->save();

        return view('partials.server-status', ['subscription' => $subscription]);
    }

    public function stopServer(Request $request, $server)
    {
        $subscription = Subscription::where('user_id', auth()->id())->findOrFail($server);
        $status = $subscription->serverStatus;
        $now = Carbon::now();

        if ($status->last_started_at) {
            $uptime = $now->diffInSeconds($status->last_started_at);
            $status->increment('uptime', $uptime);
        }

        $status->update([
            'status' => 'stopped',
            'last_stopped_at' => $now,
        ]);

        $regionResource = RegionResource::where('location_id', $subscription->service->location_id)->first();
        $regionResource->remaining_cpu_cores += $subscription->service->serverType->cpu_cores;
        $regionResource->remaining_ram += $subscription->service->serverType->ram;
        $regionResource->remaining_storage += $subscription->service->serverType->storage;
        $regionResource->remaining_bandwidth += $subscription->service->serverType->network_speed;
        $regionResource->save();

        return view('partials.server-status', ['subscription' => $subscription]);
    }

    public function restartServer(Request $request, $server)
    {
        $subscription = Subscription::where('user_id', auth()->id())->findOrFail($server);
        $subscription->serverStatus->update(['status' => 'pending']);

        return view('partials.server-status', ['subscription' => $subscription]);
    }

    public function terminateServer(Request $request, $server)
    {
        $subscription = Subscription::where('user_id', auth()->id())->findOrFail($server);
        $pricing = $subscription->pricing;

        $startDate = Carbon::parse($subscription->start_date);

        $endDate = now();
        if ($pricing->period === 'monthly') {
            $endDate = $startDate->addMonth();
        } elseif ($pricing->period === 'yearly') {
            $endDate = $startDate->addYear();
        }

        DB::transaction(function () use ($subscription, $endDate) {
            $subscription->update(['end_date' => $endDate]);
            $subscription->serverStatus->update(['status' => 'terminated']);
        });

        // session()->flash('status', 'Server terminated successfully');
        // session()->flash('alert-type', 'success');

        return view('partials.server-status', ['subscription' => $subscription]);
    }
}
