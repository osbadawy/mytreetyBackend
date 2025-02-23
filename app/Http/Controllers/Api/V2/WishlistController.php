<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Requests\CreateWishlistRequest;
use App\Http\Resources\V2\WishlistCollection;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{

    /**
     * @param Request $request
     * @return WishlistCollection
     */
    public function index(Request $request): WishlistCollection
    {
        //Get all products in the wishlist
        $product_ids = Wishlist::where('user_id', $request->user()->id)->pluck("product_id")->toArray();
        $existing_product_ids = Product::whereIn('id', $product_ids)->pluck("id")->toArray();

        return new WishlistCollection(Wishlist::where('user_id', $request->user()->id)->whereIn("product_id", $existing_product_ids)->latest()->get());
    }


    /**
     * @param CreateWishlistRequest $request
     * @return JsonResponse
     */
    public function add(CreateWishlistRequest $request): JsonResponse
    {
        //Add/Update products to the wishlist
        foreach ($request->products as $key => $prod) Wishlist::updateOrCreate(['user_id' => $request->user()->id, 'product_id' => $prod['product_id']]);

        return response()->json(['message' => translate('Products added to wishlist')], 200);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function remove($id, Request $request): JsonResponse
    {
        //Check if product exist in the wishlist
        if (Wishlist::where(['id' => $id, 'user_id' => $request->user()->id])->count() == 0) return response()->json(['message' => translate('Product in not in wishlist'),'is_in_wishlist' => false,'wishlist_id' => 0], 200);

        //Remove the product from the wishlist
        Wishlist::where(['id' => $id, 'user_id' => $request->user()->id])->delete();

        return response()->json(['message' => translate('Product is removed from wishlist'),'is_in_wishlist' => false,'wishlist_id' => 0 ], 200);
    }

}
