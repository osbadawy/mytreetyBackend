<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\ShopCollection;
use App\Models\Seller;
use App\Utility\SearchUtility;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ShopController extends Controller
{


    /**
     * @param Request $request
     * @return ShopCollection
     */
    public function index(Request $request): ShopCollection
    {
        //Prepare sellers query
        $sellers_query = Seller::query();

        //Per page
        $request->per_page ? $per_page = $request->per_page :  $per_page = 12;

        //Filter by name
        $this->filterByName($request->name, $sellers_query);

        return new ShopCollection($sellers_query->whereIn('user_id', verified_sellers_id())->paginate($per_page));

    }


    public function filterByName($name, Builder $sellers_query): void
    {
        if ($name != null && $name != "") {
            $sellers_query->where("name", 'like', "%{$name}%");
            SearchUtility::store($name);
        }
    }


}
