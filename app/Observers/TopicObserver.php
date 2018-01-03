<?php
namespace  App\Observers;

use App\Handlers\SlugTranslateHandler;
use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    /**
     * @param Topic $topic
     */
    public function saving(Topic $topic)
    {
        // xss 过滤
        $topic->body = clean($topic->body,'user_topic_body');

        // 生成话题摘录
        $topic->excerpt = make_excerpt($topic->body);

        // 如果没有slug字段没有内容，使用翻译
        if (! $topic->slug){
            $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
        }
    }
}