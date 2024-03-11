<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Pricing;
use App\Models\RegionResource;
use App\Models\Server;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            LocationsTableSeeder::class,
            TestimonialsTableSeeder::class,
            FaqsTableSeeder::class,
            UsersTableSeeder::class,
            ServerTypeTableSeeder::class,
            ServersTableSeeder::class,
            PricingTableSeeder::class,
            RegionResourcesTableSeeder::class,
        ]);
    }
}
