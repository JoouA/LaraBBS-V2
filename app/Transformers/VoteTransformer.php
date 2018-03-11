<?php
namespace App\Transformers;

use App\Models\Topic;
use League\Fractal\TransformerAbstract;

class  VoteTransformer extends TransformerAbstract
{
    public function transform(Topic $topic)
    {
        return [
            'id' => $topic->id,
            'topic_title' => $topic->title,
            'user_id' => \Auth::guard('api')->user()->id,
            'user_avatar' => \Auth::guard('api')->user()->avatar,
            'user_link' => route('users.show',\Auth::guard('api')->user()->id),
        ];
    }
}