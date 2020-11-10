<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\CreateStripeUser;
use App\Listeners\SendWelcomeEmail;
use App\Events\LeadCreated;
use App\Events\PasswordResetRequested;
use App\Events\PasswordReset;
use App\Listeners\NotifyUserAboutLead;
use App\Listeners\SendResetPasswordEmail;
use App\Listeners\SendPasswordResetEmail;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendWelcomeEmail::class,
            CreateStripeUser::class
        ],
        LeadCreated::class => [
            NotifyUserAboutLead::class
        ],
        PasswordResetRequested::class => [
            SendResetPasswordEmail::class
        ],
        PasswordReset::class => [
            SendPasswordResetEmail::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
