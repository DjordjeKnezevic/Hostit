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
                'amount_due' => 0,
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

    public function showInvoicePage(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $sortBy = $request->input('sortBy', 'total_amount_due');

        $distinctDates = $this->getDistinctDates();
        $invoicesByLocation = $this->fetchInvoices($year, $month, $sortBy);
        list($totalDue, $totalPaid) = $this->calculateTotals($invoicesByLocation);

        $locations = Location::all()->keyBy('id');

        $formattedDates = $distinctDates->mapWithKeys(function ($date) {
            $carbonDate = Carbon::create($date->year, $date->month);
            return [$date->year . '-' . $date->month => $carbonDate->format('F Y')];
        });

        return view('components.invoices', compact(
            'invoicesByLocation',
            'distinctDates',
            'month',
            'year',
            'totalDue',
            'totalPaid',
            'locations',
            'formattedDates'
        ));
    }

    public function updateInvoiceList(Request $request)
    {
        $monthYear = $request->input('monthYear', now()->format('Y-m'));
        [$year, $month] = explode('-', $monthYear);
        $sortBy = $request->input('sortBy', 'total_amount_due');

        $invoicesByLocation = $this->fetchInvoices($year, $month, $sortBy);
        list($totalDue, $totalPaid) = $this->calculateTotals($invoicesByLocation);

        $locations = Location::all()->keyBy('id');

        return view('components.invoices-list', compact('invoicesByLocation', 'totalDue', 'totalPaid', 'locations'));
    }

    private function getDistinctDates()
    {
        return  Invoice::selectRaw('YEAR(due_date) as year, MONTH(due_date) as month')
            ->distinct()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
    }

    private function calculateTotals($invoicesByLocation)
    {
        $totalDue = $invoicesByLocation->sum('total_amount_due');
        $totalPaid = $invoicesByLocation->sum('total_amount_paid');
        return [$totalDue, $totalPaid];
    }

    private function sortInvoicesByLocation($invoicesByLocation, $sortBy)
    {
        $sortByColumn = $sortBy === 'amount_paid' ? 'total_amount_paid' : 'total_amount_due';

        return $invoicesByLocation->sortByDesc(function ($locationData) use ($sortByColumn) {
            return $locationData[$sortByColumn];
        });
    }

    private function updateInvoiceAmount($invoice)
    {
        $serverStatus = $invoice->subscription->serverStatus;
        $pricing = $invoice->subscription->pricing;
        if ($serverStatus->status === 'good' && $pricing->period == 'hourly') {
            $uptimeInSeconds = now()->diffInSeconds($serverStatus->updated_at);
            $serverStatus->increment('uptime', $uptimeInSeconds);

            $hours = $serverStatus->uptime / 3600;
            $invoice->amount_due = $hours * $pricing->price;
            $invoice->save();
        }
    }

    private function fetchInvoices($year, $month, $sortBy)
    {
        $user = auth()->user();

        $invoices = Invoice::whereHas('subscription', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereYear('due_date', $year)
            ->whereMonth('due_date', $month)
            ->get();

        foreach ($invoices as $invoice) {
            $this->updateInvoiceAmount($invoice);
        }

        $invoicesByLocation = $invoices->groupBy(function ($item) {
            return $item->subscription->service->location_id;
        })->map(function ($items) {
            return [
                'total_amount_due' => $items->sum('amount_due'),
                'total_amount_paid' => $items->sum('amount_paid'),
                'invoices' => $items
            ];
        });

        return $this->sortInvoicesByLocation($invoicesByLocation, $sortBy);
    }
}
