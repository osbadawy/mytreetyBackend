<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection->map(function($data) {
                $thumbnail_image = $data->product ? $data->product->thumbnail_img : '';

                if (!$thumbnail_image || is_numeric($thumbnail_image)) {

                    $thumbnail_image = 'https://mytreety.s3.eu-central-1.amazonaws.com/products/default.png';
                }
                return [
                    'id'=> $data->id,
                    'user_id'=> $data->user->id,
                    'user_name'=> $data->user->name,
                    'avatar'=> api_asset($data->user->avatar_original),
                    'rating' => floatval(number_format($data->rating,1,'.','')),
                    'comment' => $data->comment,
                    'product_name'=> $data->product ? $data->product->name :"",
                    'product_image'=> $thumbnail_image ? $thumbnail_image :"",
                    'created_at' => $data->updated_at->diffForHumans()
                ];
            })
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
