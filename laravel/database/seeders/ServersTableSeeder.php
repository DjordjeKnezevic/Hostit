<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\ServerType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServersTableSeeder extends Seeder
{
    public function run()
    {
        $locations = Location::all();
        $serverTypes = ServerType::all()->pluck('id', 'name');

        $serverTypeNames = ['Basic', 'Starter', 'Intermediate', 'Advanced', 'Pro', 'Ultra', 'Max', 'Mega', 'Giga', 'Tera'];
        foreach ($locations as $index => $location) {
            $namesAvailable = array_slice($serverTypeNames, 0, count($serverTypeNames) - $index);

            foreach ($namesAvailable as $spec) {
                DB::table('servers')->insert([
                    'name' => "{$location->city} - {$spec}",
                    'server_type_id' => $serverTypes[$spec],
                    'location_id' => $location->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
