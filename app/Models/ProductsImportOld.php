<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use Auth;
use Combinations;
use JoggApp\GoogleTranslate\GoogleTranslateFacade;
use Storage;

//class ProductsImport implements ToModel, WithHeadingRow, WithValidation
class ProductsImportOld implements ToCollection, WithHeadingRow, WithValidation, ToModel
{
    private $rows = 0;

    public function collection(Collection $rows)
    {
        $canImport = true;

        if ($canImport) {

            $test_rows = $rows->take(1000);
            // dd($test_rows);
            foreach ($test_rows as $row) {

                $price = 0;
                $qty = 0;
                $approved = 1;

                $slug = Str::slug($row['name'], '-');
                $image = $this->downloadThumbnail($row['thumbnail_img']);

                $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
                $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
                $slug .= $slug_suffix;

                if ($row['unit_price']) {
                    $price = $row['unit_price'];
                }
                if ($row['current_stock']) {
                    $qty = $row['current_stock'];
                } else {
                    $qty = 100;
                }


                $sku = rand();
                if ($row['sku']) {
                    $sku = $row['sku'];
                }

                $ProductAtts = ['General Sizes'];
                $ProductVals = [];

                $sameprods = $rows->where('description', $row['description']);
                foreach ($sameprods as $key => $sameprod) {
                    $ProductVals[] = $sameprod['size'];
                }

                $attributeIDs = [];
                $values = [];


                // check if atts and values exist and create them
                foreach ($ProductAtts as $key => $ProductAtt) {

                    $attribute = Attribute::firstOrNew(['name' => $ProductAtt]);
                    $attribute->save();
                    $attributeIDs[] = $attribute->id;

                    foreach ($ProductVals as $key => $value) {
                        $val = AttributeValue::firstOrNew(['attribute_id' => $attribute->id, 'value' => $value]);
                        $val->save();
                    }
                    $values[$attribute->id] = array($row['size']);
                }
                $choices = $attributeIDs;
                $options = [];
                $product_options = $test_rows->where('name', $row['name']);
                foreach ($product_options as $key => $option) {

                    $options[] = $option['size'];
                }


                $productId = Product::firstOrNew(['name' => $row['name'], 'user_id' => Auth::user()->id]);

                $productId->name =  $row['name'];
                $productId->description = $row['description'];
                $productId->added_by = 'seller';
                $productId->user_id = Auth::user()->id;
                $productId->approved = 1;
                $productId->category_id = null;
                $productId->unit_price = (floatval($price));
                $productId->purchase_price = (floatval($price));
                $productId->unit = 'pc';
                $productId->meta_title = $row['name'];
                $productId->meta_description = $row['description'];
                $productId->colors = json_encode(array());
                $productId->choice_options = json_encode(array());
                $productId->variations = json_encode(array());
                $productId->slug = $slug;
                $productId->variant_product = 1;
                $choice_options = array();
                // $choices = ['1'];
                $options = $values;

                foreach ($choices as $key => $no) {

                    $item['attribute_id'] = $no;

                    $data = array();
                    // foreach (json_decode($request[$str][0]) as $key => $eachValue) {
                    foreach (array_unique($ProductVals) as $key => $eachValue) {

                        // array_push($data, $eachValue->value);
                        array_push($data, $eachValue);
                    }
                    $item['values'] = $data;
                    array_push($choice_options, $item);
                }

                if (!empty($choice_options)) {
                    $productId->attributes = json_encode($choices);
                } else {
                    $productId->attributes = json_encode(array());
                }

                $productId->choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);


                if ($image) {

                    $productId->photos = $image;
                }
                $shipping_cost = $row['shipping_cost'];
                if ($row['shipping_cost'] == "Free Shipping") {
                    $shipping_cost = 0;
                }
                $productId->est_shipping_days = $row['est_shipping_days'];
                $productId->shipping_cost = $shipping_cost;
                $productId->manufactured = $row['where_manufactured'];
                $productId->distributed = $row['where_distributed'];

                if (!$productId->thumbnail_img) {

                    $productId->thumbnail_img = $image;
                }
                $productId->save();


                // save variations
                //Generates the combinations of customer choice options

                $combinations = Combinations::makeCombinations($options);
                foreach ($product_options as $key => $product_option) {
                    $str = '';

                    if (count($combinations[0]) > 0) {
                        $productId->variant_product = 1;
                        foreach ($combinations as $key => $combination) {
                            $str = '';
                            foreach ($combination as $key => $comb) {

                                if ($key > 0) {
                                    $str .= '-' . str_replace(' ', '', $comb);
                                } else {

                                    $str .= str_replace(' ', '', $comb);
                                }
                            }

                            $product_stock = ProductStock::where('product_id', $productId->id)->where('variant', $str)->first();
                            if ($product_stock == null) {
                                $product_stock = new ProductStock;
                                $product_stock->product_id = $productId->id;
                            }
                            if ($product_option['unit_price'] > 0) {
                                $price = $product_option['unit_price'];
                            } else {
                                $price = 0;
                            }

                            if ($product_option['current_stock'] > 0) {
                                $inventory_quantity = $product_option['current_stock'];
                            } else {
                                $inventory_quantity = 100;
                            }

                            $product_stock->variant = $str;
                            $product_stock->price = (float)$product_option['unit_price'];
                            $product_stock->sku = $product_option['sku'];
                            $product_stock->qty = $inventory_quantity;
                            // $product_stock->image = $request['img_' . str_replace('.', '_', $str)];
                            $product_stock->value = $comb;
                            $product_stock->title = $product_option['name'];


                            $product_stock->save();
                        }
                    }
                    //combinations end
                }

                $lang = 'en';

                if ($lang != 'en' || $lang != 'de') {
                    $lang = 'en';
                }


                // Product Translations
                $product_translation = ProductTranslation::firstOrNew(['lang' => $lang, 'product_id' => $productId->id]);
                $product_translation->name = $row['name'];
                // $product_translation->unit = $request->unit;
                $product_translation->description = $row['description'];
                $product_translation->save();
            }

            flash(translate('CSV imported successfully'))->success();
        }
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
            $upload->user_id = Auth::user()->id;
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
