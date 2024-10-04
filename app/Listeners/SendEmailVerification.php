<?php

namespace App\Listeners;
use App\Events\NewUserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
class SendEmailVerification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewUserCreated $event): void
    {
        sleep(5);
        Mail::to($event->user->email)->send(new SendMail($event->user));
    }
}
