<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;

class BannerController extends Controller
{


    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $banners = [];
        $main_url = "https://mytreety.s3.eu-central-1.amazonaws.com/banners";
        $de_banners = ['WelcomeBanner(GE).png', 'Banner1(GE).png', 'Banner2(GE).png', 'Banner3(GE).png'];
        $en_banners = ['WelcomeBanner(EN).png', 'Banner1(EN).png', 'Banner2(EN).png', 'Banner3(EN).png'];

        //Check app language
        app()->getLocale() == 'de' ? $lang_banners = $de_banners : $lang_banners = $en_banners;

        //Set the banners
        foreach ($lang_banners as $lang_banner) {
            $banners[]['photo'] = "$main_url/$lang_banner";
        }

        return response()->json([
            'data' => $banners,
            'success' => true,
            'status' => 200
        ], 200);
    }
}
