<?php

namespace App\Console\Commands;

class Page extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collection:page';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '采集分页信息';

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
        try{
            return $this->collection();
        }catch (\Exception $e){
            $this->info('采集结束');
            $this->info($e->getLine());
            $this->info($e->getMessage());
        }
    }
}
