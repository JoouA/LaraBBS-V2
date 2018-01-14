<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Request $request,Topic $topic,Category $category,User $user)
    {
        $topics = $topic->withOrder($request->input('order'))->where('category_id',$category->id)->paginate();

        $active_users = $user->getActiveUsers();

        return view('topics.index',compact('topics','category','active_users'));
    }
}
