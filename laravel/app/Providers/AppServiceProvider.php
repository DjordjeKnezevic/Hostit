<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $navLinks = [
            ['name' => 'Home', 'route' => 'index', 'icon' => 'img/nav-bullet.png'],
            ['name' => 'About', 'route' => 'about', 'icon' => 'img/nav-bullet.png'],
            ['name' => 'Servers', 'route' => 'server', 'icon' => 'img/nav-bullet.png'],
            ['name' => 'Pricing', 'route' => 'price', 'icon' => 'img/nav-bullet.png'],
            ['name' => 'Contact Us', 'route' => 'contact', 'icon' => 'img/nav-bullet.png'],
        ];

        View::share('navLinks', $navLinks);
    }
}
