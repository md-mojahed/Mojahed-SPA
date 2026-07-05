<?php

namespace Mojahed\Spa\Commands;

use Illuminate\Console\Command;

class SpaPublishCommand extends Command
{
    protected $signature   = 'spa:publish {--config : Publish only the config file} {--views : Publish only the views}';
    protected $description = 'Publish mojahed/spa config and/or blade views for customization.';

    public function handle(): void
    {
        $onlyConfig = $this->option('config');
        $onlyViews  = $this->option('views');

        if (!$onlyConfig && !$onlyViews) {
            // Publish everything
            $this->callSilent('vendor:publish', ['--tag' => 'spa-config']);
            $this->callSilent('vendor:publish', ['--tag' => 'spa-views']);
            $this->info('');
            $this->info('  ✓ mojahed/spa config and views published.');
            $this->info('  → config/spa.php');
            $this->info('  → resources/views/vendor/spa/');
            $this->info('');
            return;
        }

        if ($onlyConfig) {
            $this->callSilent('vendor:publish', ['--tag' => 'spa-config']);
            $this->info('  ✓ Config published → config/spa.php');
        }

        if ($onlyViews) {
            $this->callSilent('vendor:publish', ['--tag' => 'spa-views']);
            $this->info('  ✓ Views published → resources/views/vendor/spa/');
        }

        $this->info('');
    }
}
