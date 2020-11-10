<?php
\Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
$endpoint_secret = 'whsec_BfNl4Lg04H3uJDAssXWKT5ZVO5txNHeI';
$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
} catch(\UnexpectedValueException $e) {
    http_response_code(400);
    exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400);
    exit();
}

switch ($event->type) {
    case 'payment_intent.succeeded':
        $paymentIntent = $event->data->object;
        $id = $paymentIntent->metadata->listing_id;
//        file_put_contents('test.txt', $id);
        $listing = \App\Models\Listing::where('id', $id)->first();
        $listing->paid = true;
        $listing->payment_id = $paymentIntent->id;
        $listing->payment_amount = $paymentIntent->amount / 100;
        $listing->payment_date = date("Y-m-d H:i:s");
        $listing->slug = $listing->createSlug();
        $listing->save();
        break;

    case 'payment_method.attached':
        $paymentMethod = $event->data->object;
        break;

    default:
        echo 'Received unknown event type ' . $event->type;
}
echo 'test';
http_response_code(200);
