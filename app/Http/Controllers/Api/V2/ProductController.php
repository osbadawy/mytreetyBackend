<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\ProductDetailCollection;
use App\Http\Resources\V2\ProductMiniCollection;
use App\Models\Category;
use App\Models\Product;
use App\Utility\CategoryUtility;
use App\Utility\SearchUtility;
use Cache;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductController extends Controller
{


    /**
     * @param $slug
     * @return ProductDetailCollection
     */
    public function show($slug): ProductDetailCollection
    {
        //Get product details by slug
        return new ProductDetailCollection(Product::where('slug', $slug)->where('published', 1)->where('approved', 1)->get());
    }


    /**
     * @param Request $request
     * @param $slug
     * @return ProductDetailCollection
     */
    public function VendorProductShow(Request $request, $slug): ProductDetailCollection
    {
        //Get vendor product details by slug
        return new ProductDetailCollection(Product::where('slug', $slug)->where('user_id', $request->user()->id)->get());
    }


    public function bestSeller()
    {
        //Get best seller products
        return Cache::remember('app.best_selling_products', 86400, function () {
            $products = Product::orderBy('num_of_sale', 'desc');
            return new ProductMiniCollection(filter_products($products)->limit(20)->get());
        });
    }

    public function newarrival()
    {
        //Get new arrival products
        return Cache::remember('app.new_arrival_products', 86400, function () {
            $products = Product::orderBy('created_at', 'desc');
            return new ProductMiniCollection(filter_products($products)->limit(20)->get());
        });
    }

    public function lowprice()
    {
        //Get low price products
        return Cache::remember('app.low_price_products', 86400, function () {
            $products = Product::where('unit_price', '<', 50)->inRandomOrder();

            foreach ($products->get() as $key => $product) {
                if ($product->unit_price == null || $product->unit_price == 0) {
                    if ($product->stocks) {
                        $product->unit_price = $product->stocks[0]->price;
                        $product->save();
                    }
                }
            }

            return new ProductMiniCollection(filter_products($products)->limit(20)->get());
        });
    }

    public function mostshared()
    {
        // Get most shared products
        return Cache::remember('app.most_shared_products', 86400, function () {
            $products_ids = DB::table('referral_products_count')->orderBy('count', 'desc')->select('product_id')->get()->pluck('product_id')->toArray();
            $productsCount = count($products_ids);

            // Add random products if the count is less than 5
            if ($productsCount < 5) {
                $randomProducts = Product::where('published', 1)->inRandomOrder()->limit(5 - $productsCount)->get();
                foreach ($randomProducts as $product) {
                    $products_ids[] = $product->id;
                }
            }

            $products = Product::whereIn('id', $products_ids);
            return new ProductMiniCollection(filter_products($products)->limit(20)->get());
        });
    }



    public function bestSustainable()
    {
        //Get best sustainable products
        return Cache::remember('app.best_sustainable_products', 86400, function () {
            $products = filter_products(Product::orderBy('sustainability_rank', 'desc')->groupBy('user_id'))->latest()->limit(20)->get();
            $all_products = $products;

            if ($products->count() < 3) {
                $products_ids = $products->pluck('id')->toArray();
                $new_products = filter_products(Product::whereNotIn('id', $products_ids)->groupBy('category_id')->orderBy('sustainability_rank', 'desc'))->get();
                $all_products = $products->merge($new_products);
            }
            return new ProductMiniCollection($all_products);
        });
    }


    public function related($id)
    {
        //Get related products by product id
        $product = Product::where('id', $id)->first();
        if ($product) {
            return Cache::remember("app.related_products-$id", 86400, function () use ($product) {
                $products = Product::where('category_id', $product->category_id)->where('id', '!=', $product->id);
                return new ProductMiniCollection(filter_products($products)->limit(10)->get());
            });
        } else {
            return response()->json(['result' => false, 'message' => translate('Product Not Found')], 400);
        }
    }



    public function search(Request $request): ProductMiniCollection
    {
        $category_ids = [];
        $brand_ids = null;
        $category_slug = $request->category;
        $sustainability_ranking = null;
        $type = $request->type;
        $sustainability_icons = null;
        $sort_by = $request->sort_by;
        $name = $request->s;
        $min = $request->min;
        $max = $request->max;

        //Set category ids
        if ($request->categories != null && $request->categories != "") {
            $category_ids = explode(',', $request->categories);
        }

        //Set brand_ids ids
        if ($request->brand_ids != null) {
            $brand_ids = explode(',', $request->brand_ids);
        }

        //Set sustainability_ranking ids
        if ($request->sustainability_ranking != null) {
            $sustainability_ranking = explode(',', $request->sustainability_ranking);
        }


        //Set sustainability icons
        if ($request->sustainability_icons != null && $request->sustainability_icons != "" && $request->sustainability_icons != "null") {
            $sustainability_icons = explode(',', $request->sustainability_icons);
        }

        //Prepare products query
        $products = Product::query();

        //Get all published products
        $products->where('published', 1);

        //Filter by vendor
        $this->filterByVendor($products, $brand_ids);

        //Filter by sustainability ranking
        $this->filterByRank($sustainability_ranking, $products);

        //Filter by category
        $this->filterByCategory($category_ids, $products, $category_slug);

        //Filter by name
        $this->filterByName($products, $name);

        //Filter by price
        $this->filterByPrice($min, $products, $max);

        //Filter by icons
        if ($sustainability_icons) $this->filterByIcon($sustainability_icons, $products);

        //Filter by type
        if ($type != null && $type != 'null') $this->filterByType($type, $products);

        //Sort products
        $this->sortProducts($sort_by, $products);

        return new ProductMiniCollection(filter_products($products)->paginate(12));
    }

    /**
     * @param $brand_ids
     * @param Builder $products
     * @param $brand_id
     * @return void
     */
    public function filterByVendor(Builder $products, $brand_ids): void
    {
        if (!empty($brand_ids)) {
            $products->whereIn('user_id', $brand_ids);
        }
    }

    /**
     * @param $sustainability_ranking
     * @param Builder $products
     * @return void
     */
    public function filterByRank($sustainability_ranking, Builder $products): void
    {
        if (!empty($sustainability_ranking)) {
            $products->whereIn('sustainability_rank', $sustainability_ranking);
        }
    }

    /**
     * @param $category_ids
     * @param Builder $products
     * @param $category_slug
     * @return void
     */
    public function filterByCategory($category_ids, Builder $products, $category_slug): void
    {
        $men_category_id = 1;
        $women_category_id = 2;
        $unisex_category_id = 98;

        if (!empty($category_ids)) {
            $n_cid = [];
            foreach ($category_ids as $cid) {
                $category_name = Category::find($cid)->name;
                $categories_allies = Category::where('parent_id', CategoryUtility::children_ids($unisex_category_id))->where('name', 'like', '%' . $category_name . '%')->pluck('id')->toArray();

                if (in_array($cid, [$men_category_id, $women_category_id])) {
                    // add the ID for "Unisex" category and its subcategories
                    $n_cid = array_merge($n_cid, CategoryUtility::children_ids($unisex_category_id));
                }
                $n_cid = array_merge($n_cid, $categories_allies);


                $n_cid = array_merge($n_cid, CategoryUtility::children_ids($cid));
            }

            if (!empty($n_cid)) {
                $category_ids = array_merge($category_ids, $n_cid);
            }

            $products->whereIn('category_id', $category_ids);
        }

        if (!empty($category_slug)) {
            $n_cid = [];
            $cat = Category::where('slug', $category_slug)->first();

            if ($cat) {
                $category_id = $cat->id;
                $category_name = $cat->name;
                $categories_allies = Category::where('parent_id', CategoryUtility::children_ids($unisex_category_id))->where('name', 'like', '%' . $category_name . '%')->pluck('id')->toArray();

                $n_cid[] = $category_id;

                if (in_array($category_id, [$men_category_id, $women_category_id])) {
                    // add the ID for "Unisex" category and its subcategories
                    $n_cid = array_merge($n_cid, CategoryUtility::children_ids($unisex_category_id));
                }

                $n_cid = array_merge($n_cid, CategoryUtility::children_ids($category_id));
                $n_cid = array_merge($n_cid, $categories_allies);

                if (!empty($n_cid)) {
                    $category_ids = array_merge($category_ids, $n_cid);
                }
            }

            $products->whereIn('category_id', $category_ids);
        }
    }

    /**
     * @param Builder $products
     * @param $name
     * @return void
     */
    public function filterByName(Builder $products, $name): void
    {
        if ($name != null && $name != "") {

            $products->where(function ($query) use ($name) {
                foreach (explode(' ', trim($name)) as $word) {
                    $query->where('name', 'like', '%' . $word . '%')->orWhere('tags', 'like', '%' . $word . '%')->orWhereHas('product_translations', function ($query) use ($word) {
                        $query->where('name', 'like', '%' . $word . '%');
                    });
                }
            });
            SearchUtility::store($name);
        }
    }

    /**
     * @param $min
     * @param Builder $products
     * @param $max
     * @return void
     */
    public function filterByPrice($min, Builder $products, $max): void
    {
        if ($min != null && $min != "" && is_numeric($min)) {
            $products->where('unit_price', '>=', $min);
        }

        if ($max != null && $max != "" && is_numeric($max)) {
            $products->where('unit_price', '<=', $max);
        }
    }

    /**
     * @param array $sustainability_icons
     * @param Builder $products
     * @return void
     */
    public function filterByIcon(array $sustainability_icons, Builder $products): void
    {
        $products_ids = DB::table('product_sustainability')->whereIn('sustainability_id', $sustainability_icons)->select('product_id')->get()->toArray();
        $ids = [];
        foreach ($products_ids as $key => $products_id) {
            $ids[] = $products_id->product_id;
        }
        if ($products_ids) $products->whereIn('id', $ids);
    }

    /**
     * @param $type
     * @param Builder $products
     * @return void
     */
    public function filterByType($type, Builder $products): void
    {
        switch ($type) {
            case 'sale':
                $products->where('discount', '!=', 0);
                break;

            case 'best_sustainable':
                $products->orderBy('sustainability_rank', 'desc');
                break;

            case 'best_seller':
                $products->orderBy('num_of_sale', 'desc');
                break;

            case 'low_price':
                $products->where('unit_price', '<', 50)->inRandomOrder();
                break;

            case 'most_shared':
                $products_ids = DB::table('referral_products_count')->orderBy('count', 'desc')->select('product_id')->get()->pluck('product_id')->toArray();
                $productsCount = count($products_ids);

                // Add random products if the count is less than 5
                if ($productsCount < 5) {
                    $randomProducts = $products->where('published', 1)->inRandomOrder()->limit(5 - $productsCount)->get();
                    foreach ($randomProducts as $product) {
                        $products_ids[] = $product->id;
                    }
                }
                $products->whereIn('id', $products_ids);

                break;

            default:
                $products->orderBy('created_at', 'desc');
                break;
        }
    }

    /**
     * @param $sort_by
     * @param Builder $products
     * @return void
     */
    public function sortProducts($sort_by, Builder $products): void
    {
        switch ($sort_by) {
            case 'price_low_to_high':
                $products->orderBy('unit_price', 'asc');
                break;

            case 'price_high_to_low':
                $products->orderBy('unit_price', 'desc');
                break;

            case 'popularity':
                $products->orderBy('num_of_sale', 'desc');
                break;

            case 'top_rated':
                $products->orderBy('rating', 'desc');
                break;

            default:
                $products->orderBy('created_at', 'desc');
                break;
        }
    }
}
