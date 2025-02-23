<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\UsedReferralCode;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index(Request $request)
    {


        $referrals = UsedReferralCode::groupBy('referral_code')->get();
        $products = [];
        $new_referrals = $referrals->where('is_calculated', 0);

        foreach ($new_referrals as $new_referral) {
            $orders = $new_referral->orders;
            foreach ($orders as $order) {
                foreach ($order->orderDetails as $orderDetail) {
                    $product_id = $orderDetail->product_id;
                    DB::table('referral_products_count')
                        ->where('product_id', $product_id)
                        ->exists()
                        ? DB::table('referral_products_count')
                        ->where('product_id', $product_id)
                        ->increment('count')
                        : DB::table('referral_products_count')
                        ->insert(['product_id' => $product_id, 'count' => 1]);
                }
            }
            $new_referral->is_calculated = 1;
            $new_referral->save();
        }

        $products_counts = DB::table('referral_products_count')->select('product_id', 'count')->orderByDesc('count')->take(20)->get();
        foreach ($products_counts as $key => $products_count) {
            $product = Product::find($products_count->product_id);
            $products_count->product = $product;
        }
        foreach ($referrals as $key => $referral) {
            $referral->referral_owner_email = $referral->referral_owner->email;
            $referral->customer_email = $referral->customer->email;
            $referral->referral_owner_name = $referral->referral_owner->name;
            $referral->customer_name = $referral->customer->name;
            $referral->orders= Order::where('referral_code',$referral->referral_code)->get();
        }

        return view('backend.marketing.referrals.index', compact('referrals','products_counts'));
    }
}
