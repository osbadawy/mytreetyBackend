<?php

namespace App\Http\Controllers\Api\V2;


use App\Models\Product;
use App\Models\Search;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchSuggestionController extends Controller
{

    /**
     * @param Request $request
     * @return array
     */
    public function getList(Request $request): array
    {
        $query_key = $request->query_key;
        $type = $request->type;
        $items = [];

        $search_query = Search::select('id', 'query', 'count');
        if ($query_key != "") {
            $search_query->where('query', 'like', "%{$query_key}%");
        }
        $searches = $search_query->orderBy('count', 'desc')->limit(10)->get();

        if ($type == "product") {
            $product_query = Product::query();
            if ($query_key != "") {
                $this->filterByQueryKey($product_query, $query_key);
                $products = filter_products($product_query)->limit(4)->get();
            }
        }

        //product push
        if ($type == "product" && !empty($products)) {
            foreach ($products as $product) $items[] = $this->getProductDetails($product);
        }

        return $items; // return a valid json of search list;
    }

    /**
     * @param Builder $product_query
     * @param $query_key
     * @return void
     */
    public function filterByQueryKey(Builder $product_query, $query_key): void
    {
        $product_query->where(function ($query) use ($query_key) {
            foreach (explode(' ', trim($query_key)) as $word) {
                $query->where('name', 'like', '%' . $word . '%')->orWhere('tags', 'like', '%' . $word . '%')->orWhereHas('product_translations', function ($query) use ($word) {
                    $query->where('name', 'like', '%' . $word . '%');
                });
            }
        });
    }

    /**
     * @param $product
     * @return array
     */
    public function getProductDetails($product): array
    {
        $item = [];

        $thumbnail_image = $product->thumbnail_img;
        if (!$thumbnail_image || is_numeric($thumbnail_image)) $thumbnail_image = 'https://mytreety.s3.eu-central-1.amazonaws.com/products/default.png';

        $item['id'] = $product->id;
        $item['query'] = $product->name;
        $item['thumbnail_img'] = $thumbnail_image;
        $item['slug'] = $product->slug;
        $item['type'] = "product";
        return $item;
    }
}
