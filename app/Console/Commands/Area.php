<?php

namespace App\Console\Commands;

/**
 * Class Area
 * @package App\Console\Commands
 */
/**
 * Class Area
 * @package App\Console\Commands
 */
class Area extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collection:area';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '采集百度词条的地区信息';

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
        return $this->collection(\App\Models\Area::class);
    }
}
