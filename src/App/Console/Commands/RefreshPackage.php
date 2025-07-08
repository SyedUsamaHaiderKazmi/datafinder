<?php

/**
    * Refresh Package command class for the DataFinder package.
    *
    * This command file is responsible for refreshing the datafinder package for users
    * such as publishing configuration file, assets and more after a package update.
    *
    * @package SUHK\DataFinder
    *
*/

namespace SUHK\DataFinder\App\Console\Commands;

use Illuminate\Console\Command;

class RefreshPackage extends Command
{
    protected $signature = 'datafinder:assets-refresh';

    protected $description = 'This command is setup to republish the assets to ensure the package asset published files are uptodated with the latest release assets.';

    public function handle()
    {
        $this->info('Refreshing package assets...');

        $this->call('vendor:publish', [
            '--provider' => "SUHK\DataFinder\App\Providers\MainServiceProvider",
            '--tag' => "assets",
            '--force' => true,
        ]);

        $this->info('Package assets refreshed successfully. Please follow the documentation for usage.');
    }

}
