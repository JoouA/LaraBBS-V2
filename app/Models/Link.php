<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Link extends Model
{
    protected $table = 'links';

    public $cache_key = 'larabbs_links';
    protected $cache_expire_in_minutes = 1440;

    protected $fillable = ['avatar', 'title', 'link'];

    /**
     * 修复后台图片上传的路劲问题
     * @param $path 这个参数就是一个图片的名称而已
     */
    public function setAvatarAttribute($path)
    {
        // 如果不是 `http` 子串开头，那就是从后台上传的，需要补全 URL
        if (!starts_with($path, 'http')) {
            // 拼接完整的 URL
            $path = config('app.url') . '/uploads/images/links/' . $path;
        }

        $this->attributes['avatar'] = $path;
    }

    public function getAllCached()
    {
        return Cache::remember($this->cache_key,$this->cache_expire_in_minutes,function (){
            return $this->all();
        });
    }

}
