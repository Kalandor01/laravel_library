<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    // Create a new message instance.
    public function __construct($mailData, $subject)
    {
            $this->mailData = $mailData;
            $this->subject($subject);
    }
    // Build the message.
    public function build()
    {
        return $this->view('email.test');
    }

}
