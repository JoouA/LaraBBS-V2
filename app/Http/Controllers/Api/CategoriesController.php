<?php

namespace App\Http\Controllers\Api;


use App\Models\Category;
use App\Transformers\CategoryTransformer;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return $this->collection($categories,new CategoryTransformer());
    }
}
