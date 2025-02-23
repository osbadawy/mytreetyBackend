<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Requests\ShopifySyncRequest;
use App\Jobs\ShopifySync;
use App\Models\Seller;
use App\Traits\ApiUpload;
use App\Traits\ConsumesExternalServices;
use Auth;
use Illuminate\Http\JsonResponse;

class ShopifySyncController extends Controller
{
    use ApiUpload,ConsumesExternalServices;


    /**
     * @param ShopifySyncRequest $request
     * @return JsonResponse
     */
    public function sync(ShopifySyncRequest $request): JsonResponse
    {

        $shopify_apikey = $request->shopify_apikey;
        $shopify_password = $request->shopify_password;
        $shopify_ver = '2022-01';
        $shopify_accessToken = $request->shopify_accessToken;
        $seller = Auth::user()->seller;
        $user_id = Auth::user()->id;

        //Remove the protocol from the shopify url
        $shopify_url = preg_replace('#^https?://#', '', $request->shopify_url);

        //Save seller credentials
        $this->saveCredentials($shopify_apikey, $seller, $shopify_password, $shopify_url, $shopify_ver, $shopify_accessToken);

        //Get shopify products
        $shopify_products = $this->getAllProducts($shopify_apikey, $shopify_password, $shopify_url, $shopify_ver, $shopify_accessToken);

        //Return if credentials is wrong
        if ($shopify_products == -1) return response()->json(['message' => translate('Please verify your credentials and try again'), 'success' => false, 'status' => 400], 400);

        //Return if no products found
        if (!$shopify_products) return response()->json(['message' => translate('No Products found in the shopify store'), 'success' => false, 'status' => 400], 400);

        //Dispatch products to jobs
        $this->dispatchProducts($shopify_products, $user_id);

        return response()->json(['message' => translate('Shopify is syncing in the background'), 'success' => true, 'status' => 201], 201);

    }


    /**
     * @param $shopify_apikey
     * @param Seller|null $seller
     * @param $shopify_password
     * @param $shopify_url
     * @param string $shopify_ver
     * @param $shopify_accessToken
     * @return void
     */
    public function saveCredentials($shopify_apikey, ?Seller $seller, $shopify_password, $shopify_url, string $shopify_ver, $shopify_accessToken): void
    {
        $seller->shopify_apikey = $shopify_apikey;
        $seller->shopify_password = $shopify_password;
        $seller->shopify_url = $shopify_url;
        $seller->shopify_ver = $shopify_ver;
        $seller->shopify_accessToken = $shopify_accessToken;
        $seller->update();
    }


    /**
     * @param $api_key
     * @param $password
     * @param $url
     * @param $version
     * @param $accessToken
     * @return null | JsonResponse
     */
    public function getAllProducts($api_key, $password, $url, $version, $accessToken)
    {

        try {
            //API URL to get all products
            $requestURL = "https://" . $api_key . ":" . $password . "@" . $url;
            $URL = "/admin/api/" . $version . "/products.json";
            $headers = ['X-Shopify-Access-Token' => $accessToken];

            $response = $this->makeRequest($requestURL, 'get', $URL, null, null, $headers);
            $products = json_decode($response)->products;

        } catch (\Throwable $th) {
            $products = null;
        }

        return $products;
    }

    /**
     * @param  $shopify_products
     * @param $user_id
     * @return void
     */
    public function dispatchProducts($shopify_products, $user_id): void
    {
        $chunk_no = 10;

        $chunks = array_chunk($shopify_products, $chunk_no);

        foreach ($chunks as $key => $chunk) {
            dispatch(new ShopifySync($chunk, $user_id));
        }
    }

    /**
     * @param $seller_id
     * @param $inventory_item_id
     * @param $qts
     * @return string|null
     */
    public function UpdateStock($seller_id, $inventory_item_id, $qts): ?string
    {
        // get api keys from seller
        $seller = Seller::where('user_id', $seller_id)->first();
        $shopify_url = $seller->shopify_url;
        $shopify_ver = $seller->shopify_ver;
        $shopify_accessToken = $seller->shopify_accessToken;
        $updated = null;

        // get location_id
        $requestURL = "https://$shopify_url";
        $URL = "/admin/api/" . $shopify_ver . "/locations.json";
        $headers = ['X-Shopify-Access-Token' => $shopify_accessToken];

        $locations = $this->makeRequest($requestURL, 'get', $URL, null, null, $headers);

        $location_id = 0;
        if ($locations) {
            $locations_data = json_decode($locations);
            if (!empty($locations_data->locations)) {
                $location_id = $locations_data->locations[0]->id;
            }
        }

        // Update stock
        if ($location_id > 0) {
            $requestURL = "https://$shopify_url";
            $URL = "/admin/api/" . $shopify_ver . "/inventory_levels/adjust.json";
            $params = ['inventory_item_id' => $inventory_item_id, 'location_id' => $location_id, 'available_adjustment' => $qts, 'relocate_if_necessary' => true];

            $updated = $this->makeRequest($requestURL, 'post', $URL, null, $params, $headers);
        }

        return $updated;
    }

    /**
     * @param $seller_id
     * @return void
     */
    public function SellerSync($seller_id)
    {
        $seller = Seller::find($seller_id);
        $shopify_apikey = $seller->shopify_apikey;
        $shopify_password = $seller->shopify_password;
        $shopify_url = $seller->shopify_url;
        $shopify_ver = $seller->shopify_ver;
        $shopify_accessToken = $seller->shopify_accessToken;

        $shopify_products = $this->getAllProducts($shopify_apikey, $shopify_password, $shopify_url, $shopify_ver, $shopify_accessToken);

        $user_id = $seller->user_id;

        if($shopify_products){
            $this->dispatchProducts($shopify_products, $user_id);
        }
    }
}
