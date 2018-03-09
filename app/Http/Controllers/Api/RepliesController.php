<?php

namespace App\Http\Controllers\Api;

use App\Models\Reply;
use App\Models\Topic;
use App\Transformers\ReplyTransformer;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ReplyRequest;

class RepliesController extends Controller
{
    /**
     * 保存reply
     * @param ReplyRequest $request
     * @param Topic $topic
     * @param Reply $reply
     * @return $this
     */
    public function store(ReplyRequest $request,Topic $topic,Reply $reply)
    {
//        $reply->content = clean($request->input('content'));
        $reply->content = $request->input('content');
        $reply->topic_id = $topic->id;
        $reply->user_id = $this->user()->id;
        $reply->save();

        return $this->response->item($reply,new ReplyTransformer())->setStatusCode(201);
    }
}
