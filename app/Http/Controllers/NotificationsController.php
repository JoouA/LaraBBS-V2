<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{

    /**
     *
     * NotificationsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $notifications = \Auth::user()->notifications()->paginate(20);

        // 标记为已读，未读数量清零
        \Auth::user()->markAsRead();

        return view('notifications.index',compact('notifications'));
    }
}
