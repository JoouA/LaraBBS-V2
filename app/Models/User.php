<?php

namespace App\Models;

use App\Models\Traits\LastActivedAtHelper;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Nicolaslopezj\Searchable\SearchableTrait;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;
use Overtrue\LaravelFollow\Traits\CanFollow;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Traits\ActiveUserHelper;
use Cmgmyr\Messenger\Traits\Messagable;

class User extends Authenticatable
{
    use HasRoles;
    use LastActivedAtHelper;

    use ActiveUserHelper;

    use Notifiable {
        notify as protected laravelNotify;
    }

    use SearchableTrait;

    use CanFollow,CanBeFollowed;

    use Messagable;

    protected $searchable = [
        'columns' => [
            'users.name' => 10,
            'users.introduction' => 10,
        ]
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar','introduction','mobile','weixin_openid','weixin_unionid'
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


    /**
     * 修复后台上传的保存到数据库的url地址
     * @param $path
     * @return string
     */
    public function setAvatarAttribute($path)
    {
        // 如果不是 `http` 子串开头，那就是从后台上传的，需要补全 URL
        if (! starts_with($path,'http')){
            // 拼接完整的 URL
            $path = config('app.url') .'/uploads/images/avatars/adminChange/'.  $path;
        }

        $this->attributes['avatar'] = $path;
    }

    /**
     * avatar的get修改器
     * @param $avatar
     * @return string
     */
    public function getAvatarAttribute($avatar)
    {
        // 判断数据库里面有值，并且文件照片还在
        //http://larabbs.work/uploads/images/avatars/201802/03/4_5a342131c562d46751f75a57b3de63d5.jpg
        $file = public_path() .   str_replace(config('app.url'),'',$avatar);
        return file_exists($file) ? $avatar : 'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/s5ehp11z6s.png?imageView2/1/w/200/h/200';
    }

    /**
     * 设置后台修改密码的时候密码不进行加密的问题
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        // 如果值的长度等于 60，即认为是已经做过加密的情况
        if (strlen($password)!= 60){
            // != 60就加密
            $password =  bcrypt($password);
        }

        $this->attributes['password'] = $password;
    }

    /**
     * 用户的vote专题
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function votes()
    {
        return $this->belongsToMany(Topic::class,'zans','user_id','topic_id')->withPivot('created_at')->withTimestamps();
    }

    /**
     * @param Topic $topic
     * @return bool
     */
    public function isVote(Topic $topic)
    {
        return (bool)$this->votes()->where('topic_id',$topic->id)->count();
    }
}
