<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeattachIconsRequest;
use App\Http\Requests\SustainabilityIconsRequest;
use App\Models\CombinedOrder;
use App\Models\Product;
use App\Models\ProductRanking;
use App\Models\Sustainability;
use App\Models\SustainabilityRequest;
use App\Traits\PaymentTrait;
use App\Traits\SustainabilityRankingTrait;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PayPalHttp\IOException;
use Stripe\Exception\ApiErrorException;

class SustainabilityRequestController extends Controller
{
    use SustainabilityRankingTrait, PaymentTrait;


    /**
     * @param SustainabilityIconsRequest $request
     * @return JsonResponse
     */
    public function store(SustainabilityIconsRequest $request): JsonResponse
    {

        foreach ($request->sustainabilities as $key => $sustainability_id) {
            //Check if icon exist
            $sustainability = Sustainability::where('id', $sustainability_id)->count();
            if ($sustainability == 0) return response()->json(['message' => translate('Sustainability id is wrong'), 'success' => false, 'status' => 400], 400);
        }
        $total_price = 0;

        $user_id = $request->user()->id;
        $payment_option = $request->payment_option;

        //Initiate new combined order
        $combined_order = $this->createCombinedOrder($user_id);

        //loop for each selected icons
        foreach ($request->sustainabilities as $key => $sus_id) {

            //get icon details
            $icon = Sustainability::find($sus_id);

            //check if icon exist
            if (!$icon) return response()->json(['message' => translate('Icon id is wrong'), 'success' => false, 'status' => 400], 400);

            //calculate total price
            $total_price = $total_price + ($icon->price * count($request->product_ids));

            //get uploaded documents
            $documents = $request->documents[$sus_id];
            if (!$documents) return response()->json(['message' => translate('Please upload all required documents'), 'success' => false, 'status' => 400], 400);

            //loop for each selected products
            foreach ($request->product_ids as $key => $product_id) {

                //get product details
                $product = Product::where('id', $product_id)->where('user_id', $user_id)->first();

                //check if product exist
                if (!$product) return response()->json(['message' => translate('Product id is wrong'), 'success' => false, 'status' => 400], 400);

                //flag icon_calculated
                $this->flagIconCalculated($product_id);

                //attach icon to product
                $product->sustainabilities()->attach($sus_id);

                //create sustainability request
                $this->createSustianabilityRequest($user_id, $product_id, $sus_id, $documents, $combined_order);
            }
        }

        $vat = $total_price * 0.19;

        //save combined order grand total
        $combined_order->grand_total = $total_price + $vat;
        $combined_order->save();


        //Get payment link
        $return_url = env('VENDOR_URL', 'https://vendor.mytreety.com');
        $success_url = "$return_url/dashboard/product-verfication/Thank-you";
        $cancel_url= "$return_url/dashboard/product-verfication/Cancel";
        try {
            $payment_url = $this->getPaymentUrl($payment_option, $combined_order, $success_url,$cancel_url);
        } catch (IOException|ApiErrorException $e) {
            $payment_url = null;
        }


        return response()->json(['url' => $payment_url, 'message' => translate('Your request has been placed successfully'), 'success' => true, 'status' => 200], 200);

    }

    /**
     * @param $user_id
     * @return CombinedOrder
     */
    public function createCombinedOrder($user_id): CombinedOrder
    {
        $combined_order = new CombinedOrder;
        $combined_order->user_id = $user_id;
        $combined_order->type_id = 1;
        $combined_order->save();
        return $combined_order;
    }

    /**
     * @param $product_id
     * @return void
     */
    public function flagIconCalculated($product_id): void
    {
        $ranking = ProductRanking::firstOrNew(['product_id' => $product_id]);
        $ranking->icon_calculated = 0;
        $ranking->update();
    }

    /**
     * @param $user_id
     * @param $product_id
     * @param $sus_id
     * @param $documents
     * @param CombinedOrder $combined_order
     * @return void
     */
    public function createSustianabilityRequest($user_id, $product_id, $sus_id, $documents, CombinedOrder $combined_order): void
    {
        $sustainabilityRequest = new SustainabilityRequest;
        $sustainabilityRequest = SustainabilityRequest::firstOrCreate(['user_id' => $user_id, 'product_id' => $product_id, 'sustainability_id' => $sus_id]);
        $sustainabilityRequest->user_id = $user_id;
        $sustainabilityRequest->sustainability_id = $sus_id;
        $sustainabilityRequest->files = json_encode($documents);
        $sustainabilityRequest->status = 0;
        $sustainabilityRequest->combined_order_id = $combined_order->id;
        $sustainabilityRequest->save();
    }

    /**
     * @param DeattachIconsRequest $request
     * @return JsonResponse
     *
     */
    public function deAttachIcons(DeattachIconsRequest $request): JsonResponse
    {

        $product = Product::where('id', $request->product_id)->where('user_id', $request->user()->id)->first();

        //Check if the product exist
        if (!$product) return response()->json(['message' => translate('Product id is wrong'), 'success' => false, 'status' => 400], 400);

        //detach icons to product
        $product->sustainabilities()->detach($request->sustainabilities);

        //flag icon_calculated
        $this->flagIconCalculated($product->id);

        return response()->json(['message' => translate('Success'), 'success' => true, 'status' => 200], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function adminReset(Request $request): JsonResponse
    {
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


        $this->calculateLevelAll();

        return response()->json(['message' => translate('Success'), 'success' => true, 'status' => 200], 200);
    }

}
