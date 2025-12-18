<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ShowRecentLogs extends Command
{
    protected $signature = 'logs:recent {lines=200}';
    protected $description = 'Show the last N lines of storage/logs/laravel.log';

    public function handle()
    {
        $lines = (int) $this->argument('lines');
        $path = storage_path('logs/laravel.log');
        if (! file_exists($path)) {
            $this->error('No log file found at ' . $path);
            return 1;
        }

        $content = implode('', array_slice(file($path), -$lines));
        $this->line($content);
        return 0;
    }
}
