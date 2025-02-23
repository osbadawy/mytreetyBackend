<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Requests\WoocomerceUploadRequest;
use App\Jobs\WoocomerceSync;
use App\Models\Seller;
use App\Models\Upload;
use App\Traits\ProductImportTrait;
use Automattic\WooCommerce\Client;

use Storage;
use Str;

class WoocomerceSyncController extends Controller
{
    use ProductImportTrait;

    public function sync(WoocomerceUploadRequest $request)
    {
        $woocommerce_url = $request->woocommerce_url;
        $consumer_key = $request->woocommerce_consumer_key;
        $consumer_secret = $request->woocommerce_consumer_secret;
        $user_id = $request->user()->id;
        $seller = $request->user()->seller;


        $woocommerce = new Client(
            $woocommerce_url,
            $consumer_key,
            $consumer_secret,
            [
                'wp_api' => true,
                'version' => 'wc/v3'
            ]
        );

        //Save seller credentials
        $this->saveCredentials($woocommerce_url,$seller, $consumer_key, $consumer_secret);


        $woocomerce_products = collect([]);
        $max_products=500;

        //Get products from woocomerce
        try {
            $woocomerce_products = collect($woocommerce->get('products',['per_page'=>$max_products , 'status'=>'publish']));
        } catch (\Throwable $th) {
            return response()->json(['error'=> $th,'message' => translate('Please verify your credentials and try again'), 'success' => false, 'status' => $th->getCode()], 400);
        }

        //Dispatch products to jobs
        $this->dispatchProducts($woocomerce_products, $user_id, $woocommerce);


        return response()->json(['message' => translate('Woocommerce is syncing in the background'), 'success' => true, 'status' => 201], 201);
    }


    /**
     * @param  $woocomerce_products
     * @param $user_id
     * @return void
     */
    public function dispatchProducts($woocomerce_products, $user_id, $woocommerce): void
    {
        $chunk_no = 10;


        $chunks = $woocomerce_products->chunk($chunk_no);

        foreach ($chunks as $key => $chunk) {
            dispatch(new WoocomerceSync($chunk, $user_id, $woocommerce));
        }
    }


    public function saveCredentials($woocommerce_url,?Seller $seller,$consumer_key, $consumer_secret): void
    {
        $seller->woocommerce_url = $woocommerce_url;
        $seller->woocommerce_consumer_key = $consumer_key;
        $seller->woocommerce_consumer_secret = $consumer_secret;
        $seller->update();
    }
}
