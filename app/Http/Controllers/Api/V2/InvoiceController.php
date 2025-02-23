<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Order;
use Auth;
use Illuminate\Http\JsonResponse;
use PDF;

class InvoiceController extends Controller
{


    /**
     * @param $order_code
     * @return JsonResponse
     */
    public function invoice_download($order_code): JsonResponse
    {
        $direction = 'ltr';
        $text_align = 'left';
        $not_text_align = 'right';
        $font_family = "'Roboto','sans-serif'";

        //Check if order Exist
        $order = Order::where('code', $order_code)->where('user_id', Auth::user()->id)->first();
        // dd($order_code,Order::where('code', $order_code)->first(),Auth::user()->id);
        if (!$order) {
            return response()->json(['message' => translate('Order not found')], 400);
        }

        //Generate invoice pdf for customer
        $pdf = PDF::loadView('backend.invoices.invoice', [
            'order' => $order,
            'font_family' => $font_family,
            'direction' => $direction,
            'text_align' => $text_align,
            'not_text_align' => $not_text_align
        ], [], [])->output();

        return response()->json(['data' => base64_encode($pdf), 'success' => true, 'status' => 200]);

    }


    /**
     * @param $order_code
     * @return JsonResponse
     */
    public function vendor_invoice_download($order_code): JsonResponse
    {
        $direction = 'ltr';
        $text_align = 'left';
        $not_text_align = 'right';
        $font_family = "'Roboto','sans-serif'";

        //Check if order Exist
        $order = Order::where('code', $order_code)->where('seller_id', Auth::user()->id)->first();

        //Generate invoice pdf for vendor
        $pdf = PDF::loadView('backend.invoices.vendor_invoice', [
            'order' => $order,
            'font_family' => $font_family,
            'direction' => $direction,
            'text_align' => $text_align,
            'not_text_align' => $not_text_align
        ], [], [])->output();

        return response()->json(['data' => base64_encode($pdf), 'success' => true, 'status' => 200]);

    }
}
