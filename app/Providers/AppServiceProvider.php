<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        // RenderはHTTPS終端なので、本番ではhttpsでURL生成を強制する
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
