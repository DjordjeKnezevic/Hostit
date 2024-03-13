<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Location;

class RegionResourcesTableSeeder extends Seeder
{
    public function run()
    {
        $locations = Location::all();

        foreach ($locations as $location) {
            DB::table('region_resources')->insert([
                'location_id' => $location->id,
                'total_cpu_cores' => 1000,
                'remaining_cpu_cores' => 1000,
                'total_ram' => 2000,
                'remaining_ram' => 2000,
                'total_storage' => 500000,
                'remaining_storage' => 500000,
                'total_bandwidth' => 1000,
                'remaining_bandwidth' => 1000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
