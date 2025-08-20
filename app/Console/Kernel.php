<?php
// app/Console/Kernel.php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // 毎日午前3時にmessage:dailyコマンドを実行する設定です。
        $schedule->command('message:daily')->dailyAt('03:00');

        // 必要に応じて、ログの出力先を指定することも可能です。
        // $schedule->command('message:daily')->dailyAt('03:00')->appendOutputTo(storage_path('logs/daily_message.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
