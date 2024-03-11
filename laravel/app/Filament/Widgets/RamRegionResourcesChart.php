<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use App\Models\RegionResource;
use App\Models\Location;

class RamRegionResourcesChart extends ChartWidget
{
    protected static ?string $heading = 'RAM per Region Overview';
    public ?string $resourceType = 'ram';

    protected function getData(): array
    {
        $locations = Location::all();
        $datasets = [
            [
                'label' => 'Total ' . ucfirst(str_replace('_', ' ', $this->resourceType)) . '(GB)',
                'data' => [],
                'backgroundColor' => 'rgb(255, 99, 132)',
            ],
            [
                'label' => 'Remaining ' . ucfirst(str_replace('_', ' ', $this->resourceType)) . '(GB)',
                'data' => [],
                'backgroundColor' => 'rgb(54, 162, 235)',
            ],
        ];
        $labels = [];

        foreach ($locations as $location) {
            $resource = RegionResource::where('location_id', $location->id)->firstOrFail();

            $datasets[0]['data'][] = $resource->{'total_' . $this->resourceType};
            $datasets[1]['data'][] = $resource->{'remaining_' . $this->resourceType};
            $labels[] = $location->name;
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
