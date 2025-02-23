<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttachProductRequest;
use App\Http\Requests\CreateCollectionRequest;
use App\Http\Requests\DeAttachProductRequest;
use App\Http\Requests\DestoryCollectionRequest;
use App\Http\Requests\UpdateCollectionRequest;
use App\Http\Resources\V2\ProductCollection;
use App\Models\Collection;
use App\Models\Product;
use App\Models\ProductRanking;
use App\Traits\SustainabilityRankingTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    use SustainabilityRankingTrait;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        //Get all vendor collections
        $collections = Collection::where('user_id', $request->user()->id)->paginate(10);

        //Attach collection products count
        foreach ($collections as $key => $collection) {
            $collection->no_products = $this->countCollectionProducts($request->user()->id, $collection->id);
        }

        return response()->json(['result' => true, 'data' => $collections], 200);
    }

    /**
     * @param $user_id
     * @param $collection_id
     * @return int
     */
    public function countCollectionProducts($user_id, $collection_id): int
    {
        return Product::where('user_id', $user_id)->where('collection_id', $collection_id)->count();
    }

    /**
     * @param CreateCollectionRequest $request
     * @return JsonResponse
     */
    public function store(CreateCollectionRequest $request): JsonResponse
    {
        //Create collection
        $collection_id = $this->createCollection($request->rankingDetails, $request->name, $request->user()->id);

        return response()->json(['result' => true, 'message' => translate('Collection added'), 'collection_id' => $collection_id], 200);
    }

    /**
     * @param AttachProductRequest $request
     * @return JsonResponse
     */
    public function attachProduct(AttachProductRequest $request): JsonResponse
    {

        //Check collection id from request
        if ($request->collection_id) {
            $exist = Collection::where('id', $request->collection_id)->where('user_id', $request->user()->id)->count();

            if (!$exist) {
                return $this->returnIfCollectionNotFound($request->collection_id);
            }
        }

        //Get collection id
        $collection_id = $request->collection_id;

        //Create collection if no existing collection
        if (!$collection_id) {

            $collection_id = $this->createCollection($request->rankingDetails, $request->name, $request->user()->id);
        }


        foreach ($request->product_ids as $key => $product_id) {

            //Check if product exist
            $product = Product::where('id', $product_id)->where('user_id', $request->user()->id)->first();
            if (!$product) {
                return $this->returnIfProductNotFound($product_id);
            }

            //Attach product to selected collection
            $this->attachCollection($product_id, $collection_id);

            //Sync sustainabilities icons
            if ($request->has('sustainabilities')) {
                $product->sustainabilities()->sync($request->sustainabilities);
            }
        }


        return response()->json(['result' => true, 'message' => translate('Products attached successfully')], 200);
    }

    /**
     * @param $collection_id
     * @return JsonResponse
     */
    public function returnIfCollectionNotFound($collection_id): JsonResponse
    {
        return response()->json(['result' => false, 'message' => translate('Collection not found'), 'collection_id' => $collection_id], 400);
    }

    /**
     * @param $product_id
     * @return JsonResponse
     */
    public function returnIfProductNotFound($product_id): JsonResponse
    {
        return response()->json(['result' => false, 'message' => translate('Product not found'), 'product_id' => $product_id], 400);
    }

    /**
     * @param DestoryCollectionRequest $request
     * @return JsonResponse|void
     */
    public function destroy(DestoryCollectionRequest $request)
    {

        //Bulk delete collections
        foreach ($request->collection_ids as $key => $collection_id) {

            //Check if collection exist
            $collection = Collection::where('id', $collection_id)->where('user_id', $request->user()->id)->first();
            if (!$collection) {
                return $this->returnIfCollectionNotFound($collection_id);
            }

            //Unattached products from collection
            $this->unAttachProducts($collection);

            //Delete collection
            $collection->delete();

        }

        return response()->json(['result' => true, 'message' => translate('Collections has been deleted,products ranking will be updated in the background')], 200);
    }

    /**
     * @param $collection
     * @return void
     */
    public function unAttachProducts($collection): void
    {
        $collection_products = Product::where('collection_id', $collection->id)->get();

        foreach ($collection_products as $collection_product) {
            //remove collection link from product
            $collection_product->collection_id = 0;
            $collection_product->update();

            //Reset product ranking
            $this->resetProductRanking($collection_product->id);
        }
    }

    /**
     * @param UpdateCollectionRequest $request
     * @return JsonResponse
     */
    public function update(UpdateCollectionRequest $request): JsonResponse
    {

        //Get ranking details from request

        $ranking_details = $request->rankingDetails;
        $sourcing = $ranking_details['sourcing'];
        $manufacturing = $ranking_details['manufacturing'];
        $packaging = $ranking_details['packaging'];
        $shipping = $ranking_details['shipping'];
        $use = $ranking_details['use'];
        $endOfLife = $ranking_details['endOfLife'];
        $name = $request->name;

        //Check if collection exist
        $collection = Collection::where('id', $request->id)->where('user_id', $request->user()->id)->first();

        if (!$collection) {
            return $this->returnIfCollectionNotFound($request->collection_id);
        }

        //Saving ranking details
        $this->savingRankingDetails($name, $sourcing, $collection, $manufacturing, $packaging, $shipping, $use['amount'], $endOfLife);

        //Reset product ranking
        $collection_products = Product::where('collection_id', $collection->id)->get();
        foreach ($collection_products as $key => $collection_product) {
            $this->resetProductRanking($collection_product->id);
        }

        return response()->json(['result' => true, 'message' => translate('Collection has been updated,products ranking will be updated in the background')], 200);
    }

    /**
     * @param $product_id
     * @return void
     */
    public function resetProductRanking($product_id): void
    {
        $ranking = ProductRanking::firstOrCreate(['product_id' => $product_id]);
        $ranking->is_calculated = 0;
        $ranking->update();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function view(Request $request): JsonResponse
    {
        $collection_products = [];

        //Check if collection exist
        $collection = Collection::where('id', $request->id)->where('user_id', $request->user()->id)->first();
        if (!$collection) {
            return $this->returnIfCollectionNotFound($request->id);
        }

        //Get all products in the collection
        $collection_products = Product::where('user_id', $request->user()->id)->where('collection_id', $collection->id)->get();

        //Add needed attributes to collection
        $collection->no_products = $collection_products->count();
        $collection->collection_products = new ProductCollection($collection_products);


        return response()->json(['result' => true, 'data' => $collection], 200);

    }

    /**
     * @param DeAttachProductRequest $request
     * @return JsonResponse
     */
    public function detachedProduct(DeAttachProductRequest $request): JsonResponse
    {

        //Check if product exist
        $collection_product = Product::where('id', $request->product_id)->where('user_id', $request->user()->id)->first();
        if (!$collection_product) {
            return $this->returnIfProductNotFound($request->product_id);
        }

        //Detached collection from product
        $this->deattachCollectionFromProduct($collection_product);

        //Reset product ranking
        $this->resetProductRanking($collection_product->id);

        return response()->json(['result' => true, 'message' => translate('Product has been detached,products ranking will be updated in the background')], 200);

    }

    /**
     * @param $collection_product
     * @return void
     */
    public function deattachCollectionFromProduct($collection_product): void
    {
        $collection_product->collection_id = null;
        $collection_product->published = 0;
        $collection_product->update();
    }
}
