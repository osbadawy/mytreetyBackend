<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorProductStoreRequest;
use App\Models\Cart;
use App\Models\ExternalReview;
use App\Models\Product;
use App\Models\ProductDescription;
use App\Models\ProductStock;
use App\Models\ProductTranslation;
use App\Models\Sustainability;
use App\Traits\AmazonScrapperTrait;
use App\Traits\SustainabilityRankingTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use JoggApp\GoogleTranslate\GoogleTranslateFacade;
use Str;

class VendorProductController extends Controller
{
    use SustainabilityRankingTrait;
    use AmazonScrapperTrait;


    public function store(VendorProductStoreRequest $request): \Illuminate\Http\JsonResponse
    {

        //Validate simple or variant product
        if ($request->simple_price == null && $request->simple_quantity == null && $request->simple_sku == null) {
            if (count($request->variations) == 0) {
                return response()->json(['message' => translate('Please confirm each product variant in order to save it'), 'success' => false, 'status' => 400], 400);
            }
        }



        $choice_nos = ['15', '16'];
        $choice_options = $this->getChoiceOptions($choice_nos, $request);

        $product = new Product;
        $product->name = $request->product_name;
        $product->product_type = $request->product_type;
        $product->added_by = $request->user()->name;
        $product->user_id = $request->user()->id;
        $product->category_id = $request->category_id;
        $product->refundable = 0;
        $product->photos = json_encode($request->photos);
        $product->thumbnail_img = $request->thumbnail_img;
        $product->unit = 'pc';
        $product->min_qty = '1';
        $product->low_stock_quantity = '1';
        $product->stock_visibility_state = '1';
        $product->tags = implode(',', $this->getTags($request->tags));
        $product->unit_price = 0;
        $product->source = 'manual';
        $product->discount = $request->discount;
        $product->discount_type = $request->discount_type;
        $product->discount_start_date = $request->discount_start_date;
        $product->discount_end_date = $request->discount_end_date;
        $product->shipping_type = 'flat_rate';
        $product->est_shipping_days = $request->est_shipping_days;
        $product->shipping_cost = $request->shipping_cost;
        $product->slug = $this->getSlug($request->product_name);
        $product->attributes = $choice_options ? json_encode($choice_nos) : json_encode([]);
        $product->choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);
        $product->published = 0;
        $product->cash_on_delivery = 0;
        if ($request->variations) {
            $product->unit_price = $request->variations[0]['price'];
        } else {
            $product->unit_price = $request->simple_price;
        }
        $product->save();

        //Attach sustainabilities
        if ($request->has('sustainabilities')) $product->sustainabilities()->attach($request->sustainabilities);

        //combinations start

        //Generates the combinations of customer choice options
        $variations = $request->variations;

        if (!empty($variations)) {
            foreach ($variations as $variation) {
                $this->createVariation($product->id, $variation['variant'], $variation['sku'], $variation['image'], $variation['price'], $variation['qty'], $variation['color']);
            }
        } else {
            $this->createVariation($product->id, '', $request->simple_sku, null, $request->simple_price, $request->simple_quantity, null);
        }

        //combinations end

        //product description
        if ($request->product_descriptions) {

            foreach ($request->product_descriptions as $key => $product_description) {

                $title = $product_description['title'];
                $subtitle = $product_description['subtitle'];

                if ($title == "") {
                    $title = 'Details';
                }

                if ($title && $subtitle) {

                    $product_desc = new ProductDescription;
                    $product_desc->title = $title;
                    $product_desc->sub_title = $subtitle;
                    $product_desc->product_id = $product->id;
                    $product_desc->save();
                }
            }
        }

        // $lang=GoogleTranslateFacade::detectLanguage($request->name)['language_code'];
        // $lang = $request->lang;
        $lang = 'en';

        if ($lang != 'en' || $lang != 'de') {
            $lang = 'en';
        }

        // Product Translations
        //$product_translation = ProductTranslation::firstOrNew(['lang' =>$lang, 'product_id' => $product->id]);
        //$product_translation->name = $request->name;
        // $product_translation->unit = $request->unit;
        // $product_translation->description = $request->description;
        // $product_translation->save();


