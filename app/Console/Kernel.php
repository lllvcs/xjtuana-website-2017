<?php

namespace App\Console;

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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 每天检查是否有需要超时自动评价的报修单
        $schedule->call(function() {
            $orderRepo = new \App\Repositories\OrderRepository();
            $orderRepo->autoRate();
        })->daily();
        
        // 每十分钟检查accessToken是否需要更新
        $schedule->call(function() {
            $wechat = resolve('wechat');
            $wechat->accessToken->update();
        })->everyTenMinutes();
        
        // 每天中午十二点半和下午六点半统一发送短信提醒接单
        $schedule->call(function() {
            $orderRepo = new \App\Repositories\OrderRepository();
            $orderRepo->autoUrge();
        })->dailyAt('12:30');
        
        $schedule->call(function() {
            $orderRepo = new \App\Repositories\OrderRepository();
            $orderRepo->autoUrge();
        })->dailyAt('18:30');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
        $this->load(__DIR__.'/Commands');
    }
}
