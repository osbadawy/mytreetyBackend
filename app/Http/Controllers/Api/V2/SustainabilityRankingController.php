<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Traits\SustainabilityRankingTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SustainabilityRankingController extends Controller
{
    use SustainabilityRankingTrait;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function UpdateScore(Request $request): JsonResponse
    {
        //Calculate the score for all new products
        $this->calculateScoreAll();

        return response()->json(['message' => translate('Products Scores has been updated successfully'), 'success' => true, 'status' => 200], 200);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function UpdateLevel(Request $request): JsonResponse
    {
        //Calculate the levels for all new products
        $this->calculateLevelAll();

        return response()->json(['message' => translate('Products Levels has been updated successfully'), 'success' => true, 'status' => 200], 200);

    }


}
