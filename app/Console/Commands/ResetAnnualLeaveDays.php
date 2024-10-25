<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ResetAnnualLeaveDays extends Command
{
    protected $signature = 'leave:reset-annual-days';
    protected $description = 'Reset annual leave days to 20 for all users';

    public function handle()
    {
        User::query()->update(['annual_leave_days' => 20]);
        $this->info('Annual leave days have been reset to 20 for all users.');
    }
    
}