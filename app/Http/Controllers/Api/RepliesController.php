<?php

namespace App\Http\Controllers\Api;

use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use App\Transformers\ReplyTransformer;
use App\Http\Requests\Api\ReplyRequest;

class RepliesController extends Controller
{

    /**
     * 获取topic的replies
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     */
    public function index(Topic $topic)
    {
        $replies = $topic->replies()->paginate(10);

        return $this->response->paginator($replies,new ReplyTransformer());
    }


    /**
     * 获取用户的评论
     * @param User $user
     * @return \Dingo\Api\Http\Response
     */
    public function userIndex(User $user)
    {
        $replies = $user->replies()->paginate(10);

        return $this->response->paginator($replies,new ReplyTransformer());
    }

    /**
     * 保存reply
     * @param ReplyRequest $request
     * @param Topic $topic
     * @param Reply $reply
     * @return $this
     */
    public function store(ReplyRequest $request,Topic $topic,Reply $reply)
    {
        $reply->content = $request->input('content');
        $reply->topic_id = $topic->id;
        $reply->user_id = $this->user()->id;
        $reply->save();

        return $this->response->item($reply,new ReplyTransformer())->setStatusCode(201);
    }


    /**
     * 更新reply
     * @param ReplyRequest $request
     * @param Reply $reply
     * @return \Dingo\Api\Http\Response
     */
    public function update(ReplyRequest $request,Reply $reply)
    {
        $this->authorize('update',$reply);
        $reply->content = $request->input('content');
        $reply->save();

        return $this->response->item($reply,new ReplyTransformer());
    }

    /**
     * 删除评论
     * @param Reply $reply
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update',$reply);
        $reply->delete();

        return $this->response->noContent();
    }
}