        if ($lang == 'en') {
            // Product Translations
            $product_translation = ProductTranslation::firstOrNew(['lang' => 'en', 'product_id' => $product->id]);
            $product_translation->name = $request->name;
            $product_translation->description = $request->description;
            $product_translation->save();

            $product_translation_de = ProductTranslation::firstOrNew(['lang' => 'de', 'product_id' => $product->id]);
            //   $product_translation_de->name = GoogleTranslateFacade::justTranslate($request->name, 'de');
            if ($request->description) {
                $product_translation_de->description = GoogleTranslateFacade::justTranslate($request->description, 'de');
            }
            $product_translation_de->save();
        } elseif ($lang == 'de') {
            // Product Translations
            $product_translation = ProductTranslation::firstOrNew(['lang' => 'de', 'product_id' => $product->id]);
            $product_translation->name = $request->name;
            $product_translation->description = $request->description;
            $product_translation->save();

            $product_translation_en = ProductTranslation::firstOrNew(['lang' => 'en', 'product_id' => $product->id]);
            //   $product_translation_en->name = GoogleTranslateFacade::justTranslate($request->name, 'en');
            if ($request->description) {
                $product_translation_en->description = GoogleTranslateFacade::justTranslate($request->description, 'en');
            }
            $product_translation_en->save();
        }

        // Attach collection to product
        $collection_id = 0;

        if ($request->collection_id) {
            $collection_id = $request->collection_id;
        }

        if (!$collection_id && $request->rankingDetails) {

            $collection_id = $this->createCollection($request->rankingDetails, $request->name, $request->user()->id);
        }

        // Attach collection to product

        $this->attachCollection($product->id, $collection_id);


