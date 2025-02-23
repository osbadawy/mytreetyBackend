<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Product;
use App\Models\UsedReferralCode;
use App\Models\User;
use App\Traits\CreateOrderTrait;
use App\Traits\PaymentTrait;
use App\Traits\PointsTrait;
use Illuminate\Http\JsonResponse;
use PayPalHttp\IOException;
use Stripe\Exception\ApiErrorException;

class OrderController extends Controller

{
    use PaymentTrait, CreateOrderTrait, PointsTrait;


    /**
     * @param CreateOrderRequest $request
     * @return JsonResponse
     * @throws ApiErrorException
     * @throws IOException
     */
    public function store(CreateOrderRequest $request): JsonResponse
    {

        $user = $request->user();
        $payment_option = $request->payment_option;
        $charity = $request->charity_id;
        $address_id = $request->address_id;

        //Get Cart items
        $cartItems = Cart::where('user_id', $user->id)->get();

        //Return if cart is empty
        if ($cartItems->isEmpty()) {
            return $this->returnIfCartIsEmpty();
        }


        //Get shipping address
        $address = Address::where('id', $address_id)->where('user_id', $user->id)->first();

        //Return if address is empty
        if (!$address) {
            return response()->json(['success' => false, 'message' => translate('Address not found')], 400);
        }

        //Get shipping address object
        $shippingAddress = $this->getShippingAddress($address, $user->email);


        //Create combined order
        $combined_order = $this->createCombinedOrder($user->id, $shippingAddress);

        //Separate seller products
        $seller_products = $this->getSellerProducts($cartItems);

        //Create order for every seller
        foreach ($seller_products as $seller_product) {

            //Reset variables
            $subtotal = 0;
            $shipping = 0;
            $coupon_discount = 0;

            //Initiate new order
            $order = $this->createOrder($combined_order, $shippingAddress, $charity, $payment_option);


            //Store order details for every item
            foreach ($seller_product as $cartItem) {
                $product = Product::find($cartItem['product_id']);

                if (!$product) {
                    $this->returnIfProductNotFound();
                }

                //Delete product from wishlist
                $wishlist = $user->wishlists->where('product_id', $product->id)->first();
                if ($wishlist) {
                    $wishlist->delete();
                }

                //Get shipping cost
                ($product->shipping_cost) ? $shipping_cost = $product->shipping_cost : $shipping_cost = 0;

                //Add price to subtotal
                $subtotal += $cartItem['price'] * $cartItem['quantity'];

                $coupon_discount = $cartItem['coupon_discount'];

                //Get product variation
                $product_variation = $cartItem['variation'];

                $product_stock = $this->getProductStock($product, $product_variation);

                //Return if product variation not found
                if (!$product_stock) {
                    return $this->returnIfVariationNotFound($product_variation, $cartItem['product_id'], $product);
                }

                //Check if we have quantity
                if ($cartItem['quantity'] > $product_stock->qty) {
                    return $this->notEnoughQty($order, $combined_order, $product);
                }

                //Reduce product qty stock
                $this->reduceProductQtyStock($cartItem['quantity'], $product_stock);


                // Shopify stock sync two-ways
                // $this->shopifyStockSync($product_stock, $product, $cartItem['quantity']);

                //Create order detail for cart item
                $order_detail = $this->createOrderDetail($order, $product, $product_variation, $cartItem, $shipping_cost);

                //Update product number of sales
                $this->updateProductSalesCount($cartItem['quantity'], $product);

                //Update seller sales
                if ($product->user->seller != null) {
                    $this->updateSellerSales($product->user->seller, $cartItem['quantity']);
                }
                //Add product shipping cost to total order shipping
                $shipping += $order_detail->shipping_cost;

                //Attach seller id to the order
                $order->seller_id = $product->user_id;
            }

            //Update order grand total
            $grand_total = $subtotal + $shipping;
            $order->sub_total = $subtotal;
            $order->grand_total = $grand_total;
            $order->mytreety_donation = $this->calculateDonation($grand_total, $shipping);


            //Apply points discount
            $points_applied = $cartItems[0]->points_applied;
            $points_discount = $cartItems[0]->points_discount;

            if ($points_applied > 0) {

                if (!$this->checkIfPointsAvailable($request->user()->id, $points_applied)) {
                    return response()->json([
                        'result' => false,
                        'message' => translate('Insufficient points in balance')
                    ], 400);
                }

                $order->points_discount = $points_discount;
                $order->points_applied = floor($points_applied);
                $order->grand_total -= $points_discount;

            }


            //Apply referral code
            $referral_code = $cartItems[0]->referral_code;
            if ($referral_code) {

                if (!User::where('referral_code', $referral_code)->exists()) {
                    return $this->returnIfInvalidReferral();
                }

                if (UsedReferralCode::where('user_id', $request->user()->id)->where('referral_code', $referral_code)->exists()) {

                    return $this->returnIfUsedReferral();
                }

                $referral_discount = $cartItems[0]->referral_discount;

                $order->referral_discount = $referral_discount;
                $order->referral_code = $referral_code;
                $order->grand_total -= $referral_discount;

                $referral_code_used = new UsedReferralCode;
                $referral_code_used->user_id = $request->user()->id;
                $referral_code_used->referral_code = $referral_code;
                $referral_code_used->save();


            }

            //Apply Coupon
            if ($seller_product[0]->coupon_code != null) {

                $order->coupon_discount = $coupon_discount;
                $order->grand_total -= $coupon_discount;

                $this->couponUsage($user->id, $seller_product[0]->coupon_code);
            }

            //Update combined order grand total
            $combined_order->grand_total += $order->grand_total;

            //Update order & combined order in db
            $order->save();
            $combined_order->save();
        }


        //Clear user cart
        $this->clearUserCart($user->id);


        //Get payment link
        $return_url = env('FRONTEND_URL', 'https://mytreety.com');
        $success_url = "$return_url/thank-you";
        $cancel_url = "$return_url/cancel";
        $payment_url = $this->getPaymentUrl($payment_option, $combined_order, $success_url, $cancel_url);


        return response()->json([
            'combined_order_id' => $combined_order->id,
            'url' => $payment_url,
            'result' => true,
            'message' => translate('Your order has been placed successfully')
        ]);
    }
}
