<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\CombinedOrder;
use Illuminate\Http\Request;

class StripeController extends Controller
{

    /**
     * @param Request $request
     * @return void
     */
    public function webhook(Request $request)
    {
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(401);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // Handle the event

        switch ($event->type) {
            case 'charge.refunded':
            case 'charge.failed':
                $charge = $event->data->object;
                break;
            case 'charge.succeeded':
                $charge = $event->data->object;
                $id = $charge->payment_intent;
                $combined_order_id = CombinedOrder::where('request', $id)->first()->id;
                $payment = $event->data->object;
                checkout_done($combined_order_id, json_encode($payment));
                break;
            default:
                echo 'Received unknown event type ' . htmlspecialchars($event->type);
        }

    }
}
