<?php

namespace App\Console\Commands;

class GitHubUserInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collection:githubinfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'github用户信息采集';

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
