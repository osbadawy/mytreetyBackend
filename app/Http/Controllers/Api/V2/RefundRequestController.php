<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Requests\RefundStoreRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\RefundRequest;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class RefundRequestController extends Controller
{

    /**
     * @param RefundStoreRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function send(RefundStoreRequest $request): JsonResponse
    {

        //Get order details
        $order_detail = OrderDetail::where('id', $request->id)->where('user_id', $request->user()->id)->latest()->first();

        //Return if order details not found
        if (!$order_detail) return $this->returnIfNotFound();

        //Get user order
        $order = Order::where('id', $order_detail->order_id)->where('user_id', $request->user()->id)->first();

        //Return if order not found
        if (!$order) return $this->returnIfNotFound();

        $last_month = Carbon::today()->subDays(30);

        //Check if order is created within 30 days
        if ($order->created_at < $last_month) {
            return response()->json([
                'success' => false,
                'message' => translate('You Cant Refund After 30 Days')
            ], 400);
        }

        //Save refund request in db
        $this->createRefundRequest($request, $order_detail);

        return response()->json([
            'success' => true,
            'message' => translate('Request Sent')
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function returnIfNotFound(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => translate('Order Not Found')
        ], 400);
    }

    /**
     * @param RefundStoreRequest $request
     * @param $order_detail
     * @return void
     */
    public function createRefundRequest(RefundStoreRequest $request, $order_detail): void
    {
        $refund = new RefundRequest;
        $refund->user_id = $request->user()->id;
        $refund->order_id = $order_detail->order_id;
        $refund->order_detail_id = $order_detail->id;
        $refund->seller_id = $order_detail->seller_id;
        $refund->seller_approval = 0;
        $refund->reason = $request->reason;
        $refund->method = $request->get('method');
        $refund->qty = $request->qty;
        $refund->details = $request->details;
        $refund->admin_approval = 0;
        $refund->admin_seen = 0;
        $refund->refund_amount = $order_detail->price + $order_detail->tax;
        $refund->refund_status = 0;
        $refund->attachment = $request->attachment;
        $refund->save();
    }
}
