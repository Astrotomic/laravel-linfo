<?php

namespace Linfo\Laravel;

use Illuminate\Support\ServiceProvider;

class LinfoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->publishes([
            __DIR__.'/config/linfo.php' => config_path('linfo.php'),
        ], 'config');
    }

    public function boot()
    {
        //
    }
}
