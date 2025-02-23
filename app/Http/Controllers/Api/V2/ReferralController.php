<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\PointsTrait;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    use PointsTrait;

    public function index(Request $request)
    {

        $user = $request->user();
        $referral_code = $user->referral_code;
        $orders = Order::where('referral_code', $referral_code)
            ->where('payment_status', 'paid')
            ->get();

        $referral_purchases = $orders->count();
        // Convert order_ids to array
        $orders = $orders->map(function ($order) {
            $order->order_id =  $order->id;
            $points_earned = 0;
            $point_history = DB::table('points_histories')->where('order_id', $order->id)->first();
            if ($point_history) {
                $points_earned = $point_history->points;
            }
            $order->points_earned = $points_earned;
            return $order;
        });

        $points_earned = $orders->sum('points_earned');
        $donations = $orders->sum('mytreety_donation');


        // Retrieve the referral orders for the user
        $referralOrders = Order::where('referral_code', $referral_code)
            ->where('payment_status', 'paid')
            ->selectRaw('COUNT(*) as count, DATE_FORMAT(created_at, "%b") as month')
            ->groupBy('month')
            ->get();

        // Group the referral orders by month
        $referralOrdersByMonth = collect([]);

        foreach ($referralOrders as $referralOrder) {
            $month = $referralOrder->month;
            $count = $referralOrder->count;

            // Add a new entry for this month
            $referralOrdersByMonth[$month] = $count;
        }

        // Fill in any missing months with a count of zero
        $startDate = Carbon::now()->subYear();
        $endDate = Carbon::now();
        $allMonths = $this->getMonthsBetween($startDate, $endDate);

        $referralCounts = [];

        foreach ($allMonths as $month) {
            $monthStr = $month->format('b');
            $count = isset($referralOrdersByMonth[$monthStr]) ? $referralOrdersByMonth[$monthStr] : 0;
            $referralCounts[$monthStr] = $count;
        }

        // Retrieve product count and point_earned for each product ID associated with the referral code
        $products = Order::whereHas('orderDetails', function ($query) use ($referral_code) {
            $query->where('referral_code', $referral_code);
            $point_earned = 10;
        })
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->groupBy('order_details.product_id')
            ->selectRaw('order_details.order_id,order_details.product_id, COUNT(order_details.product_id) as count, products.name as product_name, products.thumbnail_img as product_image,products.slug as product_slug, GROUP_CONCAT(orders.id) as order_ids')
            ->orderBy('count')
            ->take(3)
            ->get();

        // Convert order_ids to array
        $products = $products->map(function ($product) {
            $product->order_ids = explode(',', $product->order_ids);
            $product->points_earned = DB::table('points_histories')->whereIn('order_id', $product->order_ids)->get()->sum('points');
            return $product;
        });

        return response()->json([
            'referral_purchases' => (int)$referral_purchases,
            'points_earned' => $points_earned,
            'donations' => format_price($donations),
            'referralCounts' => $referralOrdersByMonth,
            'top_products' =>  $products
        ]);
    }

    public function all(Request $request)
    {

        // Retrieve the authenticated user object from the request
        $user = $request->user();

        // Retrieve the referral code associated with the user
        $referral_code = $user->referral_code;

        // Retrieve product count and point_earned for each product ID associated with the referral code
        $products = Order::whereHas('orderDetails', function ($query) use ($referral_code) {
            $query->where('referral_code', $referral_code);
            $point_earned = 10;
        })
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->groupBy('order_details.product_id')
            ->selectRaw('order_details.order_id,order_details.product_id, COUNT(order_details.product_id) as count, products.name as product_name, products.thumbnail_img as product_image,products.slug as product_slug, GROUP_CONCAT(orders.id) as order_ids')
            ->get();

        // Convert order_ids to array
        $products = $products->map(function ($product) {
            $product->order_ids = explode(',', $product->order_ids);
            $product->points_earned = DB::table('points_histories')->whereIn('order_id', $product->order_ids)->get()->sum('points');
            return $product;
        });


        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }


    private function getMonthsBetween(Carbon $startDate, Carbon $endDate)
    {
        $months = [];

        while ($startDate <= $endDate) {
            $months[] = $startDate->copy();
            $startDate->addMonth();
        }

        return $months;
    }
}
