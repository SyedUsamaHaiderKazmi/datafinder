<?php

/**
 * Main Service Provider for the DataFinder package.
 *
 * This service provider is responsible for:
 * - Registering the DataFinder singleton
 * - Loading routes, views, and assets
 * - Publishing package assets
 * - Registering console commands
 *
 * @package SUHK\DataFinder
 * @since 1.0.0
 */

namespace SUHK\DataFinder\App\Providers;

use Illuminate\Support\ServiceProvider;
use SUHK\DataFinder\App\Helpers\ConfigGlobal;
use SUHK\DataFinder\App\Services\DataFinderManager;

class MainServiceProvider extends ServiceProvider
{
    /**
     * Console commands to register
     * 
     * @var array
     */
    protected $commands = [
        'SUHK\DataFinder\App\Console\SetupPackage',
        'SUHK\DataFinder\App\Console\Commands\AddNewModule',
        'SUHK\DataFinder\App\Console\Commands\RefreshPackage',
    ];

    /**
     * Register any application services.
     *
     * This method is called before boot() and is used to bind
     * services into the container.
     *
     * @return void
     */
    public function register()
    {
        // Register DataFinder Manager as singleton
        // This backs the DataFinder facade
        $this->app->singleton('datafinder', function ($app) {
            return new DataFinderManager();
        });

        // Register alias for the facade
        $this->app->alias('datafinder', DataFinderManager::class);
    }

    /**
     * Bootstrap any application services.
     *
     * This method is called after all service providers have been registered.
     * Used to load routes, views, publish assets, etc.
     *
     * @return void
     */
    public function boot()
    {
        // =====================================================================
        // ROUTES
        // =====================================================================
        $this->loadRoutesFrom($this->getPackagePath('routes/routes.php'));

        // =====================================================================
        // VIEWS
        // =====================================================================
        $this->loadViewsFrom($this->getPackagePath('views'), 'datafinder');

        // =====================================================================
        // PUBLISHABLE ASSETS
        // =====================================================================
        
        // Publish JavaScript and CSS assets
        $this->publishes([
            $this->getPackagePath('assets') => public_path('vendor/datafinder/assets/'),
        ], 'datafinder-assets');

        // Alias for backward compatibility
        $this->publishes([
            $this->getPackagePath('assets') => public_path('vendor/datafinder/assets/'),
        ], 'assets');

        // Publish config file (optional)
        $this->publishes([
            $this->getPackagePath('config/filter_configurations.php') => ConfigGlobal::getPath('sample_filter_configurations'),
        ], 'datafinder-config');

        // =====================================================================
        // CONSOLE COMMANDS
        // =====================================================================
        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
    }

    /**
     * Get the full path to a package file or directory
     *
     * @param string $path Relative path within the package
     * @return string Full path
     */
    protected function getPackagePath(string $path = ''): string
    {
        return dirname(__FILE__) . '/../../' . $path;
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['datafinder', DataFinderManager::class];
    }
}
