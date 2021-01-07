<?php

namespace AndreasElia\Analytics\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /** @var string */
    protected $signature = 'analytics:install';

    /** @var string */
    protected $description = 'Install all of the Analytics resources';

    public function handle(): void
    {
        $this->comment('Publishing Analytics Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'analytics-assets']);

        $this->comment('Publishing Analytics Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'analytics-config']);

        $this->info('Analytics scaffolding installed successfully.');
    }
}
