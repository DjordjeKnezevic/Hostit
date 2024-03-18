<?php

namespace App\Providers;

use App\Models\NavigationLink;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
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
        if (Schema::hasTable('navigation_links')) {
            $navLinks = NavigationLink::all();
            View::share('navLinks', $navLinks);
        }
    }

}
