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
        // Register DNS1D alias
        if (!function_exists('DNS1D')) {
            function DNS1D() {
                return new DNS1D();
            }
        }

        // Register DNS2D alias
        if (!function_exists('DNS2D')) {
            function DNS2D() {
                return new DNS2D();
            }
        }
    }
}
