<?php

namespace App\Providers;

use App\Models\Currency;
use Torann\GeoIP\Facades\GeoIP;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Stevebauman\Location\Facades\Location;

class UserLocationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {

    }
   
}
