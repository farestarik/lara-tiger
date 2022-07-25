<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MakingModules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make New Module (Controller & Model & Factory & Migration & Seeder)';

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
      //  $wos = sub_str($module,0,-1); // wos => With Out S

        $module = $this->argument('module');

        Artisan::call("make:controller Dashboard/\\$module"."Controller -r");
        $this->info("Controller Created!");

        Artisan::call("make:model $module -m");
        $this->info("Model & Migration Created!");

        Artisan::call("make:seeder $module"."Seeder");
        $this->info("Seeder Created!");

        Artisan::call("make:factory {$module}");
        $this->info("Factory Created!");
    }
}
