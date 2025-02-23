<?php

namespace App\Http\Resources\V2;

use App\Models\OrderDetail;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderMiniResource extends JsonResource
{

    /**
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        $payment_status = null;
        $addressObj = json_decode($this->shipping_address);
        $address = "";
        if ($addressObj) {
            $address = "$addressObj->address, $addressObj->city, $addressObj->postal_code";
        }


        $items = OrderDetail::where('order_id', $this->id)->get();
        $num_products = $items->count();
        $sub_total = $items->sum('price');
        $shipping_total = $items->sum('shipping_cost');

        if ($this->payment_status == 0) {
            $payment_status = 'Pending';
        }
        if ($this->payment_status == 1) {
            $payment_status = 'Paid';
        }

        return [
            'id' => $this->id,
            'code' => $this->code,
            'shipping_address' => $address,
            'order_date' => date('d-m-Y H:i A', $this->date),
            'payment_status' => $payment_status,
            'delivery_status' => $this->delivery_status,
            'payment_method' => $this->payment_type,
            'grand_total' => "€$this->grand_total",
            'sub_total' => "€$sub_total",
            'shipping_total' => "€$shipping_total",
            'num_products' => $num_products,
            'tracking_carrier' => $this->tracking_carrier,
            'tracking_code' => $this->tracking_code,
            'download_url' => route('api.invoice.download', $this->code)
        ];
    }
}
