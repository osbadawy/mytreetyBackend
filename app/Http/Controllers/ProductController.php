<?php

namespace App\Http\Controllers;

use App\Models\AttributeValue;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductTax;
use App\Models\ProductTranslation;
use App\Models\Sustainability;
use App\Models\Upload;
use App\Models\User;
use Artisan;
use Auth;
use Cache;
use Carbon\Carbon;
use Combinations;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JoggApp\GoogleTranslate\GoogleTranslateFacade;
use Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function admin_products(Request $request): Response
    {

        $not_approved_products = Product::where('approved', 1)->get();

        foreach ($not_approved_products as $key => $not_approved_product) {
            $not_approved_product->approved = 1;
            $not_approved_product->save();
        }


        $type = 'In House';
        $col_name = null;
        $query = null;
        $sort_search = null;

        $products = Product::where('added_by', 'admin');

        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null) {
            $products = $products
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }

        $products = $products->orderBy('created_at', 'desc')->paginate(15);

        return view('backend.product.products.index', compact('products', 'type', 'col_name', 'query', 'sort_search'));
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function seller_products(Request $request)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $products = Product::where('added_by', 'seller');
        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        if ($request->search != null) {
            $products = $products
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }

        $products = $products->orderBy('created_at', 'desc')->paginate(15);
        $type = 'Seller';

        return view('backend.product.products.index', compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }

    public function all_products(Request $request)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $products = Product::orderBy('created_at', 'desc');
        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        if ($request->search != null) {
            $products = $products
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }

        $products = $products->paginate(15);
        $type = 'All';

        return view('backend.product.products.index', compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }


    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {

        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();

        $sustainabilities = Sustainability::take(25)->get();

        return view('backend.product.products.create', compact('categories', 'sustainabilities'));
    }

    public function add_more_choice_option(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('attribute_id', $request->attribute_id)->get();

        $html = '';

        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->value . '">' . $row->value . '</option>';
        }

        echo json_encode($html);
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request): RedirectResponse
    {
        dd($request);
        $product = new Product;
        $product->name = $request->name;
        $product->added_by = $request->added_by;
        if (Auth::user()->user_type == 'seller') {
            $product->user_id = Auth::user()->id;
            if (get_setting('product_approve_by_admin') == 1) {
                $product->approved = 0;
            }
        } else {
            $product->user_id = User::where('user_type', 'admin')->first()->id;
        }
        $product->category_id = $request->category_id;
        $product->barcode = $request->barcode;
        $product->photos = $request->photos;
        $product->thumbnail_img = $request->thumbnail_img;
        $product->unit = $request->unit;
        $product->min_qty = $request->min_qty;
        $product->low_stock_quantity = $request->low_stock_quantity;
        $product->stock_visibility_state = $request->stock_visibility_state;
        $product->external_link = $request->external_link;
        $product->external_link_btn = $request->external_link_btn;

        $tags = array();
        if ($request->tags[0] != null) {
            foreach (json_decode($request->tags[0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $product->tags = implode(',', $tags);

        $product->description = $request->description;
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        $product->unit_price = $request->unit_price;
        $product->discount = $request->discount;
        $product->discount_type = $request->discount_type;

        if ($request->date_range != null) {
            $date_var = explode(" to ", $request->date_range);
            $product->discount_start_date = strtotime($date_var[0]);
            $product->discount_end_date = strtotime($date_var[1]);
        }

        $product->shipping_type = $request->shipping_type;
        $product->est_shipping_days = $request->est_shipping_days;


        $product->manufactured = $request->manufactured;
        $product->distributed = $request->distributed;

        if ($request->has('shipping_type')) {
            if ($request->shipping_type == 'free') {
                $product->shipping_cost = 0;
            } elseif ($request->shipping_type == 'flat_rate') {
                $product->shipping_cost = $request->flat_shipping_cost;
            } elseif ($request->shipping_type == 'product_wise') {
                $product->shipping_cost = json_encode($request->shipping_cost);
            }
        }
        if ($request->has('is_quantity_multiplied')) {
            $product->is_quantity_multiplied = 1;
        }

        $slug = $request->slug ? Str::slug($request->slug, '-') : Str::slug($request->name, '-');
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        $product->slug = $slug;

        $choice_options = array();

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;

                $item['attribute_id'] = $no;

                $data = array();
                // foreach (json_decode($request[$str][0]) as $key => $eachValue) {
                foreach ($request[$str] as $key => $eachValue) {
                    // array_push($data, $eachValue->value);
                    array_push($data, $eachValue);
                }

                $item['values'] = $data;
                array_push($choice_options, $item);
            }
        }

        if (!empty($request->choice_no)) {
            $product->attributes = json_encode($request->choice_no);
        } else {
            $product->attributes = json_encode(array());
        }

        $product->choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);

        $product->published = 1;
        if ($request->button == 'unpublish' || $request->button == 'draft' || Auth::user()->user_type == 'seller') {
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
        //$variations = array();

        $product->save();

        // sustainabilities
        if ($request->has('sustainabilities')) {

            $product->sustainabilities()->attach($request->sustainabilities);

            $count = count($request->sustainabilities);
            $ranking = 1;

            if ($count > 6) {
                $ranking = 3;
            } elseif ($count == 4 || $count == 5) {
                $ranking = 2;
            }

            $product->sustainability_rank = $ranking;
        }

        //VAT & Tax
        if ($request->tax_id) {
            foreach ($request->tax_id as $key => $val) {
                $product_tax = new ProductTax;
                $product_tax->tax_id = $val;
                $product_tax->product_id = $product->id;
                $product_tax->tax = $request->tax[$key];
                $product_tax->tax_type = $request->tax_type[$key];
                $product_tax->save();
            }
        }

        //combinations start
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                foreach ($request[$name] as $key => $eachValue) {
                    array_push($data, $eachValue);
                }
                array_push($options, $data);
            }
        }

        //Generates the combinations of customer choice options
        $combinations = Combinations::makeCombinations($options);
        if (count($combinations[0]) > 0) {
            $product->variant_product = 1;
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $key => $item) {

                    if ($key > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
                            $color_name = Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        } else {
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();
                if ($product_stock == null) {
                    $product_stock = new ProductStock;
                    $product_stock->product_id = $product->id;
                }
                if ($request['price_' . str_replace('.', '_', $str)] != null) {
                    $price = $request['price_' . str_replace('.', '_', $str)];
                } else {
                    $price = 0;
                }

                if ($request['qty_' . str_replace('.', '_', $str)] != null) {
                    $qty = $request['qty_' . str_replace('.', '_', $str)];
                } else {
                    $qty = 0;
                }

                $product_stock->variant = $str;
                $product_stock->price = $price;
                $product_stock->sku = $request['sku_' . str_replace('.', '_', $str)];
                $product_stock->qty = $qty;
                $product_stock->image = $request['img_' . str_replace('.', '_', $str)];
                $product_stock->value = $item;

                $product_stock->save();
            }
        } else {
            $product_stock = new ProductStock;
            $product_stock->product_id = $product->id;
            $product_stock->variant = '';
            $product_stock->price = $request->unit_price;
            $product_stock->sku = $request->sku;
            $product_stock->qty = $request->current_stock;
            $product_stock->save();
        }
        //combinations end

        $product->save();

        // $lang=GoogleTranslateFacade::detectLanguage($request->name)['language_code'];
        // $lang = $request->lang;
        $lang = 'en';

        if ($lang != 'en' || $lang != 'de') {
            $lang = 'en';
        }

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

        flash(translate('Product has been inserted successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');


        return redirect()->route('products.admin');

    }


    /**
     * Show the form for editing the specified resource.
     *
     *
     */
    public function admin_product_edit(Request $request, $id)
    {


        $product = Product::findOrFail($id);

        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();

        $sustainabilities = Sustainability::take(25)->get();

        return view('backend.product.products.edit', compact('product', 'categories', 'tags', 'lang', 'sustainabilities'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     *
     */
    public function seller_product_edit(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::all();
        $sustainabilities = Sustainability::take(25)->get();
        return view('backend.product.products.edit', compact('product', 'categories', 'tags', 'lang', 'sustainabilities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $product = Product::findOrFail($id);
        $product->category_id = $request->category_id;
        $product->cash_on_delivery = 0;
        $product->featured = 0;

        if ($request->refundable != null) {
            $product->refundable = 1;
        } else {
            $product->refundable = 0;
        }

        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $product->name = $request->name;
            $product->unit = $request->unit;
            $product->description = $request->description;
        }

        $slug = $request->slug ? Str::slug($request->slug, '-') : Str::slug($request->name, '-');
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count > 1 ? '-' . ($same_slug_count + 1) : '';
        $slug .= $slug_suffix;

        // $product->slug = $slug;

        $product->photos = $request->photos;
        $product->thumbnail_img = $request->thumbnail_img;
        $product->min_qty = $request->min_qty;
        $product->low_stock_quantity = $request->low_stock_quantity;
        $product->stock_visibility_state = $request->stock_visibility_state;
        $product->external_link = $request->external_link;
        $product->external_link_btn = $request->external_link_btn;

        $tags = array();
        if ($request->tags[0] != null) {
            foreach (json_decode($request->tags[0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $product->tags = implode(',', $tags);

        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        $product->unit_price = $request->unit_price;
        $product->discount = $request->discount;
        $product->discount_type = $request->discount_type;

        if ($request->date_range != null) {
            $date_var = explode(" to ", $request->date_range);
            $product->discount_start_date = strtotime($date_var[0]);
            $product->discount_end_date = strtotime($date_var[1]);
        }

        $product->shipping_type = $request->shipping_type;
        $product->est_shipping_days = $request->est_shipping_days;


        $product->manufactured = $request->manufactured;
        $product->distributed = $request->distributed;

        if ($request->has('sustainabilities')) {
            $product->sustainabilities()->sync($request->sustainabilities);

            $count = count($request->sustainabilities);
            $ranking = 1;

            if ($count > 6) {
                $ranking = 3;
            } elseif ($count == 4 || $count == 5) {
                $ranking = 2;
            }
            $product->sustainability_rank = $ranking;
        }

        if ($request->has('shipping_type')) {
            if ($request->shipping_type == 'free') {
                $product->shipping_cost = 0;
            } elseif ($request->shipping_type == 'flat_rate') {
                $product->shipping_cost = $request->flat_shipping_cost;
            } elseif ($request->shipping_type == 'product_wise') {
                $product->shipping_cost = json_encode($request->shipping_cost);
            }
        }

        if ($request->has('is_quantity_multiplied')) {
            $product->is_quantity_multiplied = 1;
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

        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_img = $request->meta_img;

        if ($product->meta_title == null) {
            $product->meta_title = $product->name;
        }

        if ($product->meta_description == null) {
            $product->meta_description = strip_tags($product->description);
        }

        if ($product->meta_img == null) {
            $product->meta_img = $product->thumbnail_img;
        }

        $product->pdf = $request->pdf;

        $choice_options = array();

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;

                $item['attribute_id'] = $no;

                $data = array();
                if ($request[$str] != null) {

                    foreach ($request[$str] as $key => $eachValue) {
                        array_push($data, $eachValue);
                    }

                }

                $item['values'] = $data;
                array_push($choice_options, $item);
            }
        }

        // foreach ($product->stocks as $key => $stock) {
        //     $stock->delete();
        // }

        if (!empty($request->choice_no)) {
            $product->attributes = json_encode($request->choice_no);
        } else {
            $product->attributes = json_encode(array());
        }

        $product->choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);


        //combinations start
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                foreach ($request[$name] as $key => $item) {
                    array_push($data, $item);
                }
                array_push($options, $data);
            }
        }

        $combinations = Combinations::makeCombinations($options);

        if (count($combinations[0]) > 0) {
            $product->variant_product = 1;
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $key => $item) {
                    if ($key > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
                            $color_name = Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        } else {
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }

                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();

                if ($product_stock == null) {
                    $product_stock = new ProductStock;
                    $product_stock->product_id = $product->id;
                }
                if (isset($request['price_' . str_replace('.', '_', $str)])) {

                    $product_stock->variant = $str;
                    $product_stock->price = $request['price_' . str_replace('.', '_', $str)];
                    $product_stock->sku = $request['sku_' . str_replace('.', '_', $str)];
                    $product_stock->qty = $request['qty_' . str_replace('.', '_', $str)];
                    $product_stock->image = $request['img_' . str_replace('.', '_', $str)];
                    $product_stock->color = $request['color_' . str_replace('.', '_', $str)];


                    $product_stock->save();
                }
            }
        } else {
            ;
            $product_stock = new ProductStock;
            $product_stock->product_id = $product->id;
            $product_stock->variant = '';
            $product_stock->price = $request->unit_price;
            $product_stock->sku = $request->sku;
            $product_stock->qty = $request->current_stock;
            $product_stock->save();
        }

        $product->save();

        //VAT & Tax
        if ($request->tax_id) {
            ProductTax::where('product_id', $product->id)->delete();
            foreach ($request->tax_id as $key => $val) {
                $product_tax = new ProductTax;
                $product_tax->tax_id = $val;
                $product_tax->product_id = $product->id;
                $product_tax->tax = $request->tax[$key];
                $product_tax->tax_type = $request->tax_type[$key];
                $product_tax->save();
            }
        }

        // Product Translations
        $product_translation = ProductTranslation::firstOrNew(['lang' => $request->lang, 'product_id' => $product->id]);
        $product_translation->name = $request->name;
        $product_translation->unit = $request->unit;
        $product_translation->description = $request->description;
        $product_translation->save();

        flash(translate('Product has been updated successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return back();
    }

    public function bulk_product_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $product_id) {
                $this->destroy($product_id);
            }
        }

        return 1;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        foreach ($product->product_translations as $key => $product_translations) {
            $product_translations->delete();
        }

        foreach ($product->stocks as $key => $stock) {
            $stock->delete();
        }

        if (Product::destroy($id)) {
            Cart::where('product_id', $id)->delete();

            flash(translate('Product has been deleted successfully'))->success();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return back();
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Duplicates the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function duplicate(Request $request, $id)
    {
        $product = Product::find($id);

        if (Auth::user()->id == $product->user_id || Auth::user()->user_type == 'staff') {
            $product_new = $product->replicate();
            $product_new->slug = $product_new->slug . '-' . Str::random(5);
            $product_new->save();

            foreach ($product->stocks as $key => $stock) {
                $product_stock = new ProductStock;
                $product_stock->product_id = $product_new->id;
                $product_stock->variant = $stock->variant;
                $product_stock->price = $stock->price;
                $product_stock->sku = $stock->sku;
                $product_stock->qty = $stock->qty;
                $product_stock->save();
            }

            foreach ($product->sustainabilities as $key => $sustainability) {
                $product_new->sustainabilities()->attach($sustainability->id);
            }

            flash(translate('Product has been duplicated successfully'))->success();
            if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
                if ($request->type == 'In House')
                    return redirect()->route('products.admin');
                elseif ($request->type == 'Seller')
                    return redirect()->route('products.seller');
                elseif ($request->type == 'All')
                    return redirect()->route('products.all');
            } else {
                return redirect()->route('seller.products');
            }
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }


    public function updatePublished(Request $request): int
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;

        $product->save();
        return 1;
    }

    public function updateProductApproval(Request $request): int
    {
        $product = Product::findOrFail($request->id);
        $product->approved = $request->approved;

        $product->save();
        return 1;
    }

    public function updateFeatured(Request $request): int
    {
        $product = Product::findOrFail($request->id);
        $product->featured = $request->status;
        if ($product->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }


    public function sku_combination(Request $request)
    {
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            $options[] = $request->colors;
        } else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                // foreach (json_decode($request[$name][0]) as $key => $item) {
                foreach ($request[$name] as $key => $item) {
                    // array_push($data, $item->value);
                    $data[] = $item;
                }
                $options[] = $data;
            }
        }

        $combinations = Combinations::makeCombinations($options);
        return view('backend.product.products.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
    }

    public function sku_combination_edit(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $options = array();

        $product_name = $request->name;
        $unit_price = $request->unit_price;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                // foreach (json_decode($request[$name][0]) as $key => $item) {
                foreach ($request[$name] as $key => $item) {
                    // array_push($data, $item->value);
                    array_push($data, $item);
                }
                array_push($options, $data);
            }
        }


        $combinations = Combinations::makeCombinations($options);


        return view('backend.product.products.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
    }

    public function deleteAll(Request $request): RedirectResponse
    {
        $prods = Product::where('user_id', $request->user()->id)->get();

        foreach ($prods as $key => $prod) {
            $upload = Upload::find($prod->thumbnail_img);
            if ($upload) {

                $thumbnail_filename = Upload::find($prod->thumbnail_img)->file_name;
                $currentdir = getcwd();
                $thumbnail_img = "$currentdir/public/$thumbnail_filename";
                if (file_exists($thumbnail_img)) {
                    unlink($thumbnail_img);
                    Upload::find($prod->thumbnail_img)->delete();
                }
            }

            $images = explode(',', $prod->photos);

            foreach ($images as $file) {
                $upload = Upload::find($file);
                if ($upload) {
                    $filename = Upload::find($file)->file_name;
                    $currentdir = getcwd();
                    $thumbnail_img = "$currentdir/public/$filename";
                    if (file_exists($file)) {
                        unlink($file);
                        Upload::find($file)->delete();
                    }
                }
            }
            foreach ($prod->stocks as $key => $stock) {
                $stock->delete();
            }
            $prod->delete();
        }
        $prods = Product::all();
        $ids = [];
        foreach ($prods as $key => $prod) {
            $ids[] = $prod->id;
        }
        $stocks = ProductStock::all();
        foreach ($stocks as $key => $stock) {
            if (!in_array($stock->product_id, $ids)) {
                $stock->delete();
            }
        }
        flash(translate('All products deleted successfully'))->success();
        return back();
    }
}
