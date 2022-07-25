<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ServiceMaker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a Service Class';

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
        $name = $this->argument('name');

        $content = "<?php

namespace App\Services;

class ".$name."Service {

    // Make Your Magic Here

}
        
        ";

        // print("SD");
        Storage::disk('app')->put('Services/'.$name.'Service.php', $content);
        $this->info("DONE..");

    }
}
