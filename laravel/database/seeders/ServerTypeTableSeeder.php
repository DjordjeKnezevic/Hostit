<?php

namespace Database\Seeders;

use App\Models\ServerType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServerTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $types = [
            ['name' => 'Starter', 'cpu_cores' => 1, 'ram' => 2, 'storage' => 20, 'network_speed' => 1],
            ['name' => 'Basic', 'cpu_cores' => 2, 'ram' => 4, 'storage' => 40, 'network_speed' => 1],
            ['name' => 'Intermediate', 'cpu_cores' => 4, 'ram' => 8, 'storage' => 80, 'network_speed' => 2],
            ['name' => 'Advanced', 'cpu_cores' => 8, 'ram' => 16, 'storage' => 160, 'network_speed' => 2],
            ['name' => 'Pro', 'cpu_cores' => 16, 'ram' => 32, 'storage' => 320, 'network_speed' => 5],
            ['name' => 'Ultra', 'cpu_cores' => 32, 'ram' => 64, 'storage' => 640, 'network_speed' => 5],
            ['name' => 'Mega', 'cpu_cores' => 48, 'ram' => 128, 'storage' => 1280, 'network_speed' => 10],
            ['name' => 'Giga', 'cpu_cores' => 64, 'ram' => 256, 'storage' => 2560, 'network_speed' => 10],
            ['name' => 'Tera', 'cpu_cores' => 96, 'ram' => 384, 'storage' => 3840, 'network_speed' => 10],
            ['name' => 'Max', 'cpu_cores' => 128, 'ram' => 512, 'storage' => 5120, 'network_speed' => 10],
        ];


        foreach ($types as $type) {
            ServerType::create(
                [
                    'name' => $type['name'],
                    'cpu_cores' => $type['cpu_cores'],
                    'ram' => $type['ram'],
                    'storage' => $type['storage'],
                    'network_speed' => $type['network_speed'],
                ]
            );
        }
    }
}
