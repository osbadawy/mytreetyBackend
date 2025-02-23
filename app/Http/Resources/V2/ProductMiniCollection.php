<?php

namespace App\Http\Resources\V2;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductRanking;
use Auth;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductMiniCollection extends ResourceCollection
{
    public function toArray($request): array
    {

        return [
            'data' => $this->collection->map(function ($data) {
                $name = $data->name;
                $is_fav = 0;

                if (Auth::user()) {
                    $wishlists = Auth::user()->wishlists;
                    foreach ($wishlists as $key => $wishlist) {
                        if ($wishlist->product->id == $data->id) {
                            $is_fav = 1;
                        }
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

                $choice_options_15 = [];
                $choice_options_16 = [];
                if ($data->choice_options && $data->choice_options != "[]") {
                    $choiceOptions = json_decode($data->choice_options);
                    foreach ($choiceOptions as $option) {
                        $option->name = Attribute::find($option->attribute_id)->name;
                        if (in_array($option->name, ["Chakra-Collcetion", "Size", "Größe"])) {
                            $choice_options_16 = $option->values;
                        }
                        if (in_array($option->name, ["Farbe", "Color"])) {
                            $choice_options_15 = $option->values;
                        }
                    }
                }

                $discount_percent=null;
                if($data->discount_type == 'percentage' || $data->discount_type == 'Prozent' || $data->discount_type == 'percent'){
                    $discount_percent = $data->discount;
                    $price= format_price($data->unit_price - ($data->unit_price * $data->discount) / 100);
                }
                if($data->discount_type == 'amount' || $data->discount_type == 'Menge'){
                    $discount_percent = ceil(($data->discount / $data->unit_price) * 100);
                    $price= format_price($data->unit_price - $data->discount);

                }

                return [
                    'id' => $data->id,
                    'name' => preg_replace('/(\v|\s)+/', ' ', $name),
                    'thumbnail_image' => $thumbnail_image,
                    'photos' => $imgs,
                    'main_price' => $price,
                    'before_price' => format_price($data->unit_price),
                    'sustainability_rank' => $sustainability_rank,
                    'choice_options' => $this->convertToChoiceOptions(json_decode($data->choice_options)),
                    'choice_options_15'=>$choice_options_15,
                    'choice_options_16' =>$choice_options_16,
                    'is_fav' => $is_fav,
                    'discount_percent' => $discount_percent,
                    'slug' => $data->slug,
                    'variations' => $data->stocks

                ];
            })
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
        $sideCats = [];
        $sideCategories = [];
        $cat_slug = null;
        $main_cat = null;

        $cat_slug = $request->parent_category;
        $category = Category::where('slug', $cat_slug)->first();
        if ($category) {
            $main_cat = $category->getTranslation('name');
        }

        if ($category && $category->parent_id == 0) {
            $sideCats = Category::where('parent_id', $category->id)->select('name', 'slug', 'id')->get();
        } elseif ($category && $category->parent_id != 0) {
            $sideCats = Category::where('parent_id', $category->parent_id)->select('name', 'slug', 'id')->get();
        } else {
            $sideCats = Category::where('parent_id', 0)->select('name', 'slug', 'id')->get();
        }

        if ($sideCats->count() > 0) {
            foreach ($sideCats as $key => $sidecat) {
                $childs = $sidecat->childrenCategories;
                $children = [];

                foreach ($childs as $key => $child) {
                    $children[] = [

                        'id' => $child->id,
                        'name' => $child->getTranslation('name'),
                        'products_count' => Product::where('category_id', $child->id)->count(),
                        'slug' => $child->slug,
                    ];
                }
                $sideCategories[] = [
                    'id' => $sidecat->id,
                    'name' => $sidecat->getTranslation('name'),
                    'slug' => $sidecat->slug,
                    'children' => $children
                ];
            }
        }


        return [
            'side_cats' => $sideCategories,
            'main_cat' => $main_cat,
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
