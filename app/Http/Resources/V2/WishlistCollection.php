<?php

namespace App\Http\Resources\V2;

use App\Models\Attribute as Attribute;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WishlistCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection->map(function($data) {
                $thumbnail_image=$data->product->thumbnail_img;

                if(!$thumbnail_image || is_numeric($thumbnail_image)){

                    $thumbnail_image = 'https://mytreety.s3.eu-central-1.amazonaws.com/products/default.png';

                }
                $choice_options_15 = [];
                $choice_options_16 = [];
                if ($data->product->choice_options && $data->product->choice_options != "[]") {

                    $choice_options_15 = json_decode($data->product->choice_options)[0]->values;
                    if (count(json_decode($data->product->choice_options)) > 1) {

                        $choice_options_16 = json_decode($data->product->choice_options)[1]->values;
                    }
                }
                return [
                    'id' => (integer) $data->id,
                    'product' => [
                        'id' => $data->product->id,
                        'name' => $data->product->name,
                        'slug' => $data->product->slug,
                        'variations' => $data->product->stocks,
                        'choice_options' => $this->convertToChoiceOptions(json_decode($data->product->choice_options)),
                        'choice_options_15' => $choice_options_15,
                        'choice_options_16' => $choice_options_16,
                        'thumbnail_image' => $thumbnail_image,
                        'base_price' => format_price(home_base_price($data->product, false)) ,
                        'rating' => (double) $data->product->rating,
                    ]
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

    protected function convertToChoiceOptions($data): array
    {
        $result = array();
        if ($data) {
            foreach ($data as $key => $choice) {
                $item['name'] = $choice->attribute_id;
                $item['title'] = Attribute::find($choice->attribute_id)->getTranslation('name');
                $item['options'] = $choice->values;
                $result[] = $item;
            }
        }
        return $result;
    }
}
