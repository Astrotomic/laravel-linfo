<?php
namespace Gummibeer\Linfo;

class LinfoServiceProvider
{
    public function register()
    {
        $this->publishes([
            __DIR__ . '/config/linfo.php' => config_path('linfo.php'),
        ], 'config');
    }

    public function boot()
    {
        //
    }
}