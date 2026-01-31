<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProjectInitialize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Project Initialization (DB, API, Permission, Optimization)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Initializing Project...');

        // 1. Fresh Migration
        $this->info('🔄 Migrating database...');
        $this->call('migrate:fresh', [
            '--force' => true,
        ]);

        // 2. Install & Setup API (Laravel Sanctum)
        $this->info('🔐 Setting up API (Sanctum)...');

        $this->call('vendor:publish', [
            '--provider' => 'Laravel\\Sanctum\\SanctumServiceProvider',
            '--force' => true,
        ]);

        $this->call('migrate', [
            '--force' => true,
        ]);

        // 3. Generate Permission (Filament Shield)
        $this->info('🛡 Generating permissions...');
        $this->call('shield:generate', [
            '--all' => true,
            '--panel' => 'admin',
        ]);

        // 4. Database Seeder
        $this->info('🌱 Seeding database...');
        $this->call('db:seed', [
            '--force' => true,
        ]);

        // 5. Clear & Optimize Cache
        $this->info('🧹 Optimizing application...');
        $this->call('filament:optimize-clear');
        $this->call('optimize:clear');

        $this->info('✅ Project initialization completed successfully!');
    }
}
