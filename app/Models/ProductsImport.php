<?php

namespace App\Models;

use App\Jobs\CSVImportJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Storage;

//class ProductsImport implements ToModel, WithHeadingRow, WithValidation
class ProductsImport implements ToCollection, WithHeadingRow, WithValidation, ToModel, WithChunkReading, ShouldQueue
{
    private $rows = 0;
    private $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {

            $price = 0;
            $qty = 0;
            $approved = 1;
            $extraImgs = null;

            $product_name=$row['name'];
            $product_name = strtok($product_name, ',');
            $product_name = trim($product_name);

            $slug = Str::slug($product_name, '-');


            $images = [];
            $image = null;

            $imgs = explode(",", $row['other_images']);



            $product = Product::firstOrNew(['name' => $product_name, 'user_id' => $this->user_id]);
            $product->collection_id=0;
            $product->save();

            if ($row['thumbnail_images']) {
                // $image = $this->downloadThumbnail($row['thumbnail_img']);
                $extension = pathinfo($row['thumbnail_images'], PATHINFO_EXTENSION);
                $filename = 'products/' . Str::random(5) . '.' . $extension;
                $file = file_get_contents($row['thumbnail_images']);
                $mainImagepath = Storage::disk('s3')->put("$filename", $file);
                $image = Storage::disk('s3')->url($filename);
            }


            if(!$product->thumbnail_img){
                $product->thumbnail_img = $image;
            }

            $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
            $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
            $slug .= $slug_suffix;

            if ($row['price']) {
                $price = $row['price'];
            }

            $colors = [];
            $sizes = [];
//            if ($row['variant1_color']) {
//                $colors[] = $row['variant1_color'];
//            }
//            if ($row['variant2_color']) {
//                $colors[] = $row['variant2_color'];
//            }
//            if ($row['variant3_color']) {
//                $colors[] = $row['variant3_color'];
//            }
//            if ($row['variant4_color']) {
//                $colors[] = $row['variant4_color'];
//            }
//            if ($row['variant5_color']) {
//                $colors[] = $row['variant5_color'];
//            }
            if ($product->choice_options && $product->choice_options != "[]") {

                $old_colors = json_decode($product->choice_options)[0]->values;
                foreach ($old_colors as $key => $old_color) {
                    $colors[]=$old_color;
                }
                if (count(json_decode($product->choice_options)) > 1) {

                    $old_sizes = json_decode($product->choice_options)[1]->values;
                    foreach ($old_sizes as $key => $old_size) {
                        $sizes[]=$old_size;
                    }
                }
            }
            if ($row['color']) {
                $colors[] = $row['color'];
            }
            if ($row['size']) {
                $sizes[] = $row['size'];
            }

//            if ($row['variant1_size']) {
//                $sizes[] = $row['variant1_size'];
//            }
//            if ($row['variant2_size']) {
//                $sizes[] = $row['variant2_size'];
//            }
//            if ($row['variant3_size']) {
//                $sizes[] = $row['variant3_size'];
//            }
//            if ($row['variant4_size']) {
//                $sizes[] = $row['variant4_size'];
//            }
//            if ($row['variant5_size']) {
//                $sizes[] = $row['variant5_size'];
//            }
            $str = "";

            if(count($colors) > 0){
                foreach ($colors as $key => $color) {

                    if(count($sizes) > 0){
                        $str = "$sizes[$key] $color";
                    }
                    else{
                        $str = "$color";
                    }
                    $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();
                    if ($product_stock == null) {
                        $product_stock = new ProductStock();
                        $product_stock->product_id = $product->id;
                    }

                }

            }
            else{
                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->firstOrNew();
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
            $product_stock->image = $image;
            $product_stock->value = $str;
            $product_stock->title = $product_name;
            $product_stock->product_id = $product->id;

            $product_stock->save();

            $choices = [];
            $choice_options=[];




            if ($colors > 0) {
                $choices[] = "15";
            }
            if ($sizes > 0) {
                $choices[] = "16";
            }

            $options1['attribute_id'] = 15;
            $options1['values'] = array_unique($colors);
            $options2['attribute_id'] = 16;
            $options2['values'] = array_unique($sizes);

            $choice_options=[];

//            if($product->choice_options != null){
//
//                $old_choice_options = json_decode($product->choice_options, true);
//                $colors_old = array_filter($old_choice_options, function($item) {
//                    return $item['attribute_id'] == 15;
//                });
//                $colors_old_array = array_column($colors_old, 'values');
////                $colors_old_array = array_pop($colors_old_array)[0];
////                dd($colors_old_array,$colors);
//                foreach ($colors_old as  $old_color){
//                    $colors[]=$old_color;
//                }
//                $colors= array_merge($colors_old_array, $colors);
//            }


            if ($colors > 0) {
                $choices[] = "15";
            }
            if ($sizes > 0) {
                $choices[] = "16";
            }

            $options1['attribute_id'] = 15;
            $options1['values'] = $colors;
            $options2['attribute_id'] = 16;
            $options2['values'] = $sizes;

            $choice_options = [];
            $choice_options[] = $options1;
            $choice_options[] = $options2;


            if (!empty($choice_options)) {
                $product->attributes = json_encode($choices);
            } else {
                $product->attributes = json_encode(array());
            }



            $product->choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);


            $product->name = $product_name;
            $product->description = $row['details'];
            $product->added_by = 'seller';
            $product->user_id = $this->user_id;
            $product->approved = 0;
            $product->published = 0;
            $product->category_id = null;
            $product->unit_price = (floatval($price));
            $product->unit = 'pc';
            $product->slug = $slug;
            $product->variant_product = 1;
            $product->est_shipping_days = $row['est_shipping_days'];
            $product->discount_type = $row['discount_mode'];
            $product->discount = $row['discount_value'];
            $product->product_type = 0;


            $shipping_cost = 0;

            $product->shipping_cost = $shipping_cost;



            if (!$product->photos) {

                try {
                    foreach ($imgs as $key => $img) {

                        $img = str_replace(' ', '', $img);
                        $extension = pathinfo($img, PATHINFO_EXTENSION);
                        $filename = 'products/' . Str::random(5) . '.' . $extension;
                        $file = file_get_contents($img);
                        $mainImagepath = Storage::disk('s3')->put("$filename", $file);
                        $imgPath = Storage::disk('s3')->url($filename);
                        // $images[] = $this->downloadThumbnail($img);
                        $images[] = $imgPath;

                    }
                    // if (count($images) > 0) {
                        $extraImgs = implode(", ", $images);
                    // }
                } catch (\Throwable $th) {
                    //throw $th;
                }
                $product->photos = $extraImgs;
            }

            $product->save();


            $lang = 'en';

            if ($lang != 'en' || $lang != 'de') {
                $lang = 'en';
            }


            // Product Translations
            $product_translation = ProductTranslation::firstOrNew(['lang' => $lang, 'product_id' => $product->id]);
            $product_translation->name = $product_name;
            // $product_translation->unit = $request->unit;
            $product_translation->description = $row['details'];
            $product_translation->save();

            // if ($lang == 'en') {
            //     // Product Translations
            //     $product_translation = ProductTranslation::firstOrNew(['lang' => 'en', 'product_id' => $product->id]);
            //     $product_translation->name = $row['title'];
            //     $product_translation->description = $row['body_html'];
            //     $product_translation->save();

            //     $product_translation_de = ProductTranslation::firstOrNew(['lang' => 'de', 'product_id' => $product->id]);
            //     $product_translation_de->name = GoogleTranslateFacade::justTranslate($row['title'], 'de');
            //     if ($row['body_html']) {

            //         $product_translation_de->description = GoogleTranslateFacade::justTranslate($row['body_html'], 'de');
            //     }
            //     $product_translation_de->save();
            // } elseif ($lang == 'de') {
            //     // Product Translations
            //     $product_translation = ProductTranslation::firstOrNew(['lang' => 'de', 'product_id' => $product->id]);
            //     $product_translation->name = $row['title'];
            //     $product_translation->description = $row['body_html'];
            //     $product_translation->save();

            //     $product_translation_en = ProductTranslation::firstOrNew(['lang' => 'en', 'product_id' => $product->id]);
            //     $product_translation_en->name = GoogleTranslateFacade::justTranslate($row['title'], 'en');
            //     if ($row['body_html']) {
            //         $product_translation_en->description = GoogleTranslateFacade::justTranslate($row['body_html'], 'en');
            //     }
            //     $product_translation_en->save();
            // }


            // $product = Product::create([
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
            //     'product_id' => $product->id,
            //     'qty' => $qty,
            //     'price' => $price,
            //     'variant' => '',
            // ]);

            $product_desc = new ProductDescription;
            $product_desc->title = 'Details';
            $product_desc->sub_title = $row['details'];
            $product_desc->product_id = $product->id;
            $product_desc->save();

            // $product_desc = new ProductDescription;
            // $product_desc->title = 'Material';
            // $product_desc->sub_title = $row['material'];
            // $product_desc->product_id = $product->id;
            // $product_desc->save();
        }


        flash(translate('CSV imported successfully'))->success();
    }

    public function model(array $row)
    {
        ++$this->rows;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function rules(): array
    {
        return [
            // Can also use callback validation rules
            // 'unit_price' => function ($attribute, $value, $onFailure) {
            //     if (!is_numeric($value)) {
            //         $onFailure('Unit price is not numeric');
            //     }
            // }
        ];
    }

    public function chunkSize(): int
    {
        return 5;
    }

    public function downloadThumbnail($url)
    {
        try {
            $extension = pathinfo($url, PATHINFO_EXTENSION);
            $filename = 'uploads/products/' . Str::random(5) . '.' . $extension;
            $fullpath = 'public/' . $filename;
            $file = file_get_contents($url);
            file_put_contents($fullpath, $file);

            $upload = new Upload;
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
            // dd($e);
        }
        return null;
    }
}
