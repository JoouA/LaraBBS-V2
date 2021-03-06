<?php
namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['roles','followers','followings'];

    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'registration_id' => $user->registration_id,
            'introduction' => $user->introduction,
            'bound_phone' => $user->mobile ? true : false,
            'bound_wechat' => ($user->weixin_unionid || $user->weixin_openid)? true : false,
            'last_actived_at' => $user->last_actived_at->toDateTimeString(),
            'created_at' => $user->created_at->toDateTimeString(),
            'updated_at' => $user->updated_at->toDateTimeString(),
        ];
    }

    /**
     * 返回user的角色信息
     * @param User $user
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRoles(User $user)
    {
        return $this->collection($user->roles,new RoleTransformer());
    }

    /**
     * 获得followers的信息
     * @param User $user
     * @return \League\Fractal\Resource\Collection
     */
    public function includeFollowers(User $user)
    {
        $followers = $user->followers()->orderBy('pivot_created_at','desc')->get();
        return $this->collection($followers,new UserTransformer());
    }

    /**
     * 获得followings的信息
     * @param User $user
     * @return \League\Fractal\Resource\Collection
     */
    public function includeFollowings(User $user)
    {
        $followings = $user->followings()->orderBy('pivot_created_at','desc')->get();
        return $this->collection($followings,new UserTransformer());
    }
}