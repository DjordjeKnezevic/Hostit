<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Server;
use App\Models\Location;

class PricingTableSeeder extends Seeder
{
    public function run()
    {
        $servers = Server::with('location')->get();
        $locationAdjustments = [
            'New York' => 1,
            'San Francisco' => 1.05,
            'London' => 1.1,
            'Frankfurt' => 1.05,
            'Tokyo' => 0.95,
            'Mumbai' => 0.9,
        ];

        foreach ($servers as $server) {
            $baseHourlyRate = 0.01;
            $hourlyRate = $baseHourlyRate * $server->cpu_cores * ($server->ram / 2) * $locationAdjustments[$server->location->city];
            $monthlyRate = round($hourlyRate * 730 * 0.8, 2);
            $yearlyRate = round($monthlyRate * 12 * 0.8, 2);

            $pricingEntries = [
                ['period' => 'hourly', 'price' => $hourlyRate],
                ['period' => 'monthly', 'price' => $monthlyRate],
                ['period' => 'yearly', 'price' => $yearlyRate],
            ];

            foreach ($pricingEntries as $entry) {
                DB::table('pricing')->insert([
                    'service_id' => $server->id,
                    'service_type' => 'App\Models\Server',
                    'name' => "{$server->name} - {$entry['period']}",
                    'price' => $entry['price'],
                    'period' => $entry['period'],
                    'valid_from' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
