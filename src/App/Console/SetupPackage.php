<?php

/**
    * Setup Package command class for the DataFinder package.
    *
    * This command file is responsible for setting up the datafinder package for users
    * such as publishing configuration file, assets and more
    *
    * @package SUHK\DataFinder
    *
*/

namespace SUHK\DataFinder\App\Console;

use Illuminate\Console\Command;

class SetupPackage extends Command
{
    protected $signature = 'suhk:package-setup';

    protected $description = 'Auto setup process for package.';

    public function handle()
    {
        $this->info('Setting up the package...');

        $this->info('Publishing sample configuration file...');

        // $this->createDirectoryIfNotExist(config('datafinder'));
        //this works for latest laravel
        /*
        $this->call('vendor:publish', [
            '--provider' => "SUHK\DataFinder\App\Providers\MainServiceProvider",
            '--tag' => "sample_configuration",
            '--tag' => "assets",
        ]);*/

        // for old laravel, we will have to call each saperately
        $this->call('vendor:publish', [
            '--provider' => "SUHK\DataFinder\App\Providers\MainServiceProvider",
            '--tag' => "sample_configuration",
        ]);

        $this->call('vendor:publish', [
            '--provider' => "SUHK\DataFinder\App\Providers\MainServiceProvider",
            '--tag' => "assets",
        ]);

        $this->info('Package setuped successfully. Please follow the documentation for usage.');
    }

    public function createDirectoryIfNotExist($path, $replace = false)
    {
        if (file_exists($path) && $replace) {
            rmdir($path);
        }

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }
}
