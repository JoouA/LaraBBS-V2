<?php
namespace  App\Observers;

use App\Handlers\SlugTranslateHandler;
use App\Jobs\TranslateSlug;
use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{

    /*
     * 当一个新模型被初次保存将会触发 creating 以及 created 事件。
     * 如果一个模型已经存在于数据库且调用了 save 方法，将会触发 updating 和 updated 事件。
     * 在这两种情况下都会触发 saving 和 saved 事件。
     * */
    /**
     * @param Topic $topic
     */
    public function saving(Topic $topic)
    {
        // xss 过滤
        $topic->body = clean($topic->body,'user_topic_body');

        // 生成话题摘录
        $topic->excerpt = make_excerpt($topic->body);

    }

    /**
     * @param Topic $topic
     */
    public function saved(Topic $topic)
    {
        // 如果没有slug字段没有内容，使用翻译
        // 当文章第一次被创建的时候，在saving过程中还没有id，所以在在saving中调用队列会报错
        if (! $topic->slug){
            dispatch(new TranslateSlug($topic));
            //$topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
        }
    }

}