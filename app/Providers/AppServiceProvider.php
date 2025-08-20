<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;

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
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        if (!file_exists(public_path('storage'))) {
            try {
                // Call artisan command to create symbolic link
                Artisan::call('storage:link');
            } catch (\Exception $e) {
                // Handle any exceptions if the command fails
                Log::error('Error occurred while linking storage: ' . $e->getMessage());
            }
        }
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
        $this->configureRateLimiting();
    }


    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    public function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

}
