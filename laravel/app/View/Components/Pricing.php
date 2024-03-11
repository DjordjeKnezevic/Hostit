<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Pricing as PricingModel;
use App\Models\ServerType as Server;

class Pricing extends Component
{
    public $pricingPlans;
    public $maxSpecs;

    public function __construct()
    {

        $now = now();

        $this->pricingPlans = [
            'hourly' => PricingModel::where('period', 'hourly')
                ->where('valid_from', '<=', $now)
                ->where(function ($query) use ($now) {
                    $query->where('valid_until', '>=', $now)
                        ->orWhereNull('valid_until');
                })
                ->min('price'),
            'monthly' => PricingModel::where('period', 'monthly')
                ->where('valid_from', '<=', $now)
                ->where(function ($query) use ($now) {
                    $query->where('valid_until', '>=', $now)
                        ->orWhereNull('valid_until');
                })
                ->min('price'),
            'yearly' => PricingModel::where('period', 'yearly')
                ->where('valid_from', '<=', $now)
                ->where(function ($query) use ($now) {
                    $query->where('valid_until', '>=', $now)
                        ->orWhereNull('valid_until');
                })
                ->min('price'),
        ];

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
