<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\PurchaseHistoryCollection;
use App\Http\Resources\V2\PurchaseHistoryMiniCollection;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PurchaseHistoryController extends Controller
{
    /**
     * @param Request $request
     * @return PurchaseHistoryMiniCollection
     */
    public function index(Request $request): PurchaseHistoryMiniCollection
    {
        //Prepare order query
        $order_query = Order::query();

        //Filter by payment_status
        if ($request->payment_status != "") {
            $this->filterByPaymentStatus($order_query, $request->payment_status);
        } else {
            $this->filterByPaymentStatus($order_query, 'paid');
        }

        //Filter by delivery_status
        if ($request->delivery_status != "") $this->filterByDeliveryStatus($order_query, $request->delivery_status);

        return new PurchaseHistoryMiniCollection($order_query->where('user_id', $request->user()->id)->latest()->paginate(5));
    }

    /**
     * @param Builder $order_query
     * @param $payment_status
     * @return void
     */
    public function filterByPaymentStatus(Builder $order_query, $payment_status): void
    {
        $order_query->where('payment_status', $payment_status);
    }

    /**
     * @param Builder $order_query
     * @param $delivery_status
     * @return void
     */
    public function filterByDeliveryStatus(Builder $order_query, $delivery_status): void
    {
        $order_query->whereIn("id", function ($query) use ($delivery_status) {
            $query->select('order_id')
                ->from('order_details')
                ->where('delivery_status', $delivery_status);
        });
    }

    /**
     * @param $code
     * @param Request $request
     * @return PurchaseHistoryCollection
     */
    public function details($code, Request $request): PurchaseHistoryCollection
    {
        //Get order details
        $order_query = Order::where('code', $code)->where('user_id', $request->user()->id);
        return new PurchaseHistoryCollection($order_query->get());
    }
}
