<?php

namespace App\Http\Resources\V2;

use App\Models\Charity;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PurchaseHistoryCollection extends ResourceCollection
{
    public function toArray($request): array
    {

        return [
            'data' => $this->collection->map(function ($data) {

                $dotation = $data->mytreety_donation;
                $address = json_decode($data->shipping_address);

                return [
                    'id' => $data->id,
                    'code' => $data->code,
                    'shipping_address' => "$address->address $address->city $address->postal_code $address->country",
                    'shipping_phone' => $address->phone,
                    'payment_type' => $data->payment_type,
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
                    'sub_total' => format_price($data->sub_total),



                    'shipping_cost' => format_price($data->orderDetails->sum('shipping_cost')),
                    'subtotal' => format_price($data->orderDetails->sum('price')),
                    'dotation' => format_price($dotation),
                    'date' => Carbon::createFromTimestamp($data->date)->format('d-m-Y h:i A'),
                    'tracking_carrier' => $data->tracking_carrier,
                    'tracking_code' => $data->tracking_code,
                    'products' => $this->items($data->id),

                ];
            })
        ];
    }

    public function items($id): array
    {

        $products = [];
        $items = OrderDetail::where('order_id', $id)->get();
        foreach ($items as $key => $item) {
            $thumbnail_image = $item->product->thumbnail_img;

            if (!$thumbnail_image || is_numeric($thumbnail_image)) {

                $thumbnail_image = 'https://mytreety.s3.eu-central-1.amazonaws.com/products/default.png';
            }

            $sustainabs = $item->product->sustainabilities;
            $sustainabilities = [];
            if ($sustainabs) {

                foreach ($sustainabs as $sustainability) {
                    $sustainabilities[] = [
                        'id' => $sustainability->id,
                        'name' => $sustainability->getTranslation('name'),
                        'image' => uploaded_asset($sustainability->getTranslation('image')),
                        'is_verified' => $sustainability->pivot->is_verified
                    ];
                }
            }

            $products[$key]['id'] = $item->id;
            $products[$key]['name'] = $item->product->name;
            $products[$key]['slug'] = $item->product->slug;
            $products[$key]['qty'] = $item->quantity;
            $products[$key]['delivery_status'] = $item->delivery_status;
            $products[$key]['image'] = $thumbnail_image;
            $products[$key]['product_name'] = $item->product->name;
            $products[$key]['est_shipping_days'] = $item->product->est_shipping_days;
            $products[$key]['variation'] = $item->variation;
            $products[$key]['price'] = format_price($item->price);
            $products[$key]['sustainabilities'] = $sustainabilities;
            $products[$key]['date'] = Carbon::createFromTimestamp($item->created_at)->format('d-m-Y h:i A');


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
