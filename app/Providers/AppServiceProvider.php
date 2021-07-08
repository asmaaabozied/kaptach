<?php

namespace App\Providers;

use App\Device;
use App\Observers\DeviceObserver;
use App\Observers\TransferObserver;
use App\Transfer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Transfer::observe(TransferObserver::class);
        Device::observe(DeviceObserver::class);
    }
}