        return response()->json(['message' => translate('Product has been uploaded successfully'), 'success' => true, 'status' => 200], 200);
    }

    /**
     * @param $tgs
     * @return array
     */
    public function getTags($tgs): array
    {
        $tags = array();
        if ($tgs != null) {
            foreach ($tgs as $key => $tag) {
                $tags[] = $tag;
            }
        }
        return $tags;
    }

    /**
     * @param $product_name
     * @return string
     */
    public function getSlug($product_name): string
    {
        $slug = Str::slug($product_name, '-');
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . ($same_slug_count + 1) : '';
        $slug .= $slug_suffix;
        return $slug;
    }


    /**
     *
     * Update Product.
     *
     * @group Vendor Dashboard
     *
     * @authenticated
     *
     *
     * @bodyParam id string required product id
     * @bodyParam name string optional
     * @bodyParam category_id string optional
     * @bodyParam thumbnail_img string optional
     * @bodyParam est_shipping_days string optional
     * @bodyParam manufactured string optional
     * @bodyParam distributed string optional
     * @bodyParam sustainabilities array optional
     * @bodyParam photos array optional
     * @bodyParam tags array optional
     * @bodyParam discount_start_date string optional
     * @bodyParam discount_end_date string optional
     * @bodyParam discount_type string optional values('percent','amount')
     * @bodyParam discount string optional
     * @bodyParam product_descriptions array optional ["title" => "test","subtitle" => "test2"]
     * @bodyParam choice_options_15 array optional values selected for colors (Red)
     * @bodyParam choice_options_16 array optional values selected for sizes   (XS)
     *
     *
     */
    public function update(Request $request)
    {
        $product = Product::where('id', $request->id)->where('user_id', Auth::user()->id)->first();

        if (!$product) {

            return response()->json(['message' => translate('Product not found'), 'success' => true, 'status' => 400], 400);
        }

        //Validate simple product
        if ($request->product_type == 1 && $request->simple_price == null) {
            return response()->json(['message' => translate('Please set product price in order to save it'), 'success' => false, 'status' => 400], 400);
        }
        //Validate variation product
        elseif ($request->product_type == 0) {
            if (count($request->variations) == 0) {
                return response()->json(['message' => translate('Please confirm each product variant in order to save it'), 'success' => false, 'status' => 400], 400);
            }
        }


        $product->name = $request->name;
        $product->added_by = Auth::user()->name;

        $product->user_id = Auth::user()->id;

        $product->category_id = $request->category_id;
        $product->refundable = 0;
        $product->photos = json_encode($request->photos);
        $product->thumbnail_img = $request->thumbnail_img;
        $product->unit = 'pc';
        $product->min_qty = '1';
        $product->low_stock_quantity = '1';
        $product->stock_visibility_state = '1';
        $product->product_type = $request->product_type;

        $tags = $this->getTags($request->tags);
        $product->tags = implode(',', $tags);

        if ($request->variations) {
            $product->unit_price = $request->variations[0]['price'];
        } else {
            $product->unit_price = $request->simple_price;
        }
        $product->discount = $request->discount;
        $product->discount_type = $request->discount_type;

        if ($request->discount_start_date && $request->discount_end_date != null) {
            $product->discount_start_date = $request->discount_start_date;
            $product->discount_end_date = $request->discount_end_date;
        }

        $product->shipping_type = 'flat_rate';
        $product->est_shipping_days = $request->est_shipping_days;


        $product->manufactured = $request->manufactured;
        $product->distributed = $request->distributed;

        $product->shipping_cost = $request->shipping_cost;

        $choice_options = array();
        $choice_nos = ['15', '16'];

        // if ($request->has('choice_no')) {
        if ($choice_nos) {

            foreach ($choice_nos as $key => $no) {
                $str = 'choice_options_' . $no;

                $item['attribute_id'] = $no;

                $data = array();
                foreach ($request[$str] as $key => $eachValue) {
                    array_push($data, $eachValue);
                }

                $item['values'] = $data;
                array_push($choice_options, $item);
            }
        }

        if (!empty($choice_nos)) {
            $product->attributes = json_encode($choice_nos);
        } else {
            $product->attributes = json_encode(array());
        }

        $product->choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);

        if ($product->collection_id) {
            $product->published = 1;
        } else {
            $product->published = 0;
        }

        if ($request->has('cash_on_delivery')) {
            $product->cash_on_delivery = 1;
        }
        if ($request->has('featured')) {
            $product->featured = 1;
        }
        if ($request->has('todays_deal')) {
            $product->todays_deal = 1;
        }
        $product->cash_on_delivery = 0;
        if ($request->cash_on_delivery) {
            $product->cash_on_delivery = 1;
        }

        $product->save();
        // sustainabilities
        if ($request->has('sustainabilities')) {

            // $sus_exists = $product->sustainabilities();
            // foreach ($sus_exists as $key => $sus_ex) {
            //     $sus_ex->delete();
            // }
            $product->sustainabilities()->sync($request->sustainabilities);
        }

        $product_stocks = ProductStock::where('product_id', $product->id)->get();

        if (count($product_stocks) > 0) {
            foreach ($product_stocks as $key => $stock) {
                $stock->delete();
            }
        }

        //combinations end
        if ($request->variations) {

            foreach ($request->variations as $key => $variation) {
                if ($variation['qty'] == null || $variation['price'] == null) {
                    return response()->json(['message' => translate('variation qty and price are required'), 'success' => true, 'status' => 400], 400);
                }

                $product_stock = new ProductStock;

                $product_stock->product_id = $product->id;
                $product_stock->variant = $variation['variant'];
                $product_stock->price = $variation['price'];
                $product_stock->sku = $variation['sku'];
                $product_stock->qty = $variation['qty'];
                $product_stock->color = $variation['color'];

                if ($variation['image']) {

                    $product_stock->image = $variation['image'];
                }
                $product_stock->save();
            }
        } else {

            $product_stock = new ProductStock;
            $product_stock->product_id = $product->id;
            $product_stock->variant = '';
            $product_stock->price = $request->simple_price;
            $product_stock->sku = $request->simple_sku;
            $product_stock->qty = $request->simple_quantity;
            $product_stock->save();
        }

        $product->save();

        //product description
        if ($request->descriptions) {

            $exists = ProductDescription::where('product_id', $product->id)->get();
            foreach ($exists as $key => $ex) {
                $ex->delete();
            }

            foreach ($request->descriptions as $key => $product_description) {


                $title = $product_description['title'];
                $subtitle = $product_description['sub_title'];

                $product_desc = ProductDescription::where('title', $title)->where('product_id', $product->id)->first();
                if (!$product_desc) {

                    $product_desc = new ProductDescription;
                }
                $product_desc->title = $title;
                $product_desc->sub_title = $subtitle;
                $product_desc->product_id = $product->id;
                $product_desc->save();
            }
        }

        $product_id = $product->id;
        $data = [];
        if($request->amazon_link){
            $source = 'amazon';
            $product_link = $request->amazon_link;
        }

        // if ($source == 'amazon') {
        //     $data = $this->getAmazonReviews($product_link, $product_id);
        // }

        if (count($data) > 0) {
            $asin = $data['asin'];
            $original_url = $data['original_url'];
            $external_url = $data['external_url'];
            $rating_counts = $data['rating_counts'];
            $overall_rating = $data['overall_rating'];
            $reviews_array = $data['reviews_array'];

            $external_review = ExternalReview::firstOrNew(['source' => $source, 'product_id' => $product_id]);
            $external_review->user_id = Auth::user()->id;
            $external_review->product_id = $product_id;
            $external_review->external_product_id = $asin;
            $external_review->original_product_url = $original_url;
            $external_review->external_product_url = $external_url;
            $external_review->source = $source;
            $external_review->reviews_counts = json_encode($rating_counts);
            $external_review->overall_rating = $overall_rating / count($reviews_array);
            $external_review->reviews = json_encode($reviews_array);
            $external_review->status = 'active';
            $external_review->total_count = count($reviews_array);
            $external_review->save();
        }

        //send to frontend clear cache signal for this product
        $website_url = env('FRONTEND_URL');
        $revalidate_secret = env('REVALIDATE_SECRET');
        $refresh_frontend_url = "$website_url/api/revalidate?secret=$revalidate_secret&slug=$product->slug";
        try {
            $response = Http::get($refresh_frontend_url);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()->json(['message' => translate('Product has been updated successfully'), 'success' => true, 'status' => 200], 200);
    }

    /**
     *
     * Bulk Edit products.
     *
     * @group Vendor Dashboard
     *
     * @authenticated
     *
     *
     * @bodyParam products array required array of products ids
     * @bodyParam category_id string optional
     * @bodyParam shipping_cost string optional
     * @bodyParam est_shipping_days string optional
     * @bodyParam manufactured string optional
     * @bodyParam distributed string optional
     * @bodyParam sustainabilities array optional array of sustainabilities ids
     * @bodyParam discount_start_date string optional
     * @bodyParam discount_end_date string optional
     * @bodyParam discount_type string optional values('percent','amount')
     * @bodyParam discount string optional
     *
     *
     */

    public function bulkEdit(Request $request)
    {
        if (!$request->products) {

            return response()->json(['message' => translate('Please select some products'), 'success' => true, 'status' => 400], 400);
        }

        $productsIDs = $request->products;

        foreach ($productsIDs as $key => $productsID) {

            $product = Product::where('user_id', $request->user()->id)->where('id', $productsID)->first();

            if (!$product) {

                return response()->json(['message' => translate('Please recheck the products selected'), 'success' => true, 'status' => 400], 400);
            }

            if ($request->category_id) {
                $product->category_id = $request->category_id;
            }
            if ($request->shipping_cost) {
                $product->shipping_cost = $request->shipping_cost;
            }
            if ($request->est_shipping_days) {
                $product->est_shipping_days = $request->est_shipping_days;
            }
            if ($request->manufactured) {
                $product->manufactured = $request->manufactured;
            }
            if ($request->distributed) {
                $product->distributed = $request->distributed;
            }
            if (is_numeric($request->publish)) {
                $product->published = $request->publish;
            }
            if ($request->has('sustainabilities')) {
                $product->sustainabilities()->sync($request->sustainabilities);
            }

            $tags = array();

            if ($request->tags != null) {
                foreach ($request->tags as $key => $tag) {
                    array_push($tags, $tag);
                }
            }

            $product->tags = implode(',', $tags);

            $product->discount = $request->discount;
            $product->discount_type = $request->discount_type;

            if ($request->discount_start_date && $request->discount_end_date != null) {
                $product->discount_start_date = $request->discount_start_date;
                $product->discount_end_date = $request->discount_end_date;
            }

            $product->approved = 1;

            $product->update();
        }

        return response()->json(['message' => translate('Products has been updated successfully'), 'success' => true, 'status' => 200], 200);
    }

    public function vendorProductDesc(Request $request)
    {
        if (!$request->products) {

            return response()->json(['message' => translate('Please select some products'), 'success' => true, 'status' => 400], 400);
        }


        $products_ids = explode(',', $request->products);
        $descriptions = ProductDescription::whereIn('product_id', $products_ids)->select('title', 'sub_title', 'product_id')->get();

        return response()->json(['data' => $descriptions, 'success' => true, 'status' => 200], 200);
    }

    public function bulkDescEdit(Request $request)
    {

        if (!$request->descriptions) {

            return response()->json(['message' => translate('Please select some descriptions'), 'success' => true, 'status' => 400], 400);
        }

        foreach ($request->descriptions as $key => $product_description) {

            $product = Product::where('user_id', $request->user()->id)->where('id', $product_description['product_id'])->first();
            $exists = ProductDescription::where('product_id', $product->id)->get();
            foreach ($exists as $key => $ex) {
                $ex->delete();
            }
        }

        foreach ($request->descriptions as $key => $product_description) {


            $title = $product_description['title'];
            $subtitle = $product_description['sub_title'];
            $product_id = $product_description['product_id'];

            $product = Product::where('user_id', $request->user()->id)->where('id', $product_id)->first();

            if (!$product) {

                return response()->json(['message' => translate('Please recheck the products selected'), 'success' => true, 'status' => 400], 400);
            }

            $product_desc = new ProductDescription;
            $product_desc->title = $title;
            $product_desc->sub_title = $subtitle;
            $product_desc->product_id = $product_id;
            $product_desc->save();
        }


        return response()->json(['message' => translate('Product has been updated successfully'), 'success' => true, 'status' => 200], 200);
    }

    /**
     *
     * Bulk Delete products.
     *
     * @group Vendor Dashboard
     *
     * @authenticated
     *
     *
     * @bodyParam products integer array required array of products ids
     *
     *
     */

    public function destroy(Request $request)
    {

        if (!$request->products) {
            return response()->json(['message' => translate('Please select some products'), 'success' => true, 'status' => 400], 400);
        }

        $productsIDs = $request->products;

        foreach ($productsIDs as $key => $id) {
            $product = Product::where('id', $id)->where('user_id', $request->user()->id)->first();
            if (!$product) {
                return response()->json(['message' => translate('Please select some products'), 'success' => true, 'status' => 400], 400);
            }
            foreach ($product->product_translations as $key => $product_translations) {
                $product_translations->delete();
            }

            foreach ($product->stocks as $key => $stock) {
                $stock->delete();
            }
            // foreach ($product->sustainabilities as $key => $sustainability) {
            //     $sustainability->delete();
            // }

            if (Product::destroy($id)) {
                Cart::where('product_id', $id)->delete();
            }
        }

        return response()->json(['message' => translate('Products has been deleted successfully'), 'success' => true, 'status' => 200], 200);
    }

    /**
     * @param array $choice_nos
     * @param VendorProductStoreRequest $request
     * @return array
     */
    public function getChoiceOptions(array $choice_nos, VendorProductStoreRequest $request): array
    {
        $choice_options = [];
        foreach ($choice_nos as $key => $no) {
            $key = 'choice_options_' . $no;
            $data = [];
            foreach ($request[$key] as $value) {
                $data[] = $value;
            }
            $choice_options[] = ['attribute_id' => $no, 'values' => $data];
        }
        return $choice_options;
    }


    /**
     * @param int $product_id
     * @param $variant
     * @param $sku
     * @param $variation_image
     * @param $price
     * @param $qty
     * @param $color
     * @return ProductStock
     */
    public function createVariation(int $product_id, $variant, $sku, $variation_image, $price, $qty, $color): ProductStock
    {
        $price = $price ?? 0;
        $qty = $qty ?? 0;

        $productStock = new ProductStock;
        $productStock->product_id = $product_id;
        $productStock->variant = $variant;
        $productStock->price = $price;
        $productStock->sku = $sku;
        $productStock->qty = $qty;
        $productStock->color = $color;
        if (isset($variation_image)) $productStock->image = $variation_image;
        $productStock->save();

        return $productStock;
    }
}
