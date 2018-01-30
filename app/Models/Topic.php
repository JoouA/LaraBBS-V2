<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = 'topics';

    protected $fillable = ['title', 'body', 'category_id','excerpt','slug'];

    protected $guarded = [];

    public function scopeWithOrder($query,$order)
    {
        // 不同的排序，使用不同的数据读取逻辑
        switch ($order){
            case 'recent':
                $query = $this->recent();
                break;
            default:
                $query = $this->recentReplied();
                break;
        }

        //// 预加载防止 N+1 问题
       return  $query->with(['user','category']);
    }

    public function scopeRecentReplied($query)
    {
        // 当话题有新回复时，我们将编写逻辑来更新话题模型的 reply_count 属性，
        // 此时会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at','desc');
    }

    public function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->orderBy('created_at','desc');
    }


    /**
     * topic 属于哪一个用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    /**
     * topic 属于哪一个category
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }


    /**
     * 生成topic的链接
     * @param array $params
     * @return string
     */
    public function link($params = [])
    {
        return route('topics.show',array_merge([$this->id,$this->slug],$params));
    }


    /**
     * 一个专题有多少回复
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class,'topic_id','id');
    }

    /**
     * 用户赞
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function votes()
    {
        return $this->belongsToMany(User::class,'zans','topic_id','user_id')->withPivot('created_at')->withTimestamps();
    }


}
