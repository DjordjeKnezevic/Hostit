<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Server;
use App\Models\Invoice;
use App\Models\Location;
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
            $servers = $this->fetchUserServers($user->id);
            $locations = Location::all();
            $states = ServerStatus::STATUSES;
        }
        return view('pages.profile', compact('servers', 'locations', 'states'));
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

    public function showRentedServers(Request $request)
    {
        $user = auth()->user();
        $servers = $this->fetchUserServers($user->id);
        $locations = Location::all();
        $states = ServerStatus::STATUSES;

        return view('components.servers', compact('servers', 'locations', 'states'));
    }

    public function filterRentedServers(Request $request)
    {
        $user = auth()->user();
        $stateFilter = $request->input('state');
        $locationFilter = $request->input('location');
        $sortOrder = $request->input('sort', 'desc');
        $servers = $this->fetchUserServers($user->id, $stateFilter, $locationFilter, $sortOrder);

        return view('components.servers-list', compact('servers'));
    }
    protected function fetchUserServers($id, $stateFilter = null, $locationFilter = null, $sortOrder = 'desc')
    {
        $servers = Subscription::where('user_id', $id)
            ->where('service_type', 'App\Models\Server')
            ->with(['service', 'pricing', 'serverStatus'])
            ->when($stateFilter, function ($query, $stateFilter) {
                return $query->whereHas('serverStatus', function ($query) use ($stateFilter) {
                    return $query->where('status', $stateFilter);
                });
            })
            ->when($locationFilter, function ($query, $locationFilter) {
                return $query->whereHas('service', function ($query) use ($locationFilter) {
                    return $query->where('location_id', $locationFilter);
                });
            })
            ->orderBy('start_date', $sortOrder)
            ->paginate(3);

        $servers->withPath(route('filter-servers'));
        // $servers = $servers->appends([
        //     'state' => $stateFilter,
        //     'location' => $locationFilter,
        //     'sort' => $sortOrder,
        // ]);
        $servers = $servers->appends(request()->except('page'));
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

    private function updateServerUptime(ServerStatus $serverStatus)
    {
        if ($serverStatus->status === 'good' && $serverStatus->last_started_at) {
            $uptimeInSeconds = now()->diffInSeconds($serverStatus->updated_at);
            $serverStatus->increment('uptime', $uptimeInSeconds);
        }
    }

    private function calculateHourlyCost(Subscription $subscription, $additionalUptime)
    {
        $hourlyRate = $subscription->pricing->where('period', 'hourly')->first()->price ?? 0;

        $hours = $additionalUptime / 3600;
        $cost = $hours * $hourlyRate;

        return $cost;
    }

    public function showInvoices(Request $request)
    {
        $user = auth()->user();

        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $invoices = Invoice::whereHas('subscription', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereYear('due_date', $year)
            ->whereMonth('due_date', $month)
            ->get();

        foreach ($invoices as $invoice) {
            $serverStatus = $invoice->subscription->serverStatus;
            $pricing = $invoice->subscription->service->pricing->where('period', 'hourly')->first();
            if ($serverStatus && $serverStatus->status === 'good' && $pricing) {
                $uptimeInSeconds = now()->diffInSeconds($serverStatus->updated_at);
                $serverStatus->increment('uptime', $uptimeInSeconds);

                $hours = $serverStatus->uptime / 3600;
                $invoice->amount_due += $hours * $pricing->price;
            }
        }

        $invoicesByLocation = $invoices->groupBy(function ($item) {
            return $item->subscription->service->location_id;
        })->map(function ($items) {
            return [
                'total_amount_due' => $items->sum('amount_due'),
                'invoices' => $items
            ];
        });

        return view('components.invoices', compact('invoicesByLocation', 'month', 'year'));
    }
}
