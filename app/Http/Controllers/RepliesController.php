<?php

namespace App\Http\Controllers;

use App\Events\CreateReply;
use App\Http\Requests\ReplyRequest;
use App\Models\Reply;
use App\Models\Topic;
use Illuminate\Http\Request;
use DB;

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

    public function store(ReplyRequest $request, Reply $reply)
    {
        try {
            // 使用fill进行数据批量插入 也要设置一下fillable 不然会插入失败
            $reply->fill($request->all());
            $reply->user_id = \Auth::id();
            $reply->save();

            event(new CreateReply(\Auth::user(), $reply));

            return redirect()->to($reply->topic->link())->with('success', '回复成功!');
        } catch (\Exception $e) {
            return back()->withInput($request->all())->with('danger', '回复失败');
        }

    }

    public function destroy(Reply $reply)
    {
        $this->authorize('destroy', $reply);

        $reply->delete();

        return redirect()->to($reply->topic->link())->with('success', '删除评论成功!');
    }

    /**
     * 获得评论topic的用户的基本信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function replyUsers(Request $request)
    {
        $topic_id = $request->topic;

        $replies = Reply::select(DB::raw('user_id,count(*) as reply_count'))
            ->with(['user' => function ($query) {
                $query->select('id', 'name');
            }])
            ->where('topic_id', $topic_id)->groupBy('user_id')->get();


        return response()->json([
            'replies' => $replies,
        ]);
    }
}
