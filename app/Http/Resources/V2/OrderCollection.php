<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    public function toArray($request): array
    {

        return [
            'data' => OrderResource::collection($this->collection),

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
