<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\CurrentPiValue;
use App\Classes\Contracts\CurrentValueInterface;

class CurrentValueServiceProvider extends ServiceProvider
{
    //$currentpivalue = new CurrentPiValue;
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->singleton(CurrentPiValue::class);
        // $this->app->singleton('App\Classes\Contracts\CurrentValueInterface', function ($app) {
        //     return new CurrentPiValue();
        //   });
        $this->app->singleton(CurrentValueInterface::class, function ($app) {
            return new CurrentPiValue();
        });

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        if(config('currentvalue.CurrentPiValue') == null)
        {
            //lad(config('currentvalue.CurrentPiValue'));
            //config()->set('currentvalue.CurrentPiValue', 1);
        }
    }
}
