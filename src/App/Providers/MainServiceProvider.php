<?php

namespace SUHK\DataFinder\App\Providers;

use Illuminate\Support\ServiceProvider;

class MainServiceProvider extends ServiceProvider
{

    protected $commands = [
        'SUHK\DataFinder\Console\SetupPackage',
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

        // Configs
        $this->publishes([
            dirname(__FILE__) . '/../../config/filter_configurations.php' => app_path('Helpers/DataFinder/sample_filter_configurations.php'),
        ], 'sample_configuration');
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
