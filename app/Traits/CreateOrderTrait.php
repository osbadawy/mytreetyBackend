<?php

namespace App\Traits;

use App\Http\Controllers\Api\V2\ShopifySyncController;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Charity;
use App\Models\CombinedOrder;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

trait CreateOrderTrait
{

    /**
     * @param Address $address
     * @param $email
     * @return array
     */
    public function getShippingAddress(Address $address, $email): array
    {
        $full_name = $address->name;
        $country = 'Germany';
        $city = '';

        if ($address->country) {
            $country = $address->country->name;
        }

        if ($address->state) {

            $city = $address->state->name;
        }
        $street = $address->address;
        $postal_code = $address->postal_code;
        $phone = $address->phone;

        $shippingAddress['name'] = $full_name;
        $shippingAddress['email'] = $email;
        $shippingAddress['address'] = $street;
        $shippingAddress['country'] = $country;
        $shippingAddress['state'] = $street;
        $shippingAddress['city'] = $city;
        $shippingAddress['postal_code'] = $postal_code;
        $shippingAddress['phone'] = $phone;

        return $shippingAddress;
    }

    /**
     * @param $user_id
     * @param array $shippingAddress
     * @return CombinedOrder
     */
    public function createCombinedOrder($user_id, array $shippingAddress): CombinedOrder
    {
        $combined_order = new CombinedOrder;
        $combined_order->user_id = $user_id;
        $combined_order->type_id = 0;
        $combined_order->shipping_address = json_encode($shippingAddress);
        $combined_order->save();
        return $combined_order;
    }


    /**
     * @param $cartItems
     * @return array
     */
    public function getSellerProducts($cartItems): array
    {
        $seller_products = array();
        foreach ($cartItems as $cartItem) {
            $product_ids = array();
            $product = Product::find($cartItem['product_id']);
            if (isset($seller_products[$product->user_id])) {
                $product_ids = $seller_products[$product->user_id];
            }
            $product_ids[] = $cartItem;
            $seller_products[$product->user_id] = $product_ids;
        }
        return $seller_products;
    }


    /**
     * @param CombinedOrder $combined_order
     * @param array $shippingAddress
     * @param $charity
     * @param $payment_option
     * @return Order
     */
    public function createOrder(CombinedOrder $combined_order, array $shippingAddress, $charity, $payment_option): Order
    {
        $order = new Order;
        $order->combined_order_id = $combined_order->id;
        $order->user_id = \Auth::user()->id;
        $order->shipping_address = json_encode($shippingAddress);
        $order->delivery_viewed = '0';
        $order->payment_status_viewed = '0';
        $order->charity = $charity;
        $order->code = date('Ymd-His') . rand(10, 99);
        $order->date = strtotime('now');
        $order->payment_type = $payment_option;
        $order->payment_status = 'unpaid';
        $order->save();
        return $order;
    }

    /**
     * @param $product_stock
     * @param $product
     * @param $quantity
     * @return void
     */
    public function shopifyStockSync($product_stock, $product, $quantity): void
    {
        if ($product_stock->inventory_item_id > 0) {
            $seller_id = $product->user->id;
            $inventory_item_id = $product_stock->inventory_item_id;
            $qty = - ($quantity);
            (new ShopifySyncController)->UpdateStock($seller_id, $inventory_item_id, $qty);
        }
    }

    /**
     * @param float $sum
     * @param float $total_shipping_cost
     * @return float
     */
    public function calculateDonation(float $sum, float $total_shipping_cost): float
    {
        return 0.005 * ($sum - $total_shipping_cost);
    }


    /**
     * @param $user_id
     * @param $coupon_code
     * @return void
     */
    public function couponUsage($user_id, $coupon_code): void
    {
        $coupon_usage = new CouponUsage;
        $coupon_usage->user_id = $user_id;
        $coupon_usage->coupon_id = Coupon::where('code', $coupon_code)->first()->id;
        $coupon_usage->save();
    }

