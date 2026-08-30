<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Stripe;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $event = $this->constructEvent(
            $request->getContent(),
            $request->header('Stripe-Signature'),
            config('services.stripe.webhook_secret')
        );

        switch ($event->type) {
            case 'payment_intent.succeeded':
            case 'charge.succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;
        }

        return response('ok', 200);
    }

    private function constructEvent($payload, $sigHeader, $secret)
    {
        try {
            return Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\UnexpectedValueException $e) {
            abort(400, 'Invalid payload');
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            abort(400, 'Invalid signature');
        }
    }

    private function handlePaymentSucceeded($paymentIntent)
    {
        $listingId = $paymentIntent->metadata->listing_id ?? null;
        if (!$listingId) {
            return;
        }

        $listing = Listing::find($listingId);
        if (!$listing) {
            return;
        }

        $listing->payment_status = 'paid';
        $listing->payment_id = $paymentIntent->id;
        $listing->payment_amount = $paymentIntent->amount / 100;
        $listing->payment_date = now();
        if (!$listing->slug) {
            $listing->slug = $listing->createSlug();
        }
        $listing->save();
    }
}