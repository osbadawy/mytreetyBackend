<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductRanking;
use App\Models\Sustainability;
use App\Traits\SustainabilityRankingTrait;
use Artisan;
use Cache;
use DB;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    use SustainabilityRankingTrait;

    public function admin_dashboard(Request $request)
    {

        $root_categories = Category::where('level', 0)->get();

        $cached_graph_data = Cache::remember('cached_graph_data', 86400, function () use ($root_categories) {
            $num_of_sale_data = null;
            $qty_data = null;
            foreach ($root_categories as $key => $category) {
                $category_ids = \App\Utility\CategoryUtility::children_ids($category->id);
                $category_ids[] = $category->id;

                $products = Product::with('stocks')->whereIn('category_id', $category_ids)->get();
                $qty = 0;
                $sale = 0;
                foreach ($products as  $product) {
                    $sale += $product->num_of_sale;
                    foreach ($product->stocks as $stock) {
                        $qty += $stock->qty;
                    }
                }
                $qty_data .= $qty . ',';
                $num_of_sale_data .= $sale . ',';
            }
            $item['num_of_sale_data'] = $num_of_sale_data;
            $item['qty_data'] = $qty_data;

            return $item;
        });

        return view('backend.dashboard', compact('root_categories', 'cached_graph_data'));
    }

    function clearCache(Request $request): RedirectResponse
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        flash(translate('Cache cleared successfully'))->success();
        return back();
    }

    public function refreshProducts(Request $request)
    {

        $products=filter_products(Product::all());
        $website_url=env('FRONTEND_URL');
        $revalidate_secret=env('REVALIDATE_SECRET');

        foreach ($products as $key => $product) {
            $refresh_frontend_url="$website_url/api/revalidate?secret=$revalidate_secret&slug=$product->slug";
            try {
                $response = Http::get($refresh_frontend_url);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        flash(translate('Products cache refreshed successfully'))->success();
        return back();
    }

    public function refreshRanking(Request $request)
    {
        $products=Product::all();

        foreach ($products as $key => $product) {

            // check if the product dont have ranking, create it
            $prod_ranking = ProductRanking::firstOrCreate(
                ['product_id' =>  $product->id]
            );

        }

        //update all ranking score
        $this->calculateScoreAll();

        //update icon ranking for new verified icons
        $rankings = ProductRanking::where('icon_calculated', 0)->get();

        foreach ($rankings as $key => $ranking) {
            $product_id = $ranking->product_id;

            $verified_icons = DB::table('product_sustainability')->where('product_id', $product_id)->where('is_verified', 1)->get();
            foreach ($verified_icons as $verified_icon) {
                $icon = Sustainability::find($verified_icon->sustainability_id);

                if ($icon->sourcing) {
                    $ranking->sourcing_score = $ranking->sourcing_score - $ranking->sourcing_score * $icon->emisson_reduction;
                }
                if ($icon->manufacturing) {
                    $ranking->manufacturing_score = $ranking->manufacturing_score - $ranking->manufacturing_score * $icon->emisson_reduction;
                }
                if ($icon->packaging) {
                    $ranking->packaging_score = $ranking->packaging_score - $ranking->packaging_score * $icon->emisson_reduction;
                }
                if ($icon->shipping) {

                    $ranking->shipping_score = $ranking->shipping_score - $ranking->shipping_score * $icon->emisson_reduction;
                }
                if ($icon->use) {
                    $ranking->use_score = $ranking->emisson_reduction - $ranking->use_score * $icon->emisson_reduction;
                }
                if ($icon->end_of_life) {
                    $ranking->end_of_life_score = $ranking->end_of_life_score - $ranking->end_of_life_score * $icon->emisson_reduction;
                }
                $ranking->icon_calculated = 1;
                $ranking->save();
            }
        }

        //update all ranking level
        $this->calculateLevelAll();

        flash(translate('Ranking refreshed successfully'))->success();
        return back();


    }

    public function refreshPublish(Request $request)
    {
        $products=Product::all();

        //fix active and inactive products
        foreach ($products as $key => $product) {

            $product->published = 0;
            // If the product has a category and collection ID, set the published status to 1
            if ($product->category_id > 0 && $product->collection_id > 0){
                $product->published = 1;
            }

            // If the product has collection_id null set it to 0
            if ($product->collection_id == null) $product->collection_id = 0;

            // Save the updated product
            $product->save();

        }

        flash(translate('Products publish refreshed successfully'))->success();
        return back();
    }
}
