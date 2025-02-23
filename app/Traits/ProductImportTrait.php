<?php

namespace App\Traits;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductDescription;
use App\Models\ProductStock;
use App\Models\ProductTranslation;
use Combinations;
use Storage;
use Str;

trait ProductImportTrait
{

    public function import($product, $user_id)
    {
        $extraImgs = [];
        $mainImage = null;


        $new_product = Product::firstOrNew(['name' => $product->title, 'user_id' => $user_id]);

        if ($new_product->id == null) {

            // download images
            try {
                //  get main image
                $extension = pathinfo(strtok($product->image->src, '?'), PATHINFO_EXTENSION);
                $filename = 'products/' . Str::random(5) . '.' . $extension;
                $file = file_get_contents($product->image->src);
                $mainImagepath = Storage::disk('s3')->put("$filename", $file);
                $mainImage = Storage::disk('s3')->url($filename);
            } catch (\Throwable $th) {
                //throw $th;
            }

            try {
                foreach ($product->images as $key => $img) {
                    $extension = pathinfo(strtok($img->src, '?'), PATHINFO_EXTENSION);
                    $filename = 'products/' . Str::random(5) . '.' . $extension;
                    $file = file_get_contents($img->src);
                    $path = Storage::disk('s3')->put($filename, $file);
                    $path = Storage::disk('s3')->url($filename);
                    $images[] = $path;
                }
                if (count($images) > 0) {
                    $extraImgs = implode(", ", $images);
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        if ($new_product->slug == null) {
            // set slug
            $slug = Str::slug($product->title, '-');

            $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
            $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
            $slug .= $slug_suffix;
            $new_product->slug = $slug;
        }

        $ProductAtts = $product->options;
        $attributeIDs = [];
        $values = [];

        // check if atts and values exist and create them
        foreach ($ProductAtts as $key => $ProductAtt) {
            if ($ProductAtt->name == 'Size' || $ProductAtt->name == 'Color') {

                $attribute = Attribute::firstOrNew(['name' => $ProductAtt->name]);
                $attribute->save();
                $attributeIDs[] = $attribute->id;

                foreach ($ProductAtt->values as $key => $value) {
                    $val = AttributeValue::firstOrNew(['attribute_id' => $attribute->id, 'value' => $value]);
                    $val->save();
                }
                $values[$attribute->id] = $ProductAtt->values;
            }
        }

        //  get variant
        $productVariants = $product->variants;
        $price = 0;
        if ($productVariants[0]->price > 0) {
            $price = $productVariants[0]->price;
        }
        $images = [];

        // variant
        $new_product->attributes = json_encode($attributeIDs);
        $choice_options = [];
        $choices = $attributeIDs;
        $options = $values;

        foreach ($choices as $key => $no) {
            $item['attribute_id'] = $no;

            $data = array();
            $item['values'] = $options[$no];
            array_push($choice_options, $item);
        }


        if (!empty($choice_options)) {
            $new_product->attributes = json_encode($choices);
        } else {
            $new_product->attributes = json_encode(array());
        }

        $new_product->choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);
        $new_product->save();

        $new_product->name = $product->title;
        $new_product->added_by = 'seller';
        $new_product->published = 0;
        $new_product->approved = 1;

        $new_product->unit_price = $price;
        $new_product->description = $product->body_html;

        if (!$new_product->photos) {
            $new_product->photos = json_encode($extraImgs);
        }
        if (!$new_product->thumbnail_img) {
            $new_product->thumbnail_img = $mainImage;
        }

        $new_product->source = 'shopify';
        $new_product->collection_id = $new_product->collection_id ??= 0;

        $new_product->save();

        // create variations
        $product_id = $new_product->id;

        $this->create_variations($productVariants, $options, $product, $product_id);

        // product translation
        $this->product_translation($product_id, $product->title, $product->body_html);
    }

    public function woocomerce_import($product, $user_id, $variations)
    {
        $extraImgs = [];
        $mainImage = null;



        $new_product = Product::firstOrNew(['name' => $product->name, 'user_id' => $user_id]);

        if ($new_product->id == null) {

            // download images
            try {
                //  get main image
                $extension = pathinfo(strtok($product->images[0]->src, '?'), PATHINFO_EXTENSION);
                $filename = 'products/' . Str::random(5) . '.' . $extension;
                $file = file_get_contents($product->images[0]->src);
                $mainImagepath = Storage::disk('s3')->put("$filename", $file);
                $mainImage = Storage::disk('s3')->url($filename);

                //remove main image from gallary
                unset($product->images[0]);
            } catch (\Throwable $th) {
                //throw $th;
            }

            try {
                foreach ($product->images as $key => $img) {
                    $extension = pathinfo(strtok($img->src, '?'), PATHINFO_EXTENSION);
                    $filename = 'products/' . Str::random(5) . '.' . $extension;
                    $file = file_get_contents($img->src);
                    $path = Storage::disk('s3')->put($filename, $file);
                    $path = Storage::disk('s3')->url($filename);
                    $images[] = $path;
                }
                if (count($images) > 0) {
                    $extraImgs = implode(", ", $images);
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        if ($new_product->slug == null) {
            // set slug
            $slug = Str::slug($product->name, '-');

            $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
            $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
            $slug .= $slug_suffix;
            $new_product->slug = $slug;
        }


        $ProductAtts = $product->attributes;
        $attributeIDs = [];
        $values = [];

        // check if atts and values exist and create them
        foreach ($ProductAtts as $key => $ProductAtt) {

            $attribute = Attribute::firstOrNew(['name' => $ProductAtt->name]);
            $attribute->save();
            $attributeIDs[] = $attribute->id;

            foreach ($ProductAtt->options as $key => $value) {
                $val = AttributeValue::firstOrNew(['attribute_id' => $attribute->id, 'value' => $value]);
                $val->save();
            }
            $values[$attribute->id] = $ProductAtt->options;
        }

        $productVariants = [];
        $price = 0;

        if ($variations != null) {
            //  get variant
            $productVariants = $variations;
            if ($productVariants[0]->price > 0) {
                $price = $productVariants[0]->price;
            }
            foreach ($productVariants as $key => $productVariant) {
                $productVariant->inventory_item_id = $productVariant->id;
                $productVariant->inventory_quantity = $productVariant->stock_quantity;
                $productVariant->title = $product->name;
            }
        }
        //simple product
        else {
            $price = $product->price;
            $new_product->product_type = 1;
            $productVariants[] = (object) ['price' => $price];
        }

        $images = [];

        // variant
        $new_product->attributes = json_encode($attributeIDs);
        $choice_options = [];
        $choices = $attributeIDs;
        $options = $values;

        foreach ($choices as $key => $no) {
            $item['attribute_id'] = $no;

            $data = array();
            $item['values'] = $options[$no];
            array_push($choice_options, $item);
        }


        if (!empty($choice_options)) {
            $new_product->attributes = json_encode($choices);
        } else {
            $new_product->attributes = json_encode(array());
        }

        $new_product->choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);
        $new_product->save();

        $new_product->name = $product->name;
        $new_product->added_by = 'seller';
        $new_product->published = 0;
        $new_product->approved = 1;

        $new_product->unit_price = $price;


        if ($product->tags != []) {
            $tags = [];
            foreach ($product->tags as $key => $product_tag) {
                $tags[] = $product_tag->name;
            }
            $new_product->tags = implode(',', $tags);
        }
        if (!$new_product->photos) {
            $new_product->photos = json_encode($extraImgs);
        }
        if (!$new_product->thumbnail_img) {
            $new_product->thumbnail_img = $mainImage;
        }

        $new_product->source = 'woocomerce';
        $new_product->collection_id = $new_product->collection_id ??= 0;

        $new_product->save();


        // create variations
        $product_id = $new_product->id;

        $this->create_variations_woocomerce($productVariants, $options, $product, $product_id);

        // product translation
        $this->product_translation($product_id, $product->name, $product->description);
    }


    public function create_variations($productVariants, $options, $product, $product_id)
    {
        $price = 0;
        if ($productVariants[0]->price > 0) {
            $price = $productVariants[0]->price;
        }

        $combinations = Combinations::makeCombinations($options);

        foreach ($productVariants as $key => $productVariant) {

            $str = '';

            if (count($combinations[0]) > 0) {
                $product->variant_product = 1;
                foreach ($combinations as $key => $combination) {
                    $str = '';
                    foreach ($combination as $key => $comb) {

                        if ($key > 0) {
                            $str .= ' ' . str_replace(' ', ' ', $comb);
                        } else {
                            $str .= str_replace(' ', ' ', $comb);
                        }
                    }

                    $product_stock = ProductStock::where('product_id', $product_id)->where('variant', $str)->first();
                    if ($product_stock == null) {
                        $product_stock = new ProductStock;
                        $product_stock->product_id = $product_id;
                    }
                    if ($productVariant->price > 0) {
                        $price = $productVariant->price;
                    } else {
                        $price = 0;
                    }


                    if ($productVariant->inventory_quantity > 0) {
                        $inventory_quantity = $productVariant->inventory_quantity;
                    } else {
                        $inventory_quantity = 100;
                    }

                    $product_stock->variant = $str;
                    $product_stock->price = floatval($price);
                    $product_stock->sku = $productVariant->sku ? $productVariant->sku : $str;
                    $product_stock->qty = $inventory_quantity;
                    $product_stock->value = $comb;
                    $product_stock->title = $productVariant->title;
                    $product_stock->inventory_item_id = $productVariant->inventory_item_id;

                    $product_stock->save();
                }
            } else {
                $product_stock = new ProductStock;
                $product_stock->product_id = $product_id;
                $product_stock->variant = '';
                $product_stock->price = $price;
                $product_stock->sku = '';
                $product_stock->qty = 100;
                $product_stock->save();
            }
            //combinations end
        }
    }

    public function create_variations_woocomerce($productVariants, $options, $product, $product_id)
    {
        if ($options) {
            foreach ($productVariants as $key => $productVariant) {
                $str = '';
                foreach ($productVariant->attributes as $key => $attr) {
                    if ($key > 0) {
                        $str .= ' ' . str_replace(' ', ' ', $attr->option);
                    } else {
                        $str .= str_replace(' ', ' ', $attr->option);
                    }
                }

                $product_stock = ProductStock::where('product_id', $product_id)->where('variant', $str)->first();
                if ($product_stock == null) {
                    $product_stock = new ProductStock;
                    $product_stock->product_id = $product_id;
                }
                if ($productVariant->price > 0) {
                    $price = $productVariant->price;
                } else {
                    $price = 0;
                }


                if ($productVariant->inventory_quantity > 0) {
                    $inventory_quantity = $productVariant->inventory_quantity;
                } else {
                    $inventory_quantity = 100;
                }

                $product_stock->variant = $str;
                $product_stock->price = floatval($price);
                $product_stock->sku = $productVariant->sku ? $productVariant->sku : $str;
                $product_stock->qty = $inventory_quantity;
                $product_stock->value = $str;
                $product_stock->title = $productVariant->title;
                $product_stock->inventory_item_id = $productVariant->inventory_item_id;

                $product_stock->save();
            }
        } else {
            $product_stock = new ProductStock;
            $product_stock->product_id = $product_id;
            $product_stock->variant = '';
            $product_stock->price = $product->price;
            $product_stock->sku = '';
            $product_stock->qty = 100;
            $product_stock->save();
        }
    }

    // Product Translations

    public function product_translation($product_id, $product_title, $product_desc)
    {
        $product_translation = ProductTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'product_id' => $product_id]);
        $product_translation->name = $product_title;
        $product_translation->description = $product_desc;
        $product_translation->save();
    }

    //Generates the combinations of customer choice options

    public function downloadThumbnail($url, $user_id)
    {

        try {
            $extension = pathinfo(strtok($url, '?'), PATHINFO_EXTENSION);
            $filename = 'uploads/products/' . Str::random(5) . '.' . $extension;
            $fullpath = 'public/' . $filename;
            $file = file_get_contents($url);

            file_put_contents($fullpath, $file);

            $s3 = Storage::disk('s3');
            $path = $s3->put($filename, file_get_contents(base_path($fullpath)));
            unlink(base_path($fullpath));

            return $path;
        } catch (\Exception $e) {
            // dd($e);
        }
        return null;
    }
}
