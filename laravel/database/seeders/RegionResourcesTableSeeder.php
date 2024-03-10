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
                'remaining_cpu_cores' => 1000, // Assuming full capacity to start
                'total_ram' => 2000, // In GB
                'remaining_ram' => 2000, // In GB
                'total_storage' => 5000, // In GB
                'remaining_storage' => 5000, // In GB
                'total_bandwidth' => 100, // In TB
                'remaining_bandwidth' => 100, // In TB
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
