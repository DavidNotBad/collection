<?php

namespace App\Console\Commands;

class BaiduCityDetail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collection:baidu_city_detail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '采集百度城市的具体信息';

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
