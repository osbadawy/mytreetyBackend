<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SustainabilityCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->getTranslation('name'),
                    'image' => uploaded_asset($data->getTranslation('image')),
                    'price' => format_price($data->price),
                    'ui_sepertion' => $data->ui_sepertion,
                    'description' => $data->getTranslation('description'),
                    'required_documents' => $data->required_documents,

                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
