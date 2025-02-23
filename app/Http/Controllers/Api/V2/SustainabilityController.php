<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\SustainabilityCollection;
use App\Http\Resources\V2\SustainabilityRequestCollection;
use App\Models\Product;
use App\Models\Sustainability;
use App\Models\SustainabilityRequest;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use stdClass;

class SustainabilityController extends Controller
{

    /**
     * @param Request $request
     * @return SustainabilityCollection
     */
    public function index(Request $request): SustainabilityCollection
    {
        //Get all Sustainability icons
        return new SustainabilityCollection(Sustainability::all());
    }

    /**
     * @param Request $request
     * @return SustainabilityCollection
     */
    public function vendor(Request $request): SustainabilityCollection
    {
        $sustainabilities_ids = [];
        if ($request->user()) {

            // Get all sustainability requests made by the current user
            $sustainabilities = SustainabilityRequest::where('user_id', $request->user()->id)->select('sustainability_id')->get();

            // Extract the sustainability IDs into an array
            $sustainabilities_ids = $sustainabilities->pluck('sustainability_id')->toArray();

            // Get all sustainability icons that are not in the list of requested sustainability IDs
            $icons = Sustainability::whereNotIn('id', $sustainabilities_ids)->get();
        }

        $icons = Sustainability::whereNotIn('id', $sustainabilities_ids)->get();

        return new SustainabilityCollection($icons);

    }

    /**
     * @param Request $request
     * @return SustainabilityRequestCollection
     */
    public function ProductsVerifications(Request $request): SustainabilityRequestCollection
    {
        //Get all sustainability requests made by the current user and paid
        $sustainability_requests = SustainabilityRequest::where('user_id', $request->user()->id)->where('status', 1)->get();

        return new SustainabilityRequestCollection($sustainability_requests->groupBy('product_id'));

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function ProductsVerificationDetails(Request $request): JsonResponse
    {
        $user_id = $request->user()->id;

        // Get the sustainability requests for the current user and product
        $reqs = SustainabilityRequest::where('user_id', $user_id)
            ->where('product_id', $request->id)
            ->get();

        // If no requests were found, return an error response
        if (!$reqs->count()) {
            return response()->json([
                'message' => translate('Verification Request not found'), 'success' => false
            ], 400);
        }

        // Get the product associated with the requests
        $product = Product::where('id', $request->id)->where('user_id', $user_id)->first();

        // If the product was not found, return an error response
        if (!$product) {
            return response()->json([
                'message' => translate('Product not found'), 'success' => false
            ], 400);
        }

        // Initialize the response data object and the product requests array
        $data = new stdClass();
        $product_requests = [];

        // Initialize the status and price variables
        $status = translate('Verified');
        $price = 0;

        // Iterate over the requests
        foreach ($reqs as $req) {
            // Get the sustainability icon associated with the request
            $icon = Sustainability::find($req->sustainability_id);
            $is_verified = 0;

            // Get the product sustainability record for the current icon and product
            $product_sustainability = DB::table('product_sustainability')
                ->where('product_id', $req->product_id)
                ->where('sustainability_id', $req->sustainability_id)
                ->first();

            // If a product sustainability record was found, update the status and price accordingly
            if ($product_sustainability) {
                $is_verified = $product_sustainability->is_verified;
                if ($is_verified == 0) {
                    $status = translate('Pending');
                }
                if ($is_verified == 2) {
                    $status = translate('Denied');
                }
                $price = $price + $icon->price;


                // Add the current icon to the product requests array
                $product_requests[] = [
                    'id' => $icon->id,
                    'name' => $icon->getTranslation('name'),
                    'image' => uploaded_asset($icon->getTranslation('image')),
                    'price' => format_price($icon->price),
                    'ui_sepertion' => $icon->ui_sepertion,
                    'required_documents' => $icon->required_documents,
                    'verified' => (int)$is_verified,
                ];
            } else {
                // If no product sustainability record was found, set the status to "Declined"
                $status = translate('Declined');
            }
            // Set the remaining data in the response object
            $data->product_name = $product->name;
            $data->product_category = $product->category->parentCategory->parentCategory->name . ' / ' . $product->category->parentCategory->name;
            $data->product_subcategory = $product->category->name;
            $data->created_at = $reqs[0]->created_at;
            $data->status = $status;
            $data->price = format_price($price);
            $data->num_icons = $reqs->count();
            $data->icons = $product_requests;

            // Return a success response with the data
            return response()->json([
                'data' => $data, 'success' => true
            ]);
        }
    }
}
