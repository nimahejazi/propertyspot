<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Stripe;

class CreateStripeUser
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
     * @param  Registered  $user
     * @return void
     */
    public function handle(Registered $event)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $strip_customer = Stripe\Customer::create([
            'email' => $event->user->email
        ]);
        echo "CreateStripeUser event: " . $event->user->email . "\n";
        $event->user->stripe_customer_id = $strip_customer->id;
        $event->user->save();
    }
}
