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
            ['name' => 'Home', 'route' => 'index', 'icon' => 'img/nav-bullet.png', 'is_navbar' => true],
            ['name' => 'About', 'route' => 'about', 'icon' => 'img/nav-bullet.png', 'is_navbar' => true],
            ['name' => 'Servers', 'route' => 'server', 'icon' => 'img/nav-bullet.png', 'is_navbar' => true],
            ['name' => 'Pricing', 'route' => 'price', 'icon' => 'img/nav-bullet.png', 'is_navbar' => true],
            ['name' => 'Contact Us', 'route' => 'contact', 'icon' => 'img/nav-bullet.png', 'is_navbar' => true],
            ['name' => 'Portfolio', 'route' => 'https://djordjeknezevic.github.io/', 'icon' => 'img/nav-bullet.png', 'is_navbar' => false],
            ['name' => 'Dokumentacija', 'route' => '/Dokumentacija.pdf', 'icon' => 'img/nav-bullet.png', 'is_navbar' => false],
        ];

        foreach ($navLinks as $link) {
            NavigationLink::create($link);
        }
    }
}
