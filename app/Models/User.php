<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    use Notifiable {
        notify as protected laravelNotify;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar','introduction'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany(Topic::class,'user_id','id');
    }

    /**
     * @param $model
     * @return bool
     */
    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }


    /**
     * 一个用户有多少的回复
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class,'user_id','id');
    }

    /**
     * 通知
     * @param $instance
     */
    public function notify($instance)
    {
        // 如果要通知的人是当前用户，就不必通知了
        if ( $this->id == \Auth::id()){
            return;
        }

        $this->increment('notification_count',1);

        $this->laravelNotify($instance);
    }

    /**
     *标记已读的消息
     */
    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }
}
