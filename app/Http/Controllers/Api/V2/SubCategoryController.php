<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\CategoryCollection;
use App\Models\Category;

class SubCategoryController extends Controller
{

    /**
     * @param $id
     * @return CategoryCollection
     */
    public function index($id): CategoryCollection
    {
        return new CategoryCollection(Category::where('parent_id', $id)->get());
    }
}
