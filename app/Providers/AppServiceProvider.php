<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

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
        Paginator::defaultView('vendor.pagination.tailwind');
        Paginator::defaultSimpleView('vendor.pagination.simple-tailwind');
        
        // Add setTimeFromTimeString method as a Carbon macro
        Carbon::macro('setTimeFromTimeString', function ($timeString) {
            $time = explode(':', $timeString);
            $hours = (int) $time[0];
            $minutes = isset($time[1]) ? (int) $time[1] : 0;
            $seconds = isset($time[2]) ? (int) $time[2] : 0;
            
            return $this->setTime($hours, $minutes, $seconds);
        });
    }
}
