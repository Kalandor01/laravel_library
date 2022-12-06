<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoMail;

class MailController extends Controller
{

    public function index($email, $num)
    {
        for ($x=0; $x < $num; $x++) { 
            $mailData = [
                'title' => "Mail from $email.",
                'body' => "This is a testing email using YOU."
            ];
            $xx = $x + 1;
            $subject = "email $xx";
            Mail::to($email)
            ->send(new DemoMail($mailData, $subject));
        }

        dd("$num emails sent successfully.");
    }

}
