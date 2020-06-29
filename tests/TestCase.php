<?php

namespace Linfo\Laravel\Tests;

use Linfo\Laravel\LinfoServiceProvider;
use Linfo\Laravel\Linfo;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LinfoServiceProvider::class,
        ];
    }

    /**
     * Get application timezone.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return string
     */
    protected function getApplicationTimezone($app)
    {
        return 'UTC';
    }

    protected function getModelInstance()
    {
        return new Linfo();
    }
}
