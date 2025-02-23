<?php

namespace App\Http\Resources\V2;

use App\Models\ProductRanking;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public function toArray($request): array
    {

        return [
            'data' => $this->collection->map(function ($data) {
                $pics = explode(',', $data->photos);
                $photos = [];
                $category = null;
                $base_price = 0;

                $qty = 0;
                foreach ($data->stocks as $key => $stock) {
                    $qty += $stock->qty;
                }

                foreach ($pics as $key => $pic) {
                    $photos[] = $pic;
                }
                $thumbnail_image = $data->thumbnail_img;

                if (!$thumbnail_image || is_numeric($thumbnail_image)) {
                    $thumbnail_image = 'https://mytreety.s3.eu-central-1.amazonaws.com/products/default.png';
                }

                $parent_id = $category;

                if ($data->category) {
                    $category = $data->category->getTranslation('name');

                    if ($data->category && $data->category->parentCategory) {
                        $subparent_id = $data->category->parentCategory;
                        if ($subparent_id->parentCategory) {
                            $subcat = $subparent_id->getTranslation('name');
                            $category = "$subcat ($category)";
                            $parent_id = $subparent_id->parentCategory->getTranslation('name');
                        }
                    }
                }
                $variations = $data->stocks;
                if ($variations->count() > 0) {
                    $variant = $variations[0];
                    if ($variant != null && $variant != "") {
                        $base_price = convert_price($variant->price);
                    }
                } else {
                    $base_price = home_discounted_price($data, false);
                }
                $sustainability_rank = 1;
                $sustainability_ranking = ProductRanking::where('product_id', $data->id)->first();
                if ($sustainability_ranking) {
                    $sustainability_rank = $sustainability_ranking->overall_sustainability_ranking;
                }

                $currency_symbol=currency_symbol();

                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'category' => $parent_id,
                    'sub_category' => $category,
                    'collection_name' => $data->collection ? $data->collection->name : translate("No Collection"),
                    'quantity' => $qty,
                    'photos' => $photos,
                    'thumbnail_image' => $thumbnail_image,
                    'base_price' => "$currency_symbol $base_price",
                    'discount' => (double)$data->discount,
                    'discount_type' => $data->discount_type,
                    'rating' => (double)$data->rating,
                    'sales' => (integer)$data->num_of_sale,
                    'active' => (integer)$data->published,
                    'slug' => $data->slug,
                    'sustainability_rank' => $sustainability_rank,
                    'created_at' => $data->created_at,
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
