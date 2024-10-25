<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\ResetAnnualLeaveDays::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('leave:reset-annual-days')->yearlyOn(1, 0);
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }

}
