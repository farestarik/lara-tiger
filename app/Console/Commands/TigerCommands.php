<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TigerCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate & Seed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Job Started");
        Artisan::call("config:cache");
        $this->info("Config Cache Cleared!");
        Artisan::call("migrate:fresh");
        $this->info("Migrated!");
        Artisan::call("db:seed");
        $this->info("Databese Seeded!");
        $this->info("Job Done!");
    }
}
