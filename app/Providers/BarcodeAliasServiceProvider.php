<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;

class BarcodeAliasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Helper functions previously defined here were causing conflicts
        // They are not being used in the application, so they have been removed
        // Classes DNS1D and DNS2D are used directly in views
    }
}
