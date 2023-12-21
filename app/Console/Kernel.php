<?php

namespace App\Console;

use App\Jobs\CleanUpOrders;
use App\Jobs\ClearExpiredTokens;
use App\Jobs\ClearInactiveMediaJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new CleanUpOrders())->everyMinute();
        $schedule->job(new \App\Jobs\CleanUpVerificationCodesJob())->everyMinute();
        $schedule->job(new ClearInactiveMediaJob())->daily();
        $schedule->job(new ClearExpiredTokens())->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