    /**
     * @param $seller
     * @param $quantity
     * @return void
     */
    public function updateSellerSales($seller, $quantity): void
    {
        $seller->num_of_sale += $quantity;
        $seller->save();
    }

    /**
     * @param $order
     * @param $product
     * @param $product_variation
     * @param $cartItem
     * @param $shipping_cost
     * @return OrderDetail
     */
    public function createOrderDetail($order, $product, $product_variation, $cartItem, $shipping_cost): OrderDetail
    {
        $order_detail = new OrderDetail;
        $order_detail->order_id = $order->id;
        $order_detail->seller_id = $product->user_id;
        $order_detail->product_id = $product->id;
        $order_detail->variation = $product_variation;
        $order_detail->price = $cartItem['price'] * $cartItem['quantity'];
        $order_detail->tax = $cartItem['tax'] * $cartItem['quantity'];
        $order_detail->shipping_cost = $shipping_cost;
        $order_detail->quantity = $cartItem['quantity'];
        $order_detail->save();
        return $order_detail;
    }

    /**
     * @param $quantity
     * @param $product
     * @return void
     */
    public function updateProductSalesCount($quantity, $product): void
    {
        $product->num_of_sale += $quantity;
        $product->save();
    }

    /**
     * @param Order $order
     * @param CombinedOrder $combined_order
     * @param $product
     * @return JsonResponse
     */
    public function notEnoughQty(Order $order, CombinedOrder $combined_order, $product): JsonResponse
    {
        $order->delete();
        $combined_order->delete();

        return response()->json([
            'combined_order_id' => 0,
            'result' => false,
            'message' => translate('The requested quantity is not available for ') . $product->name
        ], 400);
    }

    /**
     * @return JsonResponse
     */
    public function returnIfCartIsEmpty(): JsonResponse
    {
        return response()->json([
            'combined_order_id' => 0,
            'result' => false,
            'message' => translate('Cart is Empty')
        ], 400);
    }

    /**
     * @return JsonResponse
     */
    public function returnIfInvalidReferral(): JsonResponse
    {
        return response()->json([
            'combined_order_id' => 0,
            'result' => false,
            'message' => translate('Invalid Referral code')
        ], 400);
    }

        /**
     * @return JsonResponse
     */
    public function returnIfUsedReferral(): JsonResponse
    {
        return response()->json([
            'combined_order_id' => 0,
            'result' => false,
            'message' => translate('Already used this referral code')
        ], 400);
    }

    /**
     * @return JsonResponse
     */
    public function returnIfProductNotFound(): JsonResponse
    {
        return response()->json([
            'combined_order_id' => 0,
            'result' => false,
            'message' => translate('Product Not Found')
        ], 400);
    }

    /**
     * @param $product_variation
     * @param $product_id
     * @param $product
     * @return JsonResponse
     */
    public function returnIfVariationNotFound($product_variation, $product_id, $product): JsonResponse
    {
        return response()->json([
            'combined_order_id' => 0,
            'result' => false,
            'product_variation' => $product_variation,
            'product_id' => $product_id,
            'message' => $product->name . " Not Available"
        ], 400);
    }

    /**
     * @param $quantity
     * @param $product_stock
     * @return void
     */
    public function reduceProductQtyStock($quantity, $product_stock): void
    {
        $product_stock->qty -= $quantity;
        $product_stock->save();
    }

    /**
     * @param $product
     * @param $product_variation
     * @return mixed
     */
    public function getProductStock($product, $product_variation)
    {
        $product_stock = $product->stocks->where('variant', $product_variation)->first();

        if (!$product_stock) {
            $product_stock = $product->stocks->first();
        }
        return $product_stock;
    }


    /**
     * @param $user_id
     * @return void
     */

    public function clearUserCart($user_id): void
    {
        Cart::where('user_id', $user_id)->delete();
    }
}
