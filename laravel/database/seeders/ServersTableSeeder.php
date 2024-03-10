<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Location;

class ServersTableSeeder extends Seeder
{
    public function run()
    {
        $locations = Location::all();
        $baseSpecs = [
            ['name' => 'Basic', 'cpu_cores' => 1, 'ram' => 2, 'storage' => 20, 'network_speed' => '1 Gbps'],
            ['name' => 'Starter', 'cpu_cores' => 2, 'ram' => 4, 'storage' => 40, 'network_speed' => '1 Gbps'],
            ['name' => 'Intermediate', 'cpu_cores' => 4, 'ram' => 8, 'storage' => 80, 'network_speed' => '2 Gbps'],
            ['name' => 'Advanced', 'cpu_cores' => 8, 'ram' => 16, 'storage' => 160, 'network_speed' => '5 Gbps'],
            ['name' => 'Pro', 'cpu_cores' => 16, 'ram' => 32, 'storage' => 320, 'network_speed' => '5 Gbps'],
            ['name' => 'Ultra', 'cpu_cores' => 32, 'ram' => 64, 'storage' => 640, 'network_speed' => '5 Gbps'],
            ['name' => 'Max', 'cpu_cores' => 48, 'ram' => 128, 'storage' => 1280, 'network_speed' => '10 Gbps'],
            ['name' => 'Mega', 'cpu_cores' => 64, 'ram' => 256, 'storage' => 2560, 'network_speed' => '10 Gbps'],
            ['name' => 'Giga', 'cpu_cores' => 96, 'ram' => 384, 'storage' => 3840, 'network_speed' => '10 Gbps'],
            ['name' => 'Tera', 'cpu_cores' => 128, 'ram' => 512, 'storage' => 5120, 'network_speed' => '10 Gbps'],
        ];

        foreach ($locations as $index => $location) {
            $specsAvailable = array_slice($baseSpecs, 0, count($baseSpecs) - $index); 

            foreach ($specsAvailable as $spec) {
                DB::table('servers')->insert([
                    'name' => $spec['name'] . ' Server - ' . $location->name,
                    'location_id' => $location->id,
                    'cpu_cores' => $spec['cpu_cores'],
                    'ram' => $spec['ram'],
                    'storage' => $spec['storage'],
                    'network_speed' => $spec['network_speed'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
