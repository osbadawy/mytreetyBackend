<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\CombinedOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaypalController extends Controller
{

    /**
     * @param Request $request
     * @return void
     */
    public function webhook(Request $request)
    {
        // Creating an environment
        $clientId = env('PAYPAL_CLIENT_ID');
        $clientSecret = env('PAYPAL_CLIENT_SECRET');

        if (get_setting('paypal_sandbox') == 1) {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        } else {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        }
        $client = new PayPalHttpClient($environment);

        if ($request->resource['id'] && $request->resource['status']) {

            $payment_id = $request->resource['id'];
            $status = $request->resource['status'];

            $combined_order = CombinedOrder::where('request', $payment_id)->first();

            if ($status == 'APPROVED' || $status == 'COMPLETED') {
                $order_capture_request = new OrdersCaptureRequest($payment_id);
                $order_capture_request->prefer('return=representation');
                $payment_response = $client->execute($order_capture_request);

                if ($combined_order) {
                    $order=Order::where('combined_order_id',$combined_order->id)->first();
                    if($order->payment_status != 'paid'){
                        checkout_done($combined_order->id, json_encode($payment_response));
                    }
                }
                else{
                    \Log::debug($request);
                }
            }


        } else {
            \Log::debug($request);
        }
    }
}
