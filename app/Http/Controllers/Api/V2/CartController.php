<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Requests\CreateCartRequest;
use App\Models\Cart;
use App\Models\Charity;
use App\Models\Product;
use App\Models\User;
use App\Traits\PointsTrait;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    use PointsTrait;
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function summary(Request $request): JsonResponse
    {
        $sum = 0.00;
        $subtotal = 0.00;


        //Get user cart
        $items = Cart::where('user_id', $request->user()->id)->get();

        //Return if empty cart
        if ($items->isEmpty()) {
            return $this->returnIfCartIsEmpty();
        }

        //Calculate Cart items
        foreach ($items as $cartItem) {
            $item_sum = 0.00;
            $item_sum += ($cartItem->price) * $cartItem->quantity;
            $sum += $item_sum;
            $subtotal += $cartItem->price * $cartItem->quantity;
        }

        //Calculate shipping cost
        $total_shipping_cost = $this->CalculateTotalShippingCost($items);

        //Calculate donation
        $donation = $this->calculateDonation($sum, $total_shipping_cost);

        //Calculate points discount
        $points = User::find($request->user()->id)->points;
        $max_points_discount = $this->GetMaxPointsPercentage($points, $subtotal);

        $points_discount = $items[0]->points_discount;

        $total_discount = $points_discount + $items[0]->referral_discount + $items[0]->coupon_discount;

        if ($total_discount > $subtotal) {
            $total_discount = $subtotal;
        }

        if ($total_discount < 0) {
            $total_discount = 0;
        }

        $sum = $sum - $total_discount;

        return response()->json([
            'sub_total' => format_price($subtotal),
            'shipping_cost' => format_price($total_shipping_cost),
            'donation' => format_price($donation),
            'total_discount' => format_price($total_discount),
            'grand_total' => format_price($sum),
            'grand_total_value' => convert_price(round($sum)),
            'referral_code' => $items[0]->referral_code,
            'referral_discount' => format_price($items[0]->referral_discount),
            'coupon_code' => $items[0]->coupon_code,
            'coupon_discount' => format_price($items[0]->coupon_discount),
            'points_discount' => format_price($points_discount),
            'max_points_percentage' => $max_points_discount,
            'points_applied' => $items[0]->points_applied,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function returnIfCartIsEmpty(): JsonResponse
    {
        return response()->json([
            'sub_total' => format_price(0.00),
            'shipping_cost' => format_price(0.00),
            'discount' => format_price(0.00),
            'grand_total' => format_price(0.00),
            'grand_total_value' => 0.00,
            'coupon_code' => "",
            'donation' => 0,
            'coupon_applied' => false,
        ]);
    }

    /**
     * @param $items
     * @return mixed
     */
    public function CalculateTotalShippingCost($items)
    {
        return $items->sum('shipping_cost');
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
     * @param Request $request
     * @return JsonResponse
     */
    public function getList(Request $request): JsonResponse
    {

        //Get user cart
        $items = Cart::where('user_id', $request->user()->id)->get();


        //Get cart items data
        $items_data = $this->getCartItems($items->toArray());


        return response()->json(['data' => $items_data], 200);
    }

    /**
     * @param array $shop_items_raw_data
     * @return array
     */
    public function getCartItems(array $shop_items_raw_data): array
    {
        $shop_items_data = array();
        foreach ($shop_items_raw_data as $shop_items_raw_data_item) {
            $product = Product::where('id', $shop_items_raw_data_item["product_id"])->first();
            $shop_items_data_item["id"] = intval($shop_items_raw_data_item["id"]);

            //Check if the product is in wishlist
            $is_fav = $this->checkFavorite($shop_items_raw_data_item["product_id"]);

            $thumbnail_image = $product->thumbnail_img;

            if (!$thumbnail_image || is_numeric($thumbnail_image)) {
                $thumbnail_image = 'https://mytreety.s3.eu-central-1.amazonaws.com/products/default.png';
            }
            $shop_items_data_item["product_id"] = intval($shop_items_raw_data_item["product_id"]);
            $shop_items_data_item["product_name"] = $product->name;
            $shop_items_data_item["product_slug"] = $product->slug;
            $shop_items_data_item["is_fav"] = $is_fav;
            $shop_items_data_item["thumbnail_image"] = $thumbnail_image;
            $shop_items_data_item["variation"] = $shop_items_raw_data_item["variation"];
            $shop_items_data_item["price"] = format_price($shop_items_raw_data_item["price"]);
            $shop_items_data_item["quantity"] = intval($shop_items_raw_data_item["quantity"]);
            $shop_items_data_item["lower_limit"] = intval($product->min_qty);
            $qstk = 10;
            $stk = $product->stocks->where('variant', $shop_items_raw_data_item['variation'])->first();
            if ($stk) {
                $stkq = $stk->qty;
                if (intval($stkq) < 10 && intval($stkq) > 0) {
                    $qstk = $stkq;
                }
            }
            $shop_items_data_item["upper_limit"] = intval($qstk);

            $shop_items_data[] = $shop_items_data_item;
        }
        return $shop_items_data;
    }

    /**
     * @param $product_id
     * @return int
     */
    public function checkFavorite($product_id): int
    {
        $is_fav = 0;
        if (Auth::user()) {
            $wishlists = Auth::user()->wishlists;
            foreach ($wishlists as $key => $wishlist) {
                if ($wishlist->product) {
                    if ($wishlist->product->id == $product_id) {
                        $is_fav = 1;
                    }
                }
            }
        }
        return $is_fav;
    }

    public function add(CreateCartRequest $request): JsonResponse
    {

        //Get products from request
        $products = $request->products;

        foreach ($products as $key => $prod) {

            //Get product details
            $product = Product::where('id', $prod['product_id'])->first();

            //Return if product not exist
            if (!$product) {
                return $this->returnIfProductNotFound($prod['product_id']);
            }


            $color = '';
            $size = '';

            if (array_key_exists('size', $prod)) {
                $size = $prod['size'];
            }

            $variant = $size;

            if (array_key_exists('color', $prod)) {
                $color = $prod['color'];

                if ($variant != '') {
                    $variant = $variant . ' ' . $color;
                } else {
                    $variant = $color;
                }
            }



            if ($variant == '')
                $price = $product->unit_price;
            else {
                $product_stock = $product->stocks->where('variant', $variant)->first();
                if (!$product_stock) {
                    return $this->returnIfProductNotFound($prod['product_id']);
                }
                $price = $product_stock->price;
            }

            //discount calculation based regular discount

            $price = $this->calculateDiscount($product, $price);

            //Check product qty
            if ($product->min_qty > $prod['quantity']) {
                return response()->json(['result' => false, 'message' => translate("Minimum") . " {$product->min_qty} " . translate("item(s) should be ordered"), 'product_id' => $prod['product_id']], 200);
            }

            //Set default max stock
            $stock = 10;

            //Get product real stock
            if (isset($prod['size']) || isset($prod['color'])) {
                $stock = $product->stocks->where('variant', $variant)->first();
                if (!$stock) {
                    return $this->returnIfProductNotFound($prod['product_id']);
                }
                $stock = $stock->qty;
            }

            //Check item stock
            $variant_string = $variant != null && $variant != "" ? translate("for") . " ($variant)" : "";
            if ($stock < $prod['quantity']) {
                if ($stock == 0) {
                    return response()->json(['result' => false, 'message' => translate("Stock out")], 200);
                } else {
                    return response()->json(['result' => false, 'message' => translate("Only") . " {$stock} " . translate("item(s) are available") . " {$variant_string}", 'product_id' => $prod['product_id']], 200);
                }
            }
            $qty = $prod['quantity'];

            //Save product to user cart
            $this->saveCart($request, $product, $variant, $price, $qty);
        }

        //remove points discount
        $carts = $request->user()->carts;
        foreach ($carts as $key => $cart) {
            $cart->points_discount = 0;
            $cart->points_applied = 0;
            $cart->save();
        }

        return response()->json([
            'result' => true,
            'message' => translate('Added to cart successfully')
        ]);
    }

    /**
     * @param $product_id
     * @return JsonResponse
     */
    public function returnIfProductNotFound($product_id): JsonResponse
    {
        return response()->json(['result' => false, 'message' => translate('Product Not found'), 'product_id' => $product_id], 400);
    }

    /**
     * @param $product
     * @param $price
     * @return float|int
     */
    public function calculateDiscount($product, $price)
    {
        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }
        return $price;
    }

    /**
     * @param CreateCartRequest $request
     * @param $product
     * @param string $variant
     * @param $price
     * @param $qty
     * @return void
     */
    public function saveCart(CreateCartRequest $request, $product, string $variant, $price, $qty): void
    {
        Cart::updateOrCreate([
            'user_id' => $request->user()->id,
            'owner_id' => $product->user_id,
            'product_id' => $product->id,
            'variation' => $variant
        ], [
            'price' => $price,
            'quantity' => DB::raw("quantity + $qty")
        ]);
    }

    public function changeQuantity(Request $request): JsonResponse
    {
        //Get cart
        $cart = Cart::where('id', $request->id)->where('user_id', $request->user()->id)->first();

        if ($cart != null) {

            //remove points discount
            $carts = $request->user()->carts;
            foreach ($carts as $key => $cart) {
                $cart->points_discount = 0;
                $cart->points_applied = 0;
                $cart->save();
            }

            //Get variation
            $variation = $cart->product->stocks->where('variant', $cart->variation)->first();
            if (!$variation) {
                $variation = $cart->product->stocks->first();
            }

            //Check product aty
            if ($variation >= $request->quantity) {

                //Update cart
                $cart->update([
                    'quantity' => $request->quantity
                ]);

                return response()->json(['result' => true, 'message' => translate('Cart updated')], 200);
            } else {
                return response()->json(['result' => false, 'message' => translate('Maximum available quantity reached')], 200);
            }
        }

        return response()->json(['result' => false, 'message' => translate('Something went wrong')], 400);
    }

    public function destroy($id, Request $request): JsonResponse
    {
        //Get cart
        $cart = Cart::where('user_id', $request->user()->id)->where('id', $id)->first();

        //Check if cart exist
        if (!$cart) {
            return response()->json(['result' => false, 'message' => translate('Cart Not Found')], 400);
        }

        //Delete cart
        $cart->delete();

        //remove points discount
        $carts = $request->user()->carts;
        foreach ($carts as $key => $cart) {
            $cart->points_discount = 0;
            $cart->points_applied = 0;
            $cart->save();
        }

        return response()->json(['result' => true, 'message' => translate('Product is successfully removed from your cart')], 200);
    }
}
