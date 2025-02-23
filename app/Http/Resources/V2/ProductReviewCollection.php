<?php

namespace App\Http\Resources\V2;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductRanking;
use App\Models\Review;
use App\Models\Sustainability;
use Auth;
use DB;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductReviewCollection extends ResourceCollection
{
    protected $pagination;

    public function __construct($resource, $pagination)
    {
        $this->pagination = $pagination;
        parent::__construct($resource);
    }

    public function toArray($request): array
    {

        return [
            'data' => $this->collection->map(function ($data) {
                $name = $data->name;
                $is_fav = 0;

                if (Auth::user()) {
                    $wishlists = Auth::user()->wishlists;
                    foreach ($wishlists as $key => $wishlist) {
                        if ($wishlist->product && $wishlist->product->id == $data->id) {
                            $is_fav = 1;
                        }
                    }
                }
                $sustainabilities=[];
                $product_sustainabilities = $data->sustainabilities;

                if ($product_sustainabilities) {

                    foreach ($product_sustainabilities as $sustainability) {
                        $sustainabilities[] = [
                            'name' => $sustainability->getTranslation('name'),
                            'image' => uploaded_asset($sustainability->getTranslation('image'))
                        ];
                    }
                }
                $thumbnail_image = $data->thumbnail_img;

                if (!$thumbnail_image || is_numeric($thumbnail_image)) {

                    $thumbnail_image = 'https://mytreety.s3.eu-central-1.amazonaws.com/products/default.png';
                }
                $imgs = [];
                $photos = json_decode($data->photos) ? json_decode($data->photos) : explode(",", $data->photos);
                if ($photos == [""] || $photos == ["[]"] || !is_array($photos)) {
                    $photos = [];
                }
                if ($photos != "[]" || $photos != [] || $photos != "") {
                    foreach ($photos as $key => $photo) {
                        if (is_numeric($photo)) {
                            $imgs[] = 'https://mytreety.s3.eu-central-1.amazonaws.com/products/default.png';
                        } else {
                            $imgs[] = str_replace(' ', '', $photo);
                        }
                    }
                }

                $price = home_discounted_base_price($data);
                if (!home_discounted_base_price($data) || home_discounted_base_price($data) == 0) {
                    if ($data->stocks->count() > 0) {
                        $price = format_price(convert_price($data->stocks[0]->price));
                    }
                }


                $sustainability_rank = 1;
                $sustainability_ranking = ProductRanking::where('product_id', $data->id)->first();
                if ($sustainability_ranking) {
                    $sustainability_rank = $sustainability_ranking->overall_sustainability_ranking;
                }


                $sustainabilities_ids = [];

                foreach ($data->sustainabilities as $key => $sustainability) {
                    $sustainabilities_ids[] = $sustainability->id;
                }
                $sustainabs = Sustainability::whereIn('id', $sustainabilities_ids)->distinct()->get();
                $sustainabilities = [];
                foreach ($sustainabs as $key => $sustainability) {
                    $is_veified = DB::table('product_sustainability')->where('product_id', $data->id)->where('sustainability_id', $sustainability->id)->first()->is_verified;
                    $sustainabilities[] = [
                        'id' => $sustainability->id,
                        'name' => $sustainability->getTranslation('name'),
                        'image' => uploaded_asset($sustainability->getTranslation('image')),
                        'is_verified' => $is_veified
                    ];
                }

                return [
                    'id' => $data->id,
                    'product_name' => preg_replace('/(\v|\s)+/', ' ', $name),
                    'image' => $thumbnail_image,
                    'photos' => $imgs,
                    'main_price' => $price,
                    'sustainability_rank' => $sustainability_rank,
                    'is_fav' => $is_fav,
                    'slug' => $data->slug,
                    'est_shipping_days' => $data->est_shipping_days,
                    'delivery_status'=>$data->delivery_status,
                    'quantity'=>$data->quantity,
                    'variation'=>$data->variation,

                    'sustainabilities' => $sustainabilities,

                    'date' =>$data->order_created_at

                ];
            }),
            'meta' =>$this->pagination

        ];
    }

    protected function convertToChoiceOptions($data): array
    {
        $result = array();

        foreach ($data as $key => $choice) {
            $title = Attribute::find($choice->attribute_id)->getTranslation('name');
            $options = $choice->values;
            // $item['name'] = $choice->attribute_id;
            $item['title'] = preg_replace('/(\v|\s)+/', ' ', $title);
            $item['options'] = preg_replace('/(\v|\s)+/', ' ', $options);
            $result[] = $item;
        }

        return $result;
    }

    public function with($request): array
    {

        return [
            'success' => true,
            'status' => 200,

        ];
    }

    protected function getVariations($data): array
    {
        $result = array();
        $stocks = $data->stocks;

        foreach ($stocks as $key => $value) {

            $result[] = [
                'variant' => $value->variant,
                'sku' => $value->sku,
                'color' => $value->color,
                'price' => $value->price,
                'image' => $value->image
            ];
        }
        return $result;
    }
}
