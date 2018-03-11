<?php

namespace App\Http\Controllers\Api;

use App\Events\CreateVote;
use App\Http\Requests\Api\TopicRequest;
use App\Models\User;
use App\Notifications\VoteNotification;
use App\Transformers\VoteTransformer;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Transformers\TopicTransformer;
use Log;

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


    /**
     * 删除topic
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('update',$topic);

        $topic->delete();

        return $this->response->noContent();
    }


    /**
     * 显示某一个topic的详情
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     */
    public function show(Topic $topic)
    {
        return $this->response->item($topic,new TopicTransformer());
    }

    /**
     * 一个用户的的topics
     * @param User $user
     * @return \Dingo\Api\Http\Response
     */
    public function userIndex(User $user)
    {
        $topics = $user->topics()->recent()->paginate(20);

        return $this->response->paginator($topics,new TopicTransformer());
    }

    public function vote(Topic $topic)
    {
        $user = $this->user();

        try{
            $result = $topic->votes()->toggle($user->id);

            $type = count($result['attached']);

            // 如果是点赞的话，进行通知
            if (!empty($type)){
                if (!$user->isAuthorOf($topic)){
                    $topic->user->notify(new VoteNotification($user,$topic));
                }
                event(new CreateVote($user,$topic));
            }

            return $this->response->item($topic,new VoteTransformer());
        }catch (\Exception $e){
            Log::error($user->id . ' id的用户赞' . $topic->id . ' id的专题失败');

            return response()->json([
                'vote_id' => $user->id,
                'topic_id' => $topic->id,
                'status' => 'failed',
                'statusCode' => 500,
            ]);
        }

    }

}
