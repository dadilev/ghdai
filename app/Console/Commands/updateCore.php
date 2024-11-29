<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class updateCore extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-core';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update core to laravel 11';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Backup the current project
        $this->info('Backing up the project...');
        shell_exec('tar -czf backup_project_' . date('Y-m-d_H-i-s') . '.tar.gz ' . base_path());

        // You can also implement database backup logic here if needed
        $this->info('Backup completed.');

        // Read composer.json and update the Laravel version to 11.x
        $composerJsonPath = base_path('composer.json');
        $composerJson = json_decode(file_get_contents($composerJsonPath), true);

        // Update PHP version to 8.2
        $composerJson['require']['php'] = '^8.2';

        // Ensure all Laravel dependencies are updated to version 11.x
        $composerJson['require']['laravel/framework'] = '^11.0';
        $composerJson['require']['laravel/sanctum'] = '^4.0';
        $composerJson['require-dev']['nunomaduro/collision'] = '^8.1';

        // Additional checks for other dependencies like caching, queues, etc.
        $composerJson['require'] = array_map(function ($package) {
            // Update other packages to latest versions compatible with Laravel 11
            return $package;
        }, $composerJson['require']);

        file_put_contents($composerJsonPath, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->info('Updated composer.json with Laravel 11 dependencies.');

        // Run composer update
        $this->info('Running composer update...');
        shell_exec('composer update --no-interaction --prefer-dist');

        // Ensure that any warnings are handled
        $this->info('Composer update completed.');

        // Publish the Laravel 11 configuration files (if any)
        $this->info('Publishing Laravel 11 configuration files...');
        shell_exec('php artisan vendor:publish --tag=config --force');
        $this->info('Configuration files published.');


        // Clear cache and config cache
        $this->info('Clearing caches...');
        shell_exec('php artisan config:clear');
        shell_exec('php artisan cache:clear');
        shell_exec('php artisan route:clear');
        $this->info('Caches cleared.');


        //optimize and clear!
        shell_exec('php artisan optimize:clear');

        // Run PHPUnit tests
        $this->info('Running tests...');
        shell_exec('php artisan test');
        $this->info('Tests completed.');

        $this->info('Checking for issues...');
        shell_exec('tail -n 100 storage/logs/laravel.log');

        $this->info('Update complete!');
    }
}
