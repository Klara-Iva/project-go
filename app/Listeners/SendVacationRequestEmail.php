<?php

namespace App\Listeners;

use App\Events\VacationRequestSubmitted;
use App\Jobs\SendEmails;
use App\Repositories\UserRepository;

class SendVacationRequestEmail
{
    // dont forget to "php artisan queue:work" in terminal when working with jobs (for database)

    public function __construct(
        protected UserRepository $userRepository
    ) {
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
            $teamLeaderEmails[] = $this->userRepository->getEmailsByRoleAndTeam(2, $team->id);
            $projectManagerEmails[] = $this->userRepository->getEmailsByRoleAndTeam(3, $team->id);
        }

        $teamLeaderEmails = array_unique(array_merge(...$teamLeaderEmails));
        $projectManagerEmails = array_unique(array_merge(...$projectManagerEmails));
        $emails = array_unique(array_merge($teamLeaderEmails, $projectManagerEmails));

        SendEmails::dispatch($user, $emails, $teamNames);
    }

}