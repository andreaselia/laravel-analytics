<?php

namespace Laravel\Analytics\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected string $signature = 'analytics:install';

    protected string $description = 'Install all of the Analytics resources';

    public function handle(): void
    {
        $this->comment('Publishing Analytics Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'analytics-assets']);

        $this->comment('Publishing Analytics Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'analytics-config']);

        $this->info('Analytics scaffolding installed successfully.');
    }
}
