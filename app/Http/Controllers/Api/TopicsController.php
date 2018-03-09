<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\TopicRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Transformers\TopicTransformer;

class TopicsController extends Controller
{

    /**
     * Topics 默认列表
     * @param Request $request
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request,Topic $topic)
    {
        $query = $topic->query();

        if ($categoryId = $request->category_id){
            $query->where('category_id',$categoryId);
        }

        // 为了说明N+1问题，不使用scopeWithOrder
        switch ($request->order){
            case 'recent':
                $query->recent();
                break;
            default:
                $query->recentReplied();
                break;
        }

        $topics = $query->paginate(20);

        return $this->response->paginator($topics,new TopicTransformer());
    }

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


    public function userIndex(User $user)
    {
        $topics = $user->topics()->recent()->paginate(20);

        return $this->response->paginator($topics,new TopicTransformer());
    }
}
