<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\WoocomerceSyncController;
use App\Http\Requests\AutoXmlUploadRequest;
use App\Http\Requests\CsvUploadRequest;
use App\Http\Requests\ShopwareUploadRequest;
use App\Http\Requests\WoocomerceUploadRequest;
use App\Http\Requests\XmlUploadRequest;
use App\Jobs\CSVSync;
use App\Jobs\XMLSync;
use App\Models\Seller;
use App\Traits\ProductImportTrait;
use Auth;
use Automattic\WooCommerce\Client;
use Illuminate\Http\JsonResponse;

class ProductBulkUploadController extends Controller
{


    use ProductImportTrait;
    /**
     *
     * @param CsvUploadRequest $request
     * @return JsonResponse
     */

    public function bulk_upload(CsvUploadRequest $request): JsonResponse
    {

        //Check vendor verification status
        if (!Auth::user()->seller->verification_status) {
            return response()->json(['message' => translate('Your shop is not verified yet!'), 'success' => false, 'status' => 403], 403);
        }

        $user_id = $request->user()->id;

        //Get file from request
        $file = request()->file('bulk_file');

        //Move Uploaded File
        $destinationPath = "public/uploads/excel/$user_id";
        $filename = $file->getClientOriginalName();
        $file->move($destinationPath, "$filename");

        dispatch(new CSVSync($filename, $user_id));

        return response()->json(['message' => translate('CSV is syncing in the background'), 'success' => true, 'status' => 201], 201);
    }


    /**
     * @param XmlUploadRequest $request
     * @return JsonResponse
     */
    public function bulk_upload_xml(XmlUploadRequest $request): JsonResponse
    {
        $user_id = Auth::user()->id;

        //Get file from request
        $xmlString = file_get_contents($request->bulk_file);

        //Convert xml to json
        $products = $this->convertXmlToJson($xmlString);

        //Check if there are products
        if (count($products) == 0) {
            return response()->json(['message' => translate('No products found in the file'), 'success' => false, 'status' => 400], 400);
        }

        //Dispatch xml sync job to run in the background
        dispatch(new XMLSync($products, $user_id));

        return response()->json(['message' => translate('XML is syncing in the background'), 'success' => true, 'status' => 201], 201);
    }

    /**
     * @param $xmlString
     * @return mixed
     */
    public function convertXmlToJson($xmlString)
    {
        //Convert xml to json
        $xmlObject = simplexml_load_string($xmlString);
        $json = json_encode($xmlObject);

        //Get products from json
        return json_decode($json, true)['Item'];
    }

    /**
     * @param AutoXmlUploadRequest $request
     * @return JsonResponse
     */
    public function bulk_upload_auto_xml(AutoXmlUploadRequest $request): JsonResponse
    {
        //Save XML url to database
        $seller = Auth::user()->seller;
        $seller->xml_file = $request->xml_file;
        $seller->save();

        return response()->json(['message' => translate('XML will be automatically imported everyday at 12:00 AM'), 'success' => true, 'status' => 200], 200);
    }

    public function SellerSync($seller_id)
    {

        $seller = Seller::where('id', $seller_id)->first();
        $file = $seller->xml_file;

        try {
            //Get file from request
            $xmlString = file_get_contents($file);

            //Convert xml to json
            $products = $this->convertXmlToJson($xmlString);

            //Dispatch xml sync job to run in the background

            dispatch(new XMLSync($products, $seller->user_id));
        } catch (\Exception $e) {
            // dd($e);
        }
    }


    /**
     * @param ShopwareUploadRequest $request
     * @return JsonResponse
     */
    public function shopware(ShopwareUploadRequest $request): JsonResponse
    {
        return response()->json(['message' => translate('Setting saved'), 'success' => true, 'status' => 201], 201);
    }
}
