<?php

namespace Pbxhq\Kazoo;

use Illuminate\Support\ServiceProvider;

class KazooServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->make('Pbxhq\Kazoo\KazooController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
