<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\CategoryCollection;
use App\Models\Category;
use Cache;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CategoryController extends Controller
{

    /**
     * @param int $parent_id
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(int $parent_id = 0)
    {
        //Set parent id
        if (request()->has('parent_id') && is_numeric(request()->get('parent_id'))) {
            $parent_id = request()->get('parent_id');
        }

        //Get categories
        return Cache::remember("app.categories-$parent_id", 86400, function () use ($parent_id) {
            return new CategoryCollection(Category::where('parent_id', $parent_id)->get());
        });
    }


}
