<?php

namespace App\Models;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection()
    {
        return Product::all();
    }

    public function headings(): array
    {
        return [
            'name',
            'added_by',
            'category',
            'unit_price',
            'current_stock',
            'image',
            'num_of_sale',
            'published',
            'source',
            'sustainability_rank',
            'est_shipping_days',
            'choice_options',
            'tags',
            'variant_product',
            'has_collection'


        ];
    }

    /**
     * @var Product $product
     */
    public function map($product): array
    {
        $qty = 0;
        foreach ($product->stocks as $key => $stock) {
            $qty += $stock->qty;
        }
        $is_published = 'no';

        if ($product->published == 1) {
            $is_published = 'yes';
        }

        $variant_product = 'no';

        if ($product->product_type == 0) {
            $variant_product = 'yes';
        }

        $choice_options = array();

        foreach (json_decode($product->choice_options) as $key => $choice) {
            $title = Attribute::find($choice->attribute_id)->getTranslation('name');
            $options = $choice->values;
            // $item['name'] = $choice->attribute_id;
            $item['title'] = preg_replace('/(\v|\s)+/', ' ', $title);
            $item['options'] = preg_replace('/(\v|\s)+/', ' ', $options);
            $choice_options[] = $item;
        }

        $product_category='none';
        if($product->category){
            $product_category= $product->category->name;
        }

        $product_vendor='none';
        if($product->user){
            $product_vendor= $product->user->name;
        }


        $product_source='manual';
        if($product->source){
            $product_source=$product->source;
        }

        $product_collection='no';
        if($product->collection_id > 0){
            $product_collection='yes';
        }

        return [
            $product->name,
            $product_vendor,
            $product_category,
            $product->unit_price,
            $qty,
            $product->thumbnail_img,
            $product->num_of_sale,
            $is_published,
            $product_source,
            $product->sustainability_rank,
            $product->est_shipping_days,
            $choice_options,
            $product->tags,
            $variant_product,
            $product_collection

        ];
    }
}


function convertToChoiceOptions($data): array
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
