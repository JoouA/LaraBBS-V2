<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use DB;

trait LastActivedAtHelper
{
    // 缓存相关
    protected $hash_prefix = 'larabbs_last_actived_at_';
    protected $field_prefix = 'user_';

    /**
     * 将用户的活跃时间记录到redis当中
     */
    public function recordLastActivedAt()
    {
        // 获取今天的日期
        $date = Carbon::now()->toDateString();

        // Redis 哈希表的命名，如：larabbs_last_actived_at_2017-10-21
        $hash = $this->getHashFromDateTimeString($date);

        // 字段名称，如：user_1
        $field = $this->getHashField();

        // 当前时间，如：2017-10-21 08:35:15
        $now = Carbon::now()->toDateTimeString();

        // 数据写入 Redis ，字段已存在会被更新
        Redis::hSet($hash, $field, $now);
    }

    /**
     * 将redis中的数据同步到users表中
     */
    public function syncUserActivedAt()
    {
        // 获取昨天的日期，格式如：2017-10-21
        $yestertoday_date = Carbon::now()->subDay()->toDateString();

        // Redis 哈希表的命名，如：larabbs_last_actived_at_2017-10-21
        $hash = $this->getHashFromDateTimeString($yestertoday_date);

        // 从 Redis 中获取所有哈希表里的数据
        $dates = Redis::hGetAll($hash);

        // 遍历，并同步到数据库中
        foreach ($dates as $user_id => $actived_at) {
            //将user_number 转换成number
            $user_id = str_replace('user_', '', $user_id);

            if ($user = $this->find($user_id)) {
                $user->last_actived_at = $actived_at;
                $user->save();
            }
        }

        Redis::del($hash);
    }

    /**
     * 这儿使用了 Eloquent 的 访问器 来实现此功能
     * 当我们从实例中获取某些属性值的时候，访问器允许我们对 Eloquent 属性值进行动态修改。
     * @param $value 是users表中$user字段中的last_actived_at的值
     * @return Carbon
     */
    public function getLastActivedAtAttribute($value)
    {
        // 获取今天的时间
        $date = Carbon::now()->toDateString();

        // Redis 哈希表的命名，如：larabbs_last_actived_at_2017-10-21
        $hash = $this->getHashFromDateTimeString($date);

        // 字段名称，如：user_1
        $field = $this->getHashField();

        // 三元运算符，优先选择 Redis 的数据，否则使用数据库中
        $datetime = Redis::hGet($hash, $field) ? : $value;

        // 如果存在的话，返回时间对应的 Carbon 实体
        if ($datetime) {
            return new Carbon($datetime);
        } else {
            // 否则使用用户注册时间
            return $this->created_at;
        }
    }

    /**
     * @param $date
     * @return string
     */
    public function getHashFromDateTimeString($date)
    {
        // Redis 哈希表的命名，如：larabbs_last_actived_at_2017-10-21
        return $this->hash_prefix . $date;
    }

    /**
     * @return string
     */
    public function getHashField()
    {
        // 字段名称，如：user_1
        return $this->field_prefix . $this->id;
    }
}