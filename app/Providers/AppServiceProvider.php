<?php

namespace App\Providers;

use App\Services\InfusionsoftService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(InfusionsoftService::class/*, function () {
            $storage = $this->app->make('filesystem')->disk();

            return new InfusionsoftService($storage);
        }*/);
    }
}
