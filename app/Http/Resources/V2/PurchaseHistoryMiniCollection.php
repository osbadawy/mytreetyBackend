<?php

namespace App\Http\Resources\V2;

use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PurchaseHistoryMiniCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'code' => $data->code,
                    'user_id' => intval($data->user_id),
                    'payment_type' => ucwords(str_replace('_', ' ', $data->payment_type)),
                    'payment_status' => $data->payment_status,
                    'payment_status_string' => ucwords(str_replace('_', ' ', $data->payment_status)),
                    'delivery_status' => $data->delivery_status,
                    'delivery_status_string' => $data->delivery_status == 'pending' ? "Order Placed" : ucwords(str_replace('_', ' ', $data->delivery_status)),
                    'grand_total' => format_price($data->grand_total),
                    'coupon_discount' => format_price($data->coupon_discount),
                    'referral_code' => $data->referral_code,
                    'referral_discount' => format_price($data->referral_discount),
                    'coupon_code' => $data->coupon_discount,
                    'coupon_discount' => format_price($data->coupon_discount),
                    'points_discount' => format_price($data->points_discount),
                    'points_applied' => $data->points_applied,
                    'coupon_discount' => format_price($data->coupon_discount),
                    'subtotal' => format_price($data->sub_total),
                    'date' => Carbon::createFromTimestamp($data->date)->format('d-m-Y h:i A'),
                    'products' => $this->items($data->id)
                ];
            })
        ];
    }

    public function items($id): array
    {

        $products = [];
        $items = OrderDetail::where('order_id', $id)->get();
        foreach ($items as $key => $item) {
            $thumbnail_image = $item->product ? $item->product->thumbnail_img : '';

            if (!$thumbnail_image || is_numeric($thumbnail_image)) {

                $thumbnail_image = 'https://mytreety.s3.eu-central-1.amazonaws.com/products/default.png';
            }

            $products[$key]['product_name'] = $item->product ? $item->product->name : '';
            $products[$key]['product_image'] = $thumbnail_image;
        }

        return $products;
    }

    public function with($request): array
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
