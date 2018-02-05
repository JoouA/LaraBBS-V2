<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nicolaslopezj\Searchable\SearchableTrait;

class PagesController extends Controller
{

    use SearchableTrait;

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
     * 信息搜索
     * @param Request $request
     */
    public function Search(Request $request)
    {

    }
}
