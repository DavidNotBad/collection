<?php

namespace App\Console\Commands;

class Ajax extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collection:ajax';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ajax';

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
     * @return mixed
     */
    public function handle()
    {
        $this->collection();
    }
}
