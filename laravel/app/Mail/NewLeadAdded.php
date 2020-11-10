<?php

namespace App\Mail;

use App\Models\Lead;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewLeadAdded extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $listing;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param Lead $lead
     */
    public function __construct(Lead $lead, User $user, Listing $listing)
    {
        $this->lead = $lead;
        $this->user = $user;
        $this->listing = $listing;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('You got a new request from ' . $this->listing->street)
            ->view('emails.leadCreated');
    }
}
