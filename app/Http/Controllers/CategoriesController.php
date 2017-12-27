<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Category $category)
    {
        $topics = $category->topics()->with(['user','category'])->paginate();

        return view('topics.index',compact('topics','category'));
    }
}
