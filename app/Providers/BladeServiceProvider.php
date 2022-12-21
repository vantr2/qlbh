<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('admin', function ($expression) {
            return "<?php if(Auth::user()->role == \App\Models\User::ADMIN): ?>";
        });

        Blade::directive('endadmin', function ($expression) {
            return "<?php endif; ?>";
        });
    }
}
