<?php

namespace App\Jobs;


use App\Models\Product;
use App\Models\ProductDescription;
use App\Models\ProductStock;
use App\Models\ProductTranslation;
use App\Models\Upload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Storage;
use Str;

class XMLSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 12000;

    /**
     * Create a new job instance.
     *
     * @return void
     */

     private $products;
     private $user_id;
    public function __construct($products,$user_id)
    {
        $this->products = $products;
        $this->user_id= $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $products=$this->products;
        foreach ($products as $row) {

            $price = 0;
            $qty = 0;
            $extraImgs = null;

            $slug = Str::slug($row['name'], '-');



            $imgs = [];
            $images = [];

            $imgs = explode(",", $row['other_images']);



            $productId = Product::firstOrNew(['name' => $row['name'], 'user_id' => $this->user_id]);
            $productId->save();


            $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
            $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
            $slug .= $slug_suffix;

            if ($row['price']) {
                $price = $row['price'];
            }

            $colors=[];
            $sizes=[];
            if($row['variant1_color']){
                $colors[]=$row['variant1_color'];
            }
            if($row['variant2_color']){
                $colors[]=$row['variant2_color'];
            }
            if($row['variant3_color']){
                $colors[]=$row['variant3_color'];
            }
            if($row['variant4_color']){
                $colors[]=$row['variant4_color'];
            }
            if($row['variant5_color']){
                $colors[]=$row['variant5_color'];
            }

            if($row['variant1_size']){
                $sizes[]=$row['variant1_size'];
            }
            if($row['variant2_size']){
                $sizes[]=$row['variant2_size'];
            }
            if($row['variant3_size']){
                $sizes[]=$row['variant3_size'];
            }
            if($row['variant4_size']){
                $sizes[]=$row['variant4_size'];
            }
            if($row['variant5_size']){
                $sizes[]=$row['variant5_size'];
            }

            foreach ($colors as $key => $color) {

                $str = "$color $sizes[$key]";
                $product_stock = ProductStock::where('product_id', $productId->id)->where('variant', $str)->first();
                if ($product_stock == null) {
                    $product_stock = new ProductStock();
                    $product_stock->product_id = $productId->id;
                }

            }

            if ($row['quantity'] > 0) {
                $inventory_quantity = $row['quantity'];
            } else {
                $inventory_quantity = 100;
            }

            $product_stock->variant = $str;
            $product_stock->price = (float)$price;
            $product_stock->sku = $row['sku'];
            $product_stock->qty = $inventory_quantity;
            // $product_stock->image = $request['img_' . str_replace('.', '_', $str)];
            $product_stock->value = $str;
            $product_stock->title = $row['name'];


            $product_stock->save();

            $choices=["15","16"];
            $options1['attribute_id']= $choices[0];
            $options1['values']=$colors;
            $options2['attribute_id']=16;
            $options2['values']=$sizes;

            $choice_options=[];
            $choice_options[]=$options1;
            $choice_options[]=$options2;


            if (!empty($choice_options)) {
                $productId->attributes = json_encode($choices);
            } else {
                $productId->attributes = json_encode(array());
            }


            $productId->choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);


            $productId->name =  $row['name'];
            $productId->description = $row['details'];
            $productId->added_by = 'seller';
            $productId->user_id = $this->user_id;
            $productId->approved = 0;
            $productId->category_id = null;
            $productId->unit_price = (floatval($price));
            $productId->unit = 'pc';
            $productId->meta_title = $row['name'];
            $productId->meta_description = $row['details'];
            $productId->colors = json_encode(array());
            $productId->variations = json_encode(array());
            $productId->slug = $slug;
            $productId->variant_product = 1;
            $productId->est_shipping_days = $row['est_shipping_days'];


            $shipping_cost = 0;

            $productId->shipping_cost = $shipping_cost;


            if (!$productId->thumbnail_img && $row['thumbnail_img']) {
                // $image = $this->downloadThumbnail($row['thumbnail_img']);
                $extension = pathinfo(trim($row['thumbnail_img']), PATHINFO_EXTENSION);
                $filename = 'products/' . Str::random(5) . '.' . $extension;
                $file = file_get_contents(trim($row['thumbnail_img']));
                $mainImagepath = Storage::disk('s3')->put("$filename", $file);
                $image = Storage::disk('s3')->url($filename);

                $productId->thumbnail_img = $image;
            }
            if (!$productId->photos) {

                try {
                    foreach ($imgs as $key => $img) {
                        $extension = pathinfo(trim($img), PATHINFO_EXTENSION);
                        $filename = 'products/' . Str::random(5) . '.' . $extension;
                        $file = file_get_contents(trim($img));
                        $mainImagepath = Storage::disk('s3')->put("$filename", $file);
                        $img = Storage::disk('s3')->url($filename);
                        // $images[] = $this->downloadThumbnail($img);
                        $images[] = $img= $img;

                    }
                    if (count($imgs) > 0) {
                        $extraImgs = implode(", ", $images);
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }

                $productId->photos = $extraImgs;
            }

            $productId->save();







            $lang = 'en';

            if ($lang != 'en' || $lang != 'de') {
                $lang = 'en';
            }


            // Product Translations
            $product_translation = ProductTranslation::firstOrNew(['lang' => $lang, 'product_id' => $productId->id]);
            $product_translation->name = $row['name'];
            // $product_translation->unit = $request->unit;
            $product_translation->description = $row['details'];
            $product_translation->save();

            // if ($lang == 'en') {
            //     // Product Translations
            //     $product_translation = ProductTranslation::firstOrNew(['lang' => 'en', 'product_id' => $productId->id]);
            //     $product_translation->name = $row['title'];
            //     $product_translation->description = $row['body_html'];
            //     $product_translation->save();

            //     $product_translation_de = ProductTranslation::firstOrNew(['lang' => 'de', 'product_id' => $productId->id]);
            //     $product_translation_de->name = GoogleTranslateFacade::justTranslate($row['title'], 'de');
            //     if ($row['body_html']) {

            //         $product_translation_de->description = GoogleTranslateFacade::justTranslate($row['body_html'], 'de');
            //     }
            //     $product_translation_de->save();
            // } elseif ($lang == 'de') {
            //     // Product Translations
            //     $product_translation = ProductTranslation::firstOrNew(['lang' => 'de', 'product_id' => $productId->id]);
            //     $product_translation->name = $row['title'];
            //     $product_translation->description = $row['body_html'];
            //     $product_translation->save();

            //     $product_translation_en = ProductTranslation::firstOrNew(['lang' => 'en', 'product_id' => $productId->id]);
            //     $product_translation_en->name = GoogleTranslateFacade::justTranslate($row['title'], 'en');
            //     if ($row['body_html']) {
            //         $product_translation_en->description = GoogleTranslateFacade::justTranslate($row['body_html'], 'en');
            //     }
            //     $product_translation_en->save();
            // }



            // $productId = Product::create([
            //     'name' => $row['title'],
            //     'description' => $row['body_html'],
            //     'added_by' => Auth::user()->user_type == 'seller' ? 'seller' : 'admin',
            //     'user_id' => Auth::user()->user_type == 'seller' ? $this->user_id : User::where('user_type', 'admin')->first()->id,
            //     'approved' => 1,
            //     'unit_price' => (floatval($price)),
            //     'purchase_price' => $row['unit_price'],
            //     'unit' => 'pc',
            //     'meta_title' => $row['title'],
            //     'meta_description' => $row['body_html'],
            //     'colors' => json_encode(array()),
            //     'choice_options' => json_encode(array()),
            //     'variations' => json_encode(array()),
            //     'slug' => $slug,
            //     'manufactured' => $row['where_manufactured'],
            //     'distributed' => $row['where_distributed'],
            //     'est_shipping_days' => $row['est_shipping_days'],
            //     'shipping_cost' => $row['shipping_cost'],
            //     'thumbnail_img' => $image,
            //     'photos' => $image

            // ]);
            // ProductStock::create([
            //     'product_id' => $productId->id,
            //     'qty' => $qty,
            //     'price' => $price,
            //     'variant' => '',
            // ]);

            $product_desc = new ProductDescription;
            $product_desc->title = 'Details';
            $product_desc->sub_title = $row['details'];
            $product_desc->product_id = $productId->id;
            $product_desc->save();

            $product_desc = new ProductDescription;
            $product_desc->title = 'Material';
            $product_desc->sub_title = $row['material'];
            $product_desc->product_id = $productId->id;
            $product_desc->save();
        }

    }

    public function downloadThumbnail($url)
    {
        $url=str_replace(array("\r\n", "\r", "\n", "\t"), '', $url);
        try {
            $extension = pathinfo($url, PATHINFO_EXTENSION);
            $filename = 'uploads/products/' . Str::random(5) . '.' . $extension;
            $fullpath = 'public/' . $filename;
            $file = file_get_contents(str_replace(array(' '), '%20', $url));
            file_put_contents($fullpath, $file);

            $upload = new Upload();
            $upload->extension = strtolower($extension);

            $upload->file_original_name = $filename;
            $upload->file_name = $filename;
            $upload->user_id = $this->user_id;
            $upload->type = "image";
            $upload->file_size = filesize(base_path($fullpath));
            $upload->save();

            if (env('FILESYSTEM_DRIVER') == 's3') {
                $s3 = Storage::disk('s3');
                $s3->put($filename, file_get_contents(base_path($fullpath)));
                unlink(base_path($fullpath));
            }

            return $upload->id;
        } catch (\Exception $e) {
             dd($e);
        }
        return null;
    }

    public function old($var = null)
    {
        foreach ($this->products as $key => $product) {
            $name = '';
            $description = '';
            $price = 0;
            $est_shipping_days = 0;
            $inventory_quantity = 0;
            $main_image = null;
            $shipping_cost = 0;
            $manufactured = '';
            $distributed = '';
            $lang = 'en';
            $is_variable = null;
            $parent_sku = null;
            $sku = null;
            $color = null;

            if (array_key_exists('title', $product))  $name = str_replace(array("\r\n", "\r", "\n", "\t"), '', $product['title']);
            if (array_key_exists('description', $product)) $description = str_replace(array("\r\n", "\r", "\n", "\t"), '', $product['description']);
            if (array_key_exists('price', $product)) $price = str_replace(array("\r\n", "\r", "\n", "\t"), '', $product['unit_price']);
            if (array_key_exists('est_shipping_days', $product)) $est_shipping_days = str_replace(array("\r\n", "\r", "\n", "\t"), '', $product['estimate_shipping_time']);
            if (array_key_exists('inventory_quantity', $product)) $inventory_quantity = str_replace(array("\r\n", "\r", "\n", "\t"), '', $product['inventory_quantity']);
            if (array_key_exists('main_image', $product)) $main_image = str_replace(array("\r\n", "\r", "\n", "\t"), '', $product['main_image']);
            if (array_key_exists('shipping_cost', $product)) $shipping_cost = str_replace(array("\r\n", "\r", "\n", "\t"), '', $product['shipping_cost']);
            if (array_key_exists('manufactured', $product)) $manufactured = str_replace(array("\r\n", "\r", "\n", "\t"), '', $product['where_manufactured']);
            if (array_key_exists('distributed', $product)) $distributed = str_replace(array("\r\n", "\r", "\n", "\t"), '', $product['where_distributed']);
            if (array_key_exists('productimages', $product)) $productimages = str_replace(array("\r\n", "\r", "\n", "\t", "'"), '', $product['images']);
            if (array_key_exists('lang', $product)) $lang = str_replace(array("\r\n", "\r", "\n", "\t"), '', $product['lang']);
            if (array_key_exists('parent_sku', $product)) $parent_sku = str_replace(array("\r\n", "\r", "\n", "\t"), '', $product['parent_sku']);
            if (array_key_exists('is_variable', $product)) $is_variable = str_replace(array("\r\n", "\r", "\n", "\t"), '', $product['is_variable']);
            if (array_key_exists('sku', $product)) $sku = str_replace(array("\r\n", "\r", "\n", "\t"), '', $product['sku']);
            if (array_key_exists('color', $product)) $color = str_replace(array("\r\n", "\r", "\n", "\t"), '', $product['color']);

            $imgs = explode(",", $productimages);
            $images = [];

            try {
                foreach ($imgs as $key => $img) {
                    $images[] = $this->downloadThumbnail($img);
                }
                if (count($images) > 0) {
                    $extraImgs = implode(", ", $images);
                }
            } catch (\Throwable $th) {
                //throw $th;
            }

            $slug = Str::slug($name, '-');

            $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
            $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
            $slug .= $slug_suffix;

            // variable product
            if ($parent_sku) {
                $parentId = ProductStock::where('sku', $parent_sku)->first();

                if (!$parentId) {
                    flash(translate('Please check all parent SKU'))->warning();
                    return redirect()->back();
                }

                $stock = ProductStock::firstOrNew(['sku' => $sku]);
                $stock->qty = (float)$inventory_quantity;
                $stock->sku = $sku;
                $stock->color = $color;
                $stock->price = (float)$price;
                $stock->product_id  = $parentId->id;
                $stock->image = $this->downloadThumbnail($main_image);
                $stock->save();
            } else {
                $productId = Product::create([
                    'name' => $name,
                    'description' => $description,
                    'added_by' =>  'seller' ,
                    'user_id' =>  $this->user_id,
                    'approved' => 0,
                    'category_id' => null,
                    'unit_price' => (float)$price,
                    'purchase_price' => (float)$price,
                    'unit' => 'pc',
                    'meta_title' => $name,
                    'meta_description' => $description,
                    'colors' => json_encode(array()),
                    'choice_options' => json_encode(array()),
                    'variations' => json_encode(array()),
                    'slug' => $slug,
                    'photos' => $extraImgs,
                    'est_shipping_days' => $est_shipping_days,
                    'shipping_cost' => $shipping_cost,
                    'manufactured' => $manufactured,
                    'distributed' => $distributed,
                    'thumbnail_img' => $this->downloadThumbnail($main_image),
                ]);
                if ($is_variable == 'yes') {
                    $productId->variant_product = 1;
                    $productId->save();
                }

                $stock = ProductStock::firstOrNew(['product_id' => $productId->id]);
                $stock->qty = (float)$inventory_quantity;
                $stock->sku = $sku;
                $stock->price = (float)$price;
                $stock->product_id  = $productId->id;
                $stock->save();
            }

            if ($lang != 'en' || $lang != 'de') {
                $lang = 'en';
            }

            // if ($lang == 'en') {
            //     // Product Translations
            //     $product_translation = ProductTranslation::firstOrNew(['lang' => 'en', 'product_id' => $productId->id]);
            //     $product_translation->name = $name;
            //     $product_translation->description = $description;
            //     $product_translation->save();

            //     $product_translation_de = ProductTranslation::firstOrNew(['lang' => 'de', 'product_id' => $productId->id]);
            //     // $product_translation_de->name = GoogleTranslateFacade::justTranslate($name, 'de');
            //     if ($description) {
            //         $product_translation_de->description = GoogleTranslateFacade::justTranslate($description, 'de');
            //     }
            //     $product_translation_de->save();
            // } elseif ($lang == 'de') {
            //     // Product Translations
            //     $product_translation = ProductTranslation::firstOrNew(['lang' => 'de', 'product_id' => $productId->id]);
            //     //  $product_translation->name = $name;
            //     $product_translation->description = $description;
            //     $product_translation->save();

            //     $product_translation_de = ProductTranslation::firstOrNew(['lang' => 'en', 'product_id' => $productId->id]);
            //     // $product_translation_de->name = GoogleTranslateFacade::justTranslate($name, 'en');
            //     if ($description) {
            //         $product_translation_de->description = GoogleTranslateFacade::justTranslate($description, 'en');
            //     }
            //     $product_translation_de->save();
            // }
            // Product Translations
            $product_translation = ProductTranslation::firstOrNew(['lang' => $lang, 'product_id' => $productId->id]);
            $product_translation->name = $name;
            // $product_translation->unit = $request->unit;
            $product_translation->description = $description;
            $product_translation->save();
        }
    }
}
