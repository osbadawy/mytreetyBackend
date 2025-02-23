<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CharityCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection->map(function($data) {
                if(app()->getLocale() == 'en'){
                    $operations=$data->operations;
                }
                else{

                    $operations=$data->operations_de;
                }
                $image=$data->user->avatar_original;
                if (!$image || is_numeric($image)) {

                    $image = 'https://mytreety.s3.eu-central-1.amazonaws.com/products/default.png';
                }
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'description' => $operations,
                    'image' => $image,
                    'url' => $data->user->url,

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
