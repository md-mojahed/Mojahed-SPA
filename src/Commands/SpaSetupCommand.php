<?php

namespace Mojahed\Spa\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SpaSetupCommand extends Command
{
    protected $signature   = 'spa:setup';
    protected $description = 'Copy mojahed/spa assets (JS, CSS) to your public directory.';

    public function handle(): void
    {
        $source      = __DIR__ . '/../../resources/dist';
        $destination = public_path(config('spa.asset_path', 'assets/spa'));

        if (!File::exists($source)) {
            $this->error('[spa] Asset dist folder not found. Please check the package installation.');
            return;
        }

        if (File::exists($destination)) {
            if (!$this->confirm("Directory [{$destination}] already exists. Overwrite?", true)) {
                $this->info('[spa] Setup cancelled.');
                return;
            }
            File::deleteDirectory($destination);
        }

        File::copyDirectory($source, $destination);

        $this->info('');
        $this->info('  ✓ mojahed/spa assets published successfully.');
        $this->info("  → {$destination}");
        $this->info('');
        $this->comment('  Add @spacss inside <head> and @spajs before </body> in your layout.');
        $this->info('');
    }
}
