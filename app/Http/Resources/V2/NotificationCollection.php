<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        $unreadcount = 0;
        foreach ($this->collection as $key => $value) {
            # code...
            if (!$value->read_at) {
                $unreadcount++;
            }
        }

        return [
            'data' => $this->collection->map(function ($data) {
                $read = 0;
                if ($data->read_at) {
                    $read = 1;
                }
                return [
                    'id' => $data->id,
                    'order_code' => $data->data['order_code'],
                    'message' => translate('Your Order: ') . $data->data['order_code'] . translate(' has been ' . ucfirst(str_replace('_', ' ', $data->data['status']))),
                    'read' => $read,
                    'created_at' => $data->created_at
                ];
            }),
            'unread_count' => $unreadcount
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
