<?php

namespace App\Providers;

use App\Http\Services\Bitmex;
use App\Http\Services\Fcoin;
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

         $this->app->singleton('Fcoin', function () {
              return new Fcoin();
         });

         $this->app->singleton('Bitmex', function () {
              $key = config('platform.bitmex.key');
              $secret = config('platform.bitmex.secret');
              return new Bitmex($key, $secret);
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
