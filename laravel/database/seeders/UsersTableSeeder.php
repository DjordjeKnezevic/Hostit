<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class UsersTableSeeder extends Seeder
{
    public function run()
    {

        $adminRole = Role::where('name', 'Admin')->first()->id;
        $moderatorRole = Role::where('name', 'Moderator')->first()->id;
        $userRole = Role::where('name', 'User')->first()->id;
        $premiumRole = Role::where('name', 'Premium')->first()->id;

        $users = [
            [
                'role_id' => $adminRole,
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'role_id' => $moderatorRole,
                'name' => 'Moderator User',
                'email' => 'moderator@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'role_id' => $premiumRole,
                'name' => 'Premium User 1',
                'email' => 'premiumuser@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'role_id' => $userRole,
                'name' => 'Regular User 1',
                'email' => 'user1@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'role_id' => $userRole,
                'name' => 'Regular User 2',
                'email' => 'user2@example.com',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user + ['email_verified_at' => now(), 'created_at' => now(), 'updated_at' => now()]);
        }
    }
}
