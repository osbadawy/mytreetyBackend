<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ShopCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection->map(function ($data) {
                $logo = $data->user->avatar_original;
                if (!$logo || is_numeric($logo)) {

                    $logo = 'https://mytreety.s3.eu-central-1.amazonaws.com/products/default.png';
                }
                return [
                    'id' => $data->user->id,
                    'name' => $data->user->name,
                    'logo' => $logo
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

    protected function convertPhotos($data): array
    {
        $result = array();
        foreach ($data as $key => $item) {
            $result[] = api_asset($item);
        }
        return $result;
    }
}
