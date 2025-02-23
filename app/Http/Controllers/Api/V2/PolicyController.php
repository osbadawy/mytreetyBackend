<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\PolicyCollection;
use App\Models\Page;

class PolicyController extends Controller
{

    /**
     * @OA\Get(
     *   tags={"Website | Policies"},
     *   path="/api/v2/policies/seller",
     *   summary="Return seller policy",
     *   @OA\Parameter(
     *         name="App-Language",
     *         in="header",
     *         required=false,
     *         @OA\Schema(
     *             default="en",
     *             type="string",
     *             enum={"en", "de"}
     *         )
     *     ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function sellerPolicy(): PolicyCollection
    {
        return new PolicyCollection(Page::where('type', 'seller_policy_page')->get());
    }


    /**
     * @OA\Get(
     *   tags={"Website | Policies"},
     *   path="/api/v2/policies/privacy",
     *   summary="Return privacy policy",
     *   @OA\Parameter(
     *         name="App-Language",
     *         in="header",
     *         required=false,
     *         @OA\Schema(
     *             default="en",
     *             type="string",
     *             enum={"en", "de"}
     *         )
     *     ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function privacyPolicy(): PolicyCollection
    {
        return new PolicyCollection(Page::where('type', 'privacy_policy_page')->get());
    }


    /**
     * @OA\Get(
     *   tags={"Website | Policies"},
     *   path="/api/v2/policies/buyer",
     *   summary="Return buyer policy",
     *   @OA\Parameter(
     *         name="App-Language",
     *         in="header",
     *         required=false,
     *         @OA\Schema(
     *             default="en",
     *             type="string",
     *             enum={"en", "de"}
     *         )
     *     ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function buyerPolicy(): PolicyCollection
    {
        return new PolicyCollection(Page::where('type', 'buyer_policy')->get());
    }

    /**
     * @OA\Get(
     *   tags={"Website | Policies"},
     *   path="/api/v2/policies/charity",
     *   summary="Return charity policy",
     *   @OA\Parameter(
     *         name="App-Language",
     *         in="header",
     *         required=false,
     *         @OA\Schema(
     *             default="en",
     *             type="string",
     *             enum={"en", "de"}
     *         )
     *     ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function charityPolicy(): PolicyCollection
    {
        return new PolicyCollection(Page::where('type', 'charity_policy')->get());
    }

    /**
     * @OA\Get(
     *   tags={"Website | Policies"},
     *   path="/api/v2/policies/terms",
     *   summary="Return terms policy",
     *   @OA\Parameter(
     *         name="App-Language",
     *         in="header",
     *         required=false,
     *         @OA\Schema(
     *             default="en",
     *             type="string",
     *             enum={"en", "de"}
     *         )
     *     ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function termsPolicy(): PolicyCollection
    {
        return new PolicyCollection(Page::where('type', 'terms_conditions_page')->get());
    }

    /**
     * @OA\Get(
     *   tags={"Website | Policies"},
     *   path="/api/v2/policies/imprint",
     *   summary="Return imprint policy",
     *    @OA\Parameter(
     *         name="App-Language",
     *         in="header",
     *         required=false,
     *         @OA\Schema(
     *             default="en",
     *             type="string",
     *             enum={"en", "de"}
     *         )
     *     ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function imprintPolicy(): PolicyCollection
    {

        return new PolicyCollection(Page::where('type', 'imprint_page')->get());
    }
}
