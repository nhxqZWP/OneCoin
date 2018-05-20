<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Services\Binance;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('Binance', function () {
            $key = config('platform.binance.key');
            $secret = config('platform.binance.secret');
            return new Binance($key, $secret);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
