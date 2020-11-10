<?php

namespace App\Listeners;

use App\Events\PasswordResetRequested;
use App\Mail\ResetPassword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PasswordResetRequested  $event
     * @return void
     */
    public function handle(PasswordResetRequested $event)
    {
        Mail::to($event->user->email)->send(new ResetPassword($event->user));
    }
}
