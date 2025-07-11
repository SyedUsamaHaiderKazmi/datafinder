<?php

/**
    * Main Service Provider for the DataFinder package.
    *
    * This service provider is responsible for registering any application
    * services, such as binding classes into the service container.
    *
    * @package SUHK\DataFinder
    *
*/

namespace SUHK\DataFinder\App\Providers;

use Illuminate\Support\ServiceProvider;
use SUHK\DataFinder\App\Helpers\ConfigGlobal;

class MainServiceProvider extends ServiceProvider
{

    protected $commands = [
        'SUHK\DataFinder\App\Console\SetupPackage',
        'SUHK\DataFinder\App\Console\Commands\AddNewModule',
        'SUHK\DataFinder\App\Console\Commands\RefreshPackage',
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->commands($this->commands);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Routes
        $this->loadRoutesFrom(dirname(__FILE__) . '/../../routes/routes.php');

        // Views
        $this->loadViewsFrom(dirname(__FILE__) . '/../../views', 'datafinder');
        $this->loadViewsFrom(dirname(__FILE__) . '/../../views/filters', 'datafinder');
        $this->loadViewsFrom(dirname(__FILE__) . '/../../views/datatable', 'datafinder');

        // Configs
        /*$this->publishes([
            dirname(__FILE__) . '/../../config/filter_configurations.php' => ConfigGlobal::getPath('sample_filter_configurations'),
        ], 'sample_configuration');*/
        // assets
        $this->publishes([
            dirname(__FILE__) . '/../../assets' => public_path('vendor/datafinder/assets/'),
        ], 'assets');

        // $this->commands($this->commands);

        // Commands Registration
        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }

    }
}
