<?php

namespace App\Notifications;

use App\Mail\SendVerifyEmailMail;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendVerifyWithQueueNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;
}