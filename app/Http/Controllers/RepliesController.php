<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyRequest;
use App\Models\Reply;

class RepliesController extends Controller
{
    /**
     * 构造函数
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(ReplyRequest $request,Reply $reply)
    {
        try{
            // 使用fill进行数据批量插入 也要设置一下fillable 不然会插入失败
            $reply->fill($request->all());

            $reply->user_id = \Auth::id();

            $reply->save();

            return redirect()->to($reply->topic->link())->with('success','回复成功!');
        }catch (\Exception $e){
            return back()->withInput($request->all())->with('danger','回复失败');
        }

    }

    public function destroy(Reply $reply)
    {
        $this->authorize('destroy',$reply);

        $reply->delete();

        return redirect()->to($reply->topic->link())->with('success','删除评论成功!');
    }
}
