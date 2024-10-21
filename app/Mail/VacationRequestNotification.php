<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VacationRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $recipientName;
    public $teamNames;

    public function __construct($user, $recipientName, $teamNames)
    {
        $this->user = $user;
        $this->recipientName = $recipientName;
        $this->teamNames = $teamNames;
    }

    public function build()
    {
        return $this->subject('New Vacation Request')
            ->view('emails.vacation_request_notification')
            ->with([
                'user' => $this->user,
                'recipientName' => $this->recipientName,
                'teamNames' => $this->teamNames,
            ]);
    }

}