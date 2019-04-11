<?php

namespace Akaunting\Module\Providers;

use Illuminate\Support\ServiceProvider;

class Bootstrap extends ServiceProvider
{
    /**
     * Booting the package.
     */
    public function boot()
    {
        $this->app['module']->boot();
    }

    /**
     * Register the provider.
     */
    public function register()
    {
        $this->app['module']->register();
    }
}
