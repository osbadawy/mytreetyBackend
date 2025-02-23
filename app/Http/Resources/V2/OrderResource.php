<?php

namespace App\Http\Resources\V2;

use App\Models\OrderDetail;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     */
    public function toArray($request): array
    {

        $addressObj = json_decode($this->shipping_address);
        $address = "$addressObj->address, $addressObj->city, $addressObj->postal_code";

        $items = OrderDetail::where('order_id', $this->id)->get();
        $num_products = $items->count();
        $sub_total = $items->sum('price');
        $shipping_total = $items->sum('shipping_cost');

        return [
            'id' => $this->id,
            'code' => $this->code,
            'shipping_address' => $address,
            'order_date' => date('d-m-Y H:i A', $this->date),
            'payment_status' => $this->payment_type,
            'delivery_status' => $this->delivery_status,
            'payment_method' => $this->payment_type,
            'grand_total' => format_price($this->grand_total),
            'sub_total' => format_price($sub_total),
            'shipping_total' => format_price($shipping_total),
            'num_products' => $num_products,
            'download_url' => route('api.invoice.download', $this->code),
            'products' => new OrderItemsCollection($this->orderDetails)

        ];
    }


    public function with($request): array
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
