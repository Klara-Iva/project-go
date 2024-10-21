<?php

namespace App\Listeners;

use App\Events\VacationRequestSubmitted;
use App\Mail\VacationRequestNotification;
use Illuminate\Support\Facades\Mail;
use APP\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVacationRequestEmail
{
    //implements ShouldQueue
    // dont forget to "php artisan queue:work" in terminal when working with jobs

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

        /*  $job = (Mail::to(array_unique(array_merge($teamLeaderEmails, $projectManagerEmails)))
              ->send(new VacationRequestNotification($user)));
          dispatch($job);*/

        //TODO prouci jobs, SendVacationRequestEmailJob.php-> nova klasa i u njoj Mail::to  etc i onda ovdje koristi klasa::dispatch(user, email)

        foreach ($emails as $email) {
            $recipientName = User::where('email', $email)->first()->name ?? 'User';
            Mail::to($email)->send(new VacationRequestNotification($user, $recipientName, $teamNames));
        }
    }

}