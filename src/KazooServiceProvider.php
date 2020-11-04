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
        $configPath = __DIR__ . '/../config/kazooapi.php';
        $this->mergeConfigFrom($configPath, 'kazooapi');

        //$this->app->make('Pbxhq\Kazoo\KazooController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $configPath = __DIR__ . '/../config/kazooapi.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'config');
    }

    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('kazooapi.php');
    }

    /**
     * Publish the config file
     *
     * @param  string $configPath
     */
    protected function publishConfig($configPath)
    {
        $this->publishes([$configPath => config_path('kazooapi.php')], 'config');
    }

}
