<?php

namespace App\Jobs;

use App\Mail\VacationRequestNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $emails;
    protected $teamNames;

    /**
     * Create a new job instance.
     */
    public function __construct($user, array $emails, array $teamNames)
    {
        $this->user = $user;
        $this->emails = $emails;
        $this->teamNames = $teamNames;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->emails as $email) {
            $recipientName = User::where('email', $email)->first()->name ?? 'User';
            Mail::to($email)->send(new VacationRequestNotification($this->user, $recipientName, $this->teamNames));
        }
    }
}
