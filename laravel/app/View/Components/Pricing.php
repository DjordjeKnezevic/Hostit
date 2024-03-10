<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Pricing as PricingModel;
use App\Models\Server;

class Pricing extends Component
{
    public $pricingPlans;
    public $maxSpecs;

    public function __construct()
    {
        // Fetch the minimal prices for each period
        $this->pricingPlans = [
            'hourly' => PricingModel::where('period', 'hourly')->min('price'),
            'monthly' => PricingModel::where('period', 'monthly')->min('price'),
            'yearly' => PricingModel::where('period', 'yearly')->min('price'),
        ];

        // Fetch the highest specs available
        $this->maxSpecs = [
            'cpu' => Server::max('cpu_cores'),
            'ram' => Server::max('ram'),
            'storage' => Server::max('storage'),
            'network_speed' => Server::max('network_speed'),
        ];
    }

    public function render()
    {
        return view('components.pricing');
    }
}
