<?php

namespace App\Console\Commands;

class CriminalCase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collection:case';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '中国裁决文书网-刑事案件';

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
        return $this->collection();
    }
}
