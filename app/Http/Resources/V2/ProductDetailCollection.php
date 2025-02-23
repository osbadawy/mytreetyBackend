<?php

namespace App\Http\Resources\V2;

use App\Models\Attribute;
use App\Models\ExternalReview;
use App\Models\ProductDescriptionTranslations;
use App\Models\Review;
use App\Models\Sustainability;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JoggApp\GoogleTranslate\GoogleTranslateFacade;

class ProductDetailCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection->map(function ($data) {

                //fix product translation
                foreach ($data->product_descriptions as $key => $product_description) {
                    $translations_count = $product_description->product_description_translations()->count();
                    if ($translations_count != 2 && $product_description->title && $product_description->sub_title) {
                        $check_lang = GoogleTranslateFacade::detectLanguage($product_description->sub_title);

                        if ($check_lang == 'de') {
                            $product_translation_de = ProductDescriptionTranslations::firstOrNew(['lang' => 'de', 'product_description_id' => $product_description->id]);
                            $product_translation_de->title = $product_description->title;
                            $product_translation_de->sub_title = $product_description->sub_title;
                            $product_translation_de->save();

                            $product_translation_en = ProductDescriptionTranslations::firstOrNew(['lang' => 'en', 'product_description_id' => $product_description->id]);
                            $product_translation_en->title = GoogleTranslateFacade::justTranslate($product_description->title, 'en');
                            $product_translation_en->sub_title = GoogleTranslateFacade::justTranslate($product_description->sub_title, 'en');;
                            $product_translation_en->save();
                        } else {
                            $product_translation_en = ProductDescriptionTranslations::firstOrNew(['lang' => 'en', 'product_description_id' => $product_description->id]);
                            $product_translation_en->title = $product_description->title;
                            $product_translation_en->sub_title = $product_description->sub_title;
                            $product_translation_en->save();

                            $product_translation_de = ProductDescriptionTranslations::firstOrNew(['lang' => 'de', 'product_description_id' => $product_description->id]);
                            $product_translation_de->title = GoogleTranslateFacade::justTranslate($product_description->title, 'de');
                            $product_translation_de->sub_title = GoogleTranslateFacade::justTranslate($product_description->sub_title, 'de');;
                            $product_translation_de->save();
                        }
                    }
                    $product_description->title = $product_description->getTranslation('title');
                    $product_description->sub_title = $product_description->getTranslation('sub_title');
                }

                $precision = 2;
                $calculable_price = home_discounted_base_price($data, false);
                $calculable_price = number_format($calculable_price, $precision, '.', '');
                $calculable_price = floatval($calculable_price);

                $ranking = DB::table('product_rankings')
                    ->where('product_id', $data->id)
                    ->where('is_calculated', 1)
                    ->select('sourcing_level', 'manufacturing_level', 'packaging_level', 'shipping_level', 'use_level', 'end_of_life_level', 'overall_sustainability_ranking')
                    ->first();


                if ($data->stocks && $data->stocks->count() > 0) {

                    foreach ($data->stocks as $stockItem) {
                        if ($stockItem->image != null && $stockItem->image != "") {
                            $item = array();
                            $item['variant'] = $stockItem->variant;
                            $item['path'] = $stockItem->image;
                            $item['color'] = $stockItem->color;
                            $photos[] = $item;
                        }
                    }
                }

                $parent_id = [
                    'id' => null,
                    'name' => null,
                ];
                if ($data->category) {
                    if ($data->category->parentCategory) {
                        $subparent_id = $data->category->parentCategory;

                        $parent_id = [
                            'id' => $subparent_id->parentCategory->id,
                            'name' => $subparent_id->parentCategory->getTranslation('name'),
                        ];
                    }
                }

                $choice_options_15 = [];
                $choice_options_16 = [];
                if ($data->choice_options && $data->choice_options != "[]") {
                    $choiceOptions = json_decode($data->choice_options);
                    foreach ($choiceOptions as $option) {
                        $option->name=Attribute::find($option->attribute_id)->name;
                        if (in_array($option->name, ["Chakra-Collcetion", "Size", "Größe"])) {
                            $choice_options_16 = $option->values;
                        }
                        if (in_array($option->name, ["Farbe", "Color"])) {
                            $choice_options_15 = $option->values;
                        }
                    }
                }

                $sustainabilities_ids = [];

                foreach ($data->sustainabilities as $key => $sustainability) {
                    $sustainabilities_ids[] = $sustainability->id;
                }
                $thumbnail_image = $data->thumbnail_img;

                if (!$thumbnail_image || is_numeric($thumbnail_image)) {

                    $thumbnail_image = 'https://mytreety.s3.eu-central-1.amazonaws.com/products/default.png';
                }
                $imgs = [];
                $photos = json_decode($data->photos) ? json_decode($data->photos) : explode(",", $data->photos);
                if ($photos == [""] || $photos == ["[]"]) {
                    $photos = [];
                }
                if (!is_array($photos)) {

                    $photos = explode(',', $photos);
                }
                if ($photos != null && $photos != "[]" && $photos != [] && $photos != "") {
                    foreach ($photos as $key => $photo) {
                        if (is_numeric($photo)) {
                            $imgs[] = 'https://mytreety.s3.eu-central-1.amazonaws.com/products/default.png';
                        } else {
                            $imgs[] = str_replace(' ', '', $photo);
                        }
                    }
                } else {
                    $imgs[] = 'https://mytreety.s3.eu-central-1.amazonaws.com/products/default.png';
                }
                $qty = 0;
                // $imgs[] = $thumbnail_image;

                if ($data->stocks->count() > 0) {

                    $qty = (int)$data->stocks->first()->qty;
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

                $is_fav = 1;

                if (Auth::user()) {
                    $wishlists = Auth::user()->wishlists;
                    foreach ($wishlists as $key => $wishlist) {
                        if ($wishlist->product->id == $data->id) {
                            $is_fav = 1;
                        }
                    }
                }

                $variations = $data->stocks;
                if ($variations->count() > 0) {
                    $variant = $variations[0];
                    if ($variant != null && $variant != "") {
                        $price = convert_price($variant->price);
                    }
                } else {
                    $price = home_discounted_price($data, false);
                }


                $simple_price = null;
                $simple_quantity = null;
                $simple_sku = null;
                if ($data->product_type == 1) {
                    $simple_price = $data->stocks[0]->price;
                    $simple_quantity = $data->stocks[0]->qty;
                    $simple_sku = $data->stocks[0]->sku;
                }

                $currency_symbol = currency_symbol();


                $reviews = [];
                $rating_counts = [
                    '5' => 0,
                    '4' => 0,
                    '3' => 0,
                    '2' => 0,
                    '1' => 0,
                    '0' => 0
                ];

                $mytreety_reviews = [];
                $mytreety_original_reviews = Review::where('product_id', $data->id)->where('status', 1)->get();
                if ($mytreety_original_reviews->count() > 0) {
                    foreach ($mytreety_original_reviews as $key => $mytreety_original_review) {
                        $rating_counts[(string)$mytreety_original_review->rating]++;
                        $mytreety_reviews[] = [
                            'id' => $mytreety_original_review->id,
                            'name' => $mytreety_original_review->user->name,
                            'rating' => $mytreety_original_review->rating,
                            'title' => null,
                            'description' => $mytreety_original_review->comment,
                            'date' => Carbon::createFromFormat('Y-m-d H:i:s', $mytreety_original_review->created_at)->format('d F Y'),
                            'is_verified' => true,
                            'product_attributes' => null,
                        ];
                    }
                }
                $reviews[] = [
                    // 'site_name' => translate('Mytreety Reviews'),
                    'source' => 'mytreety',
                    'total_count' => count($mytreety_reviews),
                    'reviews_counts' => $rating_counts,
                    'overall_rating' => $data->rating,
                    'reviews' => $mytreety_reviews,
                ];
                $external_reviews = ExternalReview::where('product_id', $data->id)->where('status', 'active')->get();

                foreach ($external_reviews as $key => $external_review) {
                    // $site_name=ucfirst($external_review->source) . " Reviews";
                    $reviews[] = [
                        'source' => 'amazon',
                        'total_count' => $external_review->total_count,
                        'overall_rating' => $external_review->overall_rating,
                        'reviews_counts' => json_decode($external_review->reviews_counts),
                        'reviews' => json_decode($external_review->reviews),
                    ];
                }

                return [
                    'id' => (int)$data->id,
                    'name' => $data->name,
                    'product_type' => (int)$data->product_type,
                    'collection_name' => $data->collection ? $data->collection->name : translate("No Collection"),
                    'parent_category' => $parent_id,
                    'category_id' => $data->category_id,
                    'sustainability_rank' => $data->sustainability_rank,
                    'seller_name' => $data->added_by == 'admin' ? translate('In House Product') : $data->user->name,
                    'thumbnail_img' => $thumbnail_image,
                    'photos' => $imgs,
                    'tags' => explode(',', $data->tags),
                    'choice_options' => $this->convertToChoiceOptions(json_decode($data->choice_options)),
                    'variations' => $data->stocks,
                    'has_discount' => home_base_price($data, false) != home_discounted_base_price($data, false),
                    'main_price' => "$currency_symbol $price",
                    'calculable_price' => $calculable_price,
                    'currency_symbol' => currency_symbol(),
                    'current_stock' => $qty,
                    'rating' => (float)$data->rating,
                    'rating_count' => (int)Review::where(['product_id' => $data->id])->count(),
                    'reviews' => $reviews,
                    'choice_options_15' => $choice_options_15,
                    'choice_options_16' => $choice_options_16,
                    'descriptions' => $data->product_descriptions,
                    'est_shipping_days' => $data->est_shipping_days,
                    'discount' => $data->discount,
                    'discount_type' => $data->discount_type,
                    'discount_start_date' => $data->discount_start_date,
                    'discount_end_date' => $data->discount_end_date,
                    'sustainabilities_ids' => $sustainabilities_ids,
                    'sustainabilities' => $sustainabilities,
                    'is_fav' => $is_fav,
                    'ranking' => $ranking,
                    'simple_price' => $simple_price,
                    'simple_quantity' => $simple_quantity,
                    'simple_sku' => $simple_sku,

                ];
            })
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
