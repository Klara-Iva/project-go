<?php

namespace App\Listeners;

use App\Events\VacationRequestSubmitted;
use App\Models\User;
use App\Jobs\SendEmails;

class SendVacationRequestEmail 
{
    // dont forget to "php artisan queue:work" in terminal when working with jobs (for database)

    public function __construct()
    {
        //
    }

    public function handle(VacationRequestSubmitted $event): void
    {
        $user = $event->user;
        $teams = $user->teams;
        $teamNames = $user->teams->pluck('name')->toArray();
        $teamLeaderEmails = [];
        $projectManagerEmails = [];

        foreach ($teams as $team) {
            $teamLeaderEmails[] = User::where('role_id', 2)
                ->whereHas('teams', function ($query) use ($team) {
                    $query->where('teams.id', $team->id);
                })
                ->pluck('email')
                ->toArray();

            $projectManagerEmails[] = User::where('role_id', 3)
                ->whereHas('teams', function ($query) use ($team) {
                    $query->where('teams.id', $team->id);
                })
                ->pluck('email')
                ->toArray();
        }

        $teamLeaderEmails = array_unique(array_merge(...$teamLeaderEmails));
        $projectManagerEmails = array_unique(array_merge(...$projectManagerEmails));
        $emails = array_unique(array_merge($teamLeaderEmails, $projectManagerEmails));

        SendEmails::dispatch($user, $emails, $teamNames);
    }

}