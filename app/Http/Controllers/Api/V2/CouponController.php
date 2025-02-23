<?php


namespace App\Http\Controllers\Api\V2;


use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\UsedReferralCode;
use App\Models\User;
use App\Traits\PointsTrait;
use Illuminate\Http\Request;

class CouponController
{
    use PointsTrait;

    public function apply_coupon_code(Request $request)
    {
        $user_id = $request->user()->id;
        $cart_items = Cart::where('user_id', $user_id)->get();
        $sum = 0.0;
        $subtotal = 0.0;

        if ($cart_items->isEmpty()) {
            return response()->json([
                'result' => false,
                'message' => translate('Cart is empty')
            ], 400);
        }

        //check if it is a referral code
        if (strpos($request->coupon_code, 'mytreety') === 0) {
            // It starts with 'mytreety'
            $referral_code = $request->coupon_code;

            if (UsedReferralCode::where('user_id', $request->user()->id)->where('referral_code', $referral_code)->exists()) {

                return response()->json([
                    'combined_order_id' => 0,
                    'result' => false,
                    'message' => translate('Already used this referral code')
                ], 400);
            }

            //Calculate Cart items
            foreach ($cart_items as $cartItem) {
                $item_sum = 0.00;
                $item_sum += ($cartItem->price) * $cartItem->quantity;
                $sum += $item_sum;
                $subtotal += $cartItem->price * $cartItem->quantity;
            }

            $referral_discount = ($subtotal - $cart_items[0]->points_discount) * 0.05;

            if ($cart_items->first()->referral_code) {
                return response()->json([
                    'result' => false,
                    'message' => translate('You already used a referral!')
                ], 400);
            }
            if (!User::where('referral_code', $referral_code)->exists()) {
                return response()->json([
                    'result' => false,
                    'message' => translate('Invalid referral code!')
                ], 400);
            }

            foreach ($cart_items as $key => $cart_item) {
                $cart_item->referral_code = $referral_code;
                $cart_item->referral_discount = $referral_discount;
                $cart_item->discount = $cart_item->discount + $referral_discount;
                $cart_item->save();
            }
        }
        //apply coupn
        else {

            $coupon = Coupon::where('code', $request->coupon_code)->first();
            if ($coupon == null) {
                return response()->json([
                    'result' => false,
                    'message' => translate('Invalid coupon code!')
                ], 400);
            }

            $in_range = strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date;

            if (!$in_range) {
                return response()->json([
                    'result' => false,
                    'message' => translate('Coupon expired!')
                ], 400);
            }

            $is_used = CouponUsage::where('user_id', $user_id)->where('coupon_id', $coupon->id)->first() != null;

            if ($is_used) {
                return response()->json([
                    'result' => false,
                    'message' => translate('You already used this coupon!')
                ], 400);
            }


            $coupon_details = json_decode($coupon->details);

            $subtotal = 0;
            $shipping = 0;
            $user_id = $request->user()->id;

            foreach ($cart_items as $key => $cartItem) {
                $subtotal += $cartItem['price'] * $cartItem['quantity'];
                $shipping += $cartItem['shipping'] * $cartItem['quantity'];
            }
            $sum = $subtotal + $shipping;

            if ($sum >= $coupon_details->min_buy) {
                if ($coupon->discount_type == 'percent') {
                    $coupon_discount = (($sum - $cart_items[0]->points_discount) * $coupon->discount) / 100;
                    if ($coupon_discount > $coupon_details->max_discount) {
                        $coupon_discount = $coupon_details->max_discount;
                    }
                } elseif ($coupon->discount_type == 'amount') {
                    $coupon_discount = $coupon->discount;
                }

                foreach ($cart_items as $key => $cart_item) {
                    $cart_item->coupon_code = $request->coupon_code;
                    $cart_item->coupon_discount = $coupon_discount;
                    $cart_item->discount = $cart_item->discount + $coupon_discount;
                    $cart_item->save();
                }
            }
        }
        return response()->json([
            'result' => true,
            'message' => translate('Discount Applied')
        ]);
    }

    public function remove_coupon_code(Request $request)
    {
        $user_id = $request->user()->id;
        $cart_items = Cart::where('user_id', $user_id)->get();
        $sum = 0;
        $subtotal = 0;

        //Calculate Cart items
        foreach ($cart_items as $cartItem) {
            $item_sum = 0.00;
            $item_sum += ($cartItem->price) * $cartItem->quantity;
            $sum += $item_sum;
            $subtotal += $cartItem->price * $cartItem->quantity;
        }

        //check if it is a referral code
        if (strpos($request->coupon_code, 'mytreety') === 0) {

            // It starts with 'mytreety'
            $referral_discount = $cart_items[0]->referral_discount;

            foreach ($cart_items as $key => $cart_item) {
                $cart_item->referral_code = null;
                $cart_item->referral_discount = 0;
                $cart_item->discount = $cart_item->discount - $referral_discount;
                $cart_item->save();
            }
            return response()->json([
                'result' => true,
                'message' => translate('Referral Removed')
            ]);
        } else {
            $coupon_discount = $cart_items[0]->coupon_discount;

            foreach ($cart_items as $key => $cart_item) {
                $cart_item->coupon_code = null;
                $cart_item->coupon_applied = null;
                $cart_item->coupon_discount = 0;
                $cart_item->discount = $cart_item->discount - $coupon_discount;
                $cart_item->save();
            }
            return response()->json([
                'result' => true,
                'message' => translate('Coupon Removed')
            ]);
        }
    }



    public function apply_points(Request $request)
    {
        $user_id = $request->user()->id;
        $cart_items = Cart::where('user_id', $user_id)->get();
        $subtotal = 0.0;
        $sum = 0.0;

        if ($cart_items->isEmpty()) {
            return response()->json([
                'result' => false,
                'message' => translate('Cart is empty')
            ], 400);
        }
        $points_percentage = $request->points_percentage / 100;

        //Calculate Cart items
        foreach ($cart_items as $cartItem) {
            $item_sum = 0.00;
            $item_sum += ($cartItem->price) * $cartItem->quantity;
            $sum += $item_sum;
            $subtotal += $cartItem->price * $cartItem->quantity;
        }

        $points_discount = $points_percentage * $subtotal;

        if ($points_discount > $subtotal) {
            $points_discount = $subtotal;
        }

        $points = $points_discount * 100;


        //remove points discount
        if ($request->points_percentage == 0) {
            foreach ($cart_items as $key => $cart_item) {
                $total_discount = $cart_item->discount;
                if ($cart_item->points_discount > 0) {
                    $total_discount = $total_discount - $cart_item->points_discount;
                }

                $cart_item->points_applied = 0;
                $cart_item->points_discount =  0;
                $cart_item->discount = $total_discount;
                $cart_item->save();
            }
            return response()->json([
                'result' => true,
                'message' => translate('Points discount removed')
            ], 200);
        }


        //check if user can use points
        $max_percentage = $this->GetMaxPointsPercentage($request->user()->points, $subtotal);
        if ($request->points_percentage > $max_percentage) {
            return response()->json([
                'result' => false,
                'message' => translate("Sorry, max discount is $max_percentage% for you in this order")
            ], 400);
        }

        if (!$this->checkIfPointsAvailable($user_id, $points)) {
            return response()->json([
                'result' => false,
                'message' => translate('Insufficient points in balance')
            ], 400);
        }

        //check for coupon
        $coupon_discount = $cart_items[0]->coupon_discount;
        $coupon_code = $cart_items[0]->coupon_code;

        if ($coupon_discount > 0) {
            //re-calculate coupon discount
            $coupon = Coupon::where('code', $coupon_code)->first();
            $coupon_details = json_decode($coupon->details);
            if ($sum >= $coupon_details->min_buy) {
                if ($coupon->discount_type == 'percent') {
                    $coupon_discount = (($sum - $points_discount) * $coupon->discount) / 100;
                    if ($coupon_discount > $coupon_details->max_discount) {
                        $coupon_discount = $coupon_details->max_discount;
                    }
                } elseif ($coupon->discount_type == 'amount') {
                    $coupon_discount = $coupon->discount;
                }

                foreach ($cart_items as $key => $cart_item) {

                    $cart_item->save();
                }
            }
        }

        //check for referral
        $referral_discount = $cart_items[0]->referral_discount;

        if ($referral_discount > 0) {
            $referral_discount = ($subtotal - $cart_items[0]->points_discount) * 0.05;
        }


        //update discounts in cart
        foreach ($cart_items as $key => $cart_item) {
            $total_discount = $points_discount + $referral_discount + $coupon_discount;

            //update referral_discount & coupon_discount
            $cart_item->referral_discount = $referral_discount;
            $cart_item->coupon_discount = $coupon_discount;

            //update points_discount
            $cart_item->points_applied = $points;
            $cart_item->points_discount =  $points_discount;

            //total discount
            $cart_item->discount = $total_discount;
            $cart_item->save();
        }
        return response()->json([
            'result' => true,
            'message' => translate('Points discount applied')
        ], 200);
    }
}
