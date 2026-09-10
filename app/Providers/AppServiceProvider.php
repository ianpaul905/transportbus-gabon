<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Carbon\Carbon::setLocale('fr');

        // Derrière le proxy HTTPS de Render, on force le schéma https (assets CSS/JS)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
