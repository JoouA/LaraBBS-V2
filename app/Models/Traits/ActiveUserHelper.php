<?php

namespace App\Models\Traits;

use App\Models\Reply;
use App\Models\Topic;
use Cache;
use Carbon\Carbon;
use DB;

trait ActiveUserHelper
{
    // 用于存放临时用户数据
    protected $users = [];

    // 配置信息
    protected $topic_weight = 4;
    protected $reply_weight = 1;
    protected $pass_days = 30;
    protected $user_number = 6;

    //缓存相关的配置
    protected $cache_key = 'larabbs_active_users';
    protected $cache_expire_minutes = 65;

    /**
     * 数据直接从缓存里面取，如果没有数据就获取，然后进行缓存
     * @return mixed
     */
    public function getActiveUsers()
    {
        // 尝试从缓存中取出 cache_key相对应的数据，如果可以取到直接返回数据
        //否则运行匿名函数中的代码来取出活跃用户的数据，返回的同时做了缓存
        return Cache::remember($this->cache_key, $this->cache_expire_minutes, function () {
            return $this->calculateActiveUsers();
        });
    }

    /**
     * 这个命令一个小时执行一次
     */
    public function calculateAndCacheActiveUsers()
    {
        //获得活跃用户列表
        $users = $this->calculateActiveUsers();

        //并加以缓存
        $this->cacheActiveUsers($users);
    }


    public function calculateActiveUsers()
    {
        $this->calculateTopicScore();
        $this->calculateReplyScore();

        // 数组按照得分排序
        $users = array_sort($this->users, function ($user) {
            return $user['score'];
        });


        // 我们需要的是倒序，高分靠前，第二个参数为保持数组的 KEY 不变
        $users = array_reverse($users, true);

        // 只获取我们想要的数量,从0开始取，保持key不变
        $users = array_slice($users, 0, $this->user_number, true);

        // 新建一个空合集
        $active_users = collect();

        foreach ($users as $user_id => $user) {
            // 找寻下是否可以找到用户
            $user = $this->find($user_id);

            if (count($user)) {
                // 将此用户实体放入集合的末尾
                $active_users->push($user);
            }
        }

        //返回数据
        return $active_users;

    }

    private function calculateTopicScore()
    {
        // 从话题数据表里取出限定时间范围（$pass_days）内，有发表过话题的用户
        // 并且同时取出用户此段时间内发布话题的数量
        $topic_users = Topic::query()->select(DB::raw('user_id,count(*) as topic_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();
        // "select user_id,count(*) as topic_count from `topics` where `created_at` >= ? group by `user_id`"
        // 根据话题数量计算得分
        foreach ($topic_users as $topic_user) {
            $this->users[$topic_user->user_id]['score'] = $topic_user->topic_count * $this->topic_weight;
        }
    }

    /**
     * 计算reply得分
     */
    private function calculateReplyScore()
    {
        // 从回复数据表里取出限定时间范围（$pass_days）内，有发表过回复的用户
        // 并且同时取出用户此段时间内发布回复的数量
        $reply_users = Reply::query()->select(DB::raw('user_id,count(*) as reply_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();

        // 根据回复数量计算得分
        foreach ($reply_users as $reply_user) {
            $reply_score = $reply_user->reply_count * $this->reply_weight;
            if (isset($this->users[$reply_user->user_id])) {
                $this->users[$reply_user->user_id]['score'] += $reply_score;
            } else {
                $this->users[$reply_user->user_id]['score'] = $reply_score;
            }
        }
    }

    /**
     * @param $active_users
     */
    private function cacheActiveUsers($active_users)
    {
        Cache::put($this->cache_key, $active_users, $this->cache_expire_minutes);
    }
}