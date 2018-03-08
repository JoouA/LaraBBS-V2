<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Transformers\TopicTransformer;

class TopicsController extends Controller
{
    /**
     * 创建Topic
     * @param TopicRequest $request
     * @param Topic $topic
     * @return $this
     */
    public function store(TopicRequest $request,Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $this->user()->id;
        $topic->save();

        return $this->response->item($topic,new TopicTransformer())->setStatusCode(201);
    }


    /**
     * 更新Topic
     * @param TopicRequest $request
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(TopicRequest $request,Topic $topic)
    {
        $this->authorize('update',$topic);
        $topic->update($request->all());

        return $this->response->item($topic,new TopicTransformer());
    }
}
