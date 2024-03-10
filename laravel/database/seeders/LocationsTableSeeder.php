<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationsTableSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            ['name' => 'US East', 'network_zone' => 'Zone A', 'city' => 'New York', 'image' => 'img/countries/us.jpg'],
            ['name' => 'US West', 'network_zone' => 'Zone B', 'city' => 'San Francisco', 'image' => 'img/countries/us.jpg'],
            ['name' => 'EU West', 'network_zone' => 'Zone C', 'city' => 'London', 'image' => 'img/countries/uk.jpg'],
            ['name' => 'EU Central', 'network_zone' => 'Zone D', 'city' => 'Frankfurt', 'image' => 'img/countries/germany.jpg'],
            ['name' => 'Asia East', 'network_zone' => 'Zone E', 'city' => 'Tokyo', 'image' => 'img/countries/japan.jpg'],
            ['name' => 'Asia South', 'network_zone' => 'Zone F', 'city' => 'Mumbai', 'image' => 'img/countries/india.jpg'],
        ];

        foreach ($locations as $location) {
            DB::table('locations')->insert(array_merge($location, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}
