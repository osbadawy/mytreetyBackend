<?php

namespace App\Http\Resources\V2;

use App\Models\Product;
use App\Utility\CategoryUtility;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection->map(function ($data) {
                $childrens = [];
                foreach ($data->childrenCategories as $key => $child) {
                    $childrens[] = [
                        'id' => $child->id,
                        'name' => $child->getTranslation('name'),
                        'products_count'=>Product::where('category_id',$child->id)->count(),
                        'slug' => $child->slug
                    ];
                }
                return [
                    'id' => $data->id,
                    'name' => $data->getTranslation('name'),
                    'children' => $childrens,
                    'slug' => $data->slug,
                    'number_of_children' => CategoryUtility::get_immediate_children_count($data->id),
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
