<?php

namespace App\Console\Commands;

use App\Models\FeatureToggle;
use Illuminate\Console\Command;

class ManageFeature extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feature:toggle {key} {--enable} {--disable} {--status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage feature toggles from command line';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $key = $this->argument('key');
        
        // Show all features if key is 'list'
        if ($key === 'list') {
            $this->listFeatures();
            return 0;
        }

        $feature = FeatureToggle::where('key', $key)->first();

        if (!$feature) {
            $this->error("Feature '{$key}' not found.");
            $this->info('');
            $this->info('Available features:');
            $this->listFeatures();
            return 1;
        }

        // Show status
        if ($this->option('status')) {
            $this->showFeatureStatus($feature);
            return 0;
        }

        // Enable feature
        if ($this->option('enable')) {
            $feature->update(['is_enabled' => true]);
            FeatureToggle::clearCache();
            $this->info("✓ Feature '{$feature->name}' has been ENABLED");
            return 0;
        }

        // Disable feature
        if ($this->option('disable')) {
            if ($this->confirm("Disable '{$feature->name}'? This may hide functionality from users.")) {
                $feature->update(['is_enabled' => false]);
                FeatureToggle::clearCache();
                $this->warn("✓ Feature '{$feature->name}' has been DISABLED");
                return 0;
            }
            $this->info('Operation cancelled.');
            return 0;
        }

        // No option specified, show help
        $this->showFeatureStatus($feature);
        $this->info('');
        $this->info('Options:');
        $this->info('  --enable    Enable this feature');
        $this->info('  --disable   Disable this feature');
        $this->info('  --status    Show feature status');

        return 0;
    }

    private function listFeatures()
    {
        $features = FeatureToggle::orderBy('category')->orderBy('sort_order')->get();

        $headers = ['Key', 'Name', 'Category', 'Status'];
        $rows = [];

        foreach ($features as $feature) {
            $rows[] = [
                $feature->key,
                $feature->name,
                $feature->category,
                $feature->is_enabled ? '<fg=green>ENABLED</>' : '<fg=red>DISABLED</>',
            ];
        }

        $this->table($headers, $rows);
    }

    private function showFeatureStatus($feature)
    {
        $this->info('Feature Details:');
        $this->table(
            ['Property', 'Value'],
            [
                ['Key', $feature->key],
                ['Name', $feature->name],
                ['Description', $feature->description ?: 'N/A'],
                ['Category', $feature->category],
                ['Status', $feature->is_enabled ? '<fg=green>ENABLED</>' : '<fg=red>DISABLED</>'],
                ['Sort Order', $feature->sort_order],
            ]
        );
    }
}
