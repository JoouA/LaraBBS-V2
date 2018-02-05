<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class PagesController extends Controller
{

    public function root()
    {
        return view('pages.root');
    }

    /**
     * 后台访问权限控制
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function permissionDenied()
    {
        // 如果当前用户有权限访问后台，直接跳转访问
        if (config('administrator.permission')()){
            return redirect(url(config('administrator.url')),302);
        }

        //否则使用视图
        return view('pages.permission_denied');
    }

    /**
     * 搜索信息
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Search(Request $request)
    {
        $query = clean($request->input('q'),'search_q');

        if ($request->user_id){
            $user = User::findOrFail($request->user_id);

            $topics = Topic::search($query,null,true)->byWhom($request->user_id)->paginate(30);

            $users = collect();
        }

        $filterd_noresult = isset($topics) ? $topics->total() == 0 : false;

        // 当不在用户内容进行搜索，或者当前用户没有你搜的内容的情况
        if (! $request->user_id || ($request->user_id && $topics->total() == 0) ){
            $user = $request->user_id ? $user  : new  User;
            $users = User::search($query, null, true)->orderBy('last_actived_at', 'desc')->limit(5)->get();
            $topics = Topic::search($query, null, true)->paginate(30);
        }

        return view('pages.search',compact('user','users','topics','query','filterd_noresult'));

    }
}
