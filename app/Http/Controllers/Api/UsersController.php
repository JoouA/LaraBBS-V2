<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Models\Image;
use App\Models\User;
use App\Transformers\UserTransformer;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $verifyData = \Cache::get($request->verification_key);

        if (! $verifyData){
            return $this->response->error('验证码失效',422);
        }

        if (!hash_equals($verifyData['code'],$request->verification_code)){
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'mobile' => $verifyData['mobile'],
            'password' => bcrypt($request->password),
        ]);

        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        return $this->response->item($user,new UserTransformer())->setMeta([
            'access_token' => \Auth::guard('api')->fromUser($user),
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60,
        ])->setStatusCode(201);
    }

    /**
     * 显示个人信息
     * @return \Dingo\Api\Http\Response
     */
    public function me()
    {
        $user = $this->user();
        return $this->response->item($user,new UserTransformer());
    }


    /**
     * @param UserRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(UserRequest $request)
    {
        $user = $this->user();

        $attributes = $request->only(['name','email','introduction','registration_id']);

        if ($request->avatar_image_id){
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }

        $user->update($attributes);

        return $this->response->item($user, new UserTransformer());
    }

    /**
     * 获得活跃用户的数据
     * @param User $user
     * @return \Dingo\Api\Http\Response
     */
    public function activeUsers(User $user)
    {
        $users = $user->getActiveUsers();

        return $this->response->collection($users,new UserTransformer());
    }
}
