<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderItemsCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return
            $this->collection->map(function ($data) {

                return [
                    'id' => $data->id,
                    'product_id' => $data->product->id,
                    'product_name' => $data->product->name,
                    'variation' => $data->variation,
                    'sku' => $data->variation,
                    'quantity' => (int)$data->quantity,
                    'price' => format_price($data->price),
                ];
            });
    }

    public function with($request): array
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
