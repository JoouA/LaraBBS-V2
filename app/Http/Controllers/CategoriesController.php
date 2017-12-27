<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Request $request,Topic $topic,Category $category)
    {
        $topics = $topic->withOrder($request->input('order'))->where('category_id',$category->id)->paginate();

        return view('topics.index',compact('topics','category'));
    }
}
