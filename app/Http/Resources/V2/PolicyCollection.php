<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PolicyCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'title' => $data->getTranslation('title'),
                    'content' => $data->getTranslation('content')
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
