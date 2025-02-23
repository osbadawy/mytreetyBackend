<?php

namespace App\Traits;

use App\Models\CombinedOrder;
use App\Models\Order;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalHttp\HttpException;
use PayPalHttp\IOException;
use Stripe\Exception\ApiErrorException;

trait PaymentTrait

{


    /**
     * @param $payment_option
     * @param CombinedOrder $combined_order
     * @param $return_url
     * @return mixed
     * @throws ApiErrorException
     * @throws IOException
     */
    public function getPaymentUrl($payment_option, CombinedOrder $combined_order, $success_url,$cancel_url)
    {
        if ($payment_option == 'paypal') {
            $payment_session = $this->getUrlPaypal($combined_order->id,$success_url,$cancel_url);
            $payment_id = $payment_session['all']->id;
        } else {
            $payment_session = $this->getUrlStripe($combined_order->id, $payment_option,$success_url,$cancel_url);
            $payment_id = $payment_session['id'];
        }

        $url = $payment_session['url'];
        $combined_order->request = $payment_id;
        $combined_order->save();

        return $url;
    }


    /**
     * @param $combined_order_id
     * @param $return_url
     * @return array
     * @throws IOException
     */
    public function getUrlPaypal($combined_order_id, $success_url,$cancel_url): array
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


        $combined_order = CombinedOrder::find($combined_order_id);
        $amount = $combined_order->grand_total;


        $order_create_request = new OrdersCreateRequest();
        $order_create_request->prefer('return=representation');
        $order_create_request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => rand(000000, 999999),
                "amount" => [
                    "value" => number_format($amount, 2, '.', ''),
                    "currency_code" => \App\Models\Currency::find(get_setting('system_default_currency'))->code
                ]
            ]],
            "application_context" => [
                "cancel_url" => "$cancel_url",
                "return_url" => "$success_url",
            ]
        ];

        // Call API with your client and get a response for your call

        try {
            $response = $client->execute($order_create_request);
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            //return Redirect::to($response->result->links[1]->href);
            return ['result' => true, 'url' => $response->result->links[1]->href, 'all' => $response->result, 'message' => "Found redirect url"];
        } catch (HttpException $ex) {
            return ['result' => false, 'url' => $ex, 'all' => $ex, 'message' => "Could not find redirect url"];
        }
    }


    /**
     * @param $combined_order_id
     * @param $payment_option
     * @param $return_url
     * @return array|string
     * @throws ApiErrorException
     */
    public function getUrlStripe($combined_order_id, $payment_option, $success_url,$cancel_url)
    {
        $amount = 0;
        $codes = 0;

        $combined_order = CombinedOrder::find($combined_order_id);
        if (!$combined_order) {
            return '0';
        }
        $amount = round($combined_order->grand_total * 100);
        $order_codes = Order::where('combined_order_id', $combined_order_id)->select('code')->get()->toArray();

        if (count($order_codes) > 0) {
            $codes = implode(', ', $order_codes[0]);
        }
        $payment_method_types = ['card', 'klarna', 'sepa_debit', 'giropay', 'sofort'];

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        if ($payment_option) {
            if ($payment_option == 'card') {

                $payment_method_types = ['card'];

            } elseif ($payment_option == 'sofort') {

                $payment_method_types = ['sofort'];

            } elseif ($payment_option == 'sepa') {

                $payment_method_types = ['sepa_debit'];

            } elseif ($payment_option == 'giropay') {

                $payment_method_types = ['giropay'];

            } else {
                $payment_method_types = ['card', 'klarna', 'sepa_debit', 'giropay'];
            }
        }

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => $payment_method_types,
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code,
                        'product_data' => [
                            'name' => "Payment"
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => "$success_url?orderId=$codes",
            'cancel_url' => "$cancel_url",
        ]);


        return ['id' => $session->payment_intent, 'url' => $session->url];
    }


}
