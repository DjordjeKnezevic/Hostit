<?php

namespace Database\Seeders;

use App\Models\NavigationLink;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NavigationLinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $navLinks = [
            ['name' => 'Home', 'route' => 'index', 'icon' => 'img/nav-bullet.png', 'is_footer' => false],
            ['name' => 'About', 'route' => 'about', 'icon' => 'img/nav-bullet.png', 'is_footer' => false],
            ['name' => 'Servers', 'route' => 'server', 'icon' => 'img/nav-bullet.png', 'is_footer' => false],
            ['name' => 'Pricing', 'route' => 'price', 'icon' => 'img/nav-bullet.png', 'is_footer' => false],
            ['name' => 'Contact Us', 'route' => 'contact', 'icon' => 'img/nav-bullet.png', 'is_footer' => false],
            ['name' => 'Portfolio', 'route' => 'https://djordjeknezevic.github.io/', 'icon' => 'img/nav-bullet.png', 'is_footer' => true],
            ['name' => 'Dokumentacija', 'route' => '/Dokumentacija.pdf', 'icon' => 'img/nav-bullet.png', 'is_footer' => true],
        ];

        foreach ($navLinks as $link) {
            NavigationLink::create($link);
        }
    }
}
