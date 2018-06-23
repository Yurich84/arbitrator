<?php

namespace App\Console\Commands;

use App\Console\Ar\MyM;
use Illuminate\Console\Command;

class My extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my {action=index} {arg?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'My custom commands';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $action = $this->argument('action');
        (new MyM())->$action($this->argument('arg'));
    }
}
