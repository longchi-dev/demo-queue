<?php

namespace App\Http\Controllers;

use App\Jobs\EmailJob;
use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index()
    {
        $data = [
            "title" => "Test send email Queue",
            "body" => "Test send email queue"
        ];
        dispatch(new EmailJob($data));
    }
}
