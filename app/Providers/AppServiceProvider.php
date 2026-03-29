<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Ensure maatwebsite/excel is registered when package discovery cache is missing on the server
        if (! $this->app->bound('excel') && class_exists(\Maatwebsite\Excel\ExcelServiceProvider::class)) {
            $this->app->register(\Maatwebsite\Excel\ExcelServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
