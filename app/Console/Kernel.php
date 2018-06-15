<?php

namespace App\Console;

use App\Console\Commands\Ajax;
use App\Console\Commands\Area;
use App\Console\Commands\BaiduCity;
use App\Console\Commands\BaiduCityDetail;
use App\Console\Commands\CriminalCase;
use App\Console\Commands\GithubRepositories;
use App\Console\Commands\GitHubUserInfo;
use App\Console\Commands\Page;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
//        Area::class,
//        GitHubUserInfo::class,
//        GithubRepositories::class,
//        Page::class,
//        Ajax::class,
//        BaiduCity::class,
//        BaiduCityDetail::class,
        CriminalCase::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
//        $this->load(__DIR__.'/Commands');

//        require base_path('routes/console.php');
    }
}
