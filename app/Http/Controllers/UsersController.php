<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Handlers\ImageUploadHandlers;
use Auth;

class UsersController extends Controller
{
    /**
     * 构造函数
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'votes','followers','followings','replies','topics']);
    }

    /**
     * 显示个人的信息界面
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        $topics = $user->topics()->orderBy('updated_at', 'desc')->paginate(5);


        $replies = $user->replies()->with('topic')->orderBy('created_at', 'desc')->paginate(5);

        return view('users.show', compact('user', 'topics', 'replies'));
    }

    /**
     * 编辑个人信息
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('edit', $user);
        return view('users.edit', compact('user'));
    }


    /**
     * 更新个人信息
     * @param UserRequest $request
     * @param User $user
     * @param ImageUploadHandlers $uploader
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UserRequest $request, User $user, ImageUploadHandlers $uploader)
    {
        $data = $request->all();

        $this->authorize('update', $user);

        if ($request->file('avatar')) {
            $result = $uploader->save($request->file('avatar'), 'avatars', $user->id, 362, 362);

            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }

        try {
            $user->update($data);
            return redirect()->route('users.show', $user->id)->with('success', '更新个人资料成功!');
        } catch (\Exception $exception) {
            return back()->with('danger', '更新个人信息失败');
        }


    }

    /**
     * 返回用户的点赞文章
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function votes(User $user)
    {
        // 可以使用pivot来进行数据的排序
        $topics = $user->votes()->with('category')->withCount(['votes', 'replies'])->orderBy('pivot_created_at', 'desc')->paginate(5);

        return view('users.votes', compact('topics', 'user'));
    }

    /**
     * 设置个人头像
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function avatar(User $user)
    {
        $this->authorize('update',$user);
        return view('users.avatar', compact('user'));
    }


    /**
     * 更新个人头像
     * @param Request $request
     * @return string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateAvatar(Request $request)
    {
        $user_id = '';
        if ($request->has('user_id')) {
            $user_id = $request->user_id;
        }

        $user = User::find($user_id);
        $this->authorize('update',$user);

        if ($request->has('slim') && $request->slim[0]) {

            $output = $request->slim[0];

            //对output的内容进行decode， out_put的内容是json格式的
            $output = json_decode($output, TRUE);


            if (isset($output) && isset($output['output']) && isset($output['output']['image']))
                $image = $output['output']['image'];

            if (isset($image)) {
                // $image 是经过base64处理过的图片内容
                $data = app(ImageUploadHandlers::class)->save_base64_image($image, 'avatars', $user_id, 362);
                if (is_array($data) && $data['status'] == 0) {

                    $user->avatar =  $data['path'];
                    $user->save();
//                    Auth::user()->update(['avatar' => $data['path']]);
                }
                return $data;
            }
            return '没有图片文件';
        }
        return '非使用slim cropper裁剪';
    }

    /**
     *  显示重置密码的界面
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function passwordForm(User $user)
    {
        $this->authorize('edit',$user);
        return view('users.password', compact('user'));
    }

    /**
     * 更新密码
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updatePassword(Request $request, User $user)
    {
        $this->authorize('update',$user);

        $rules = [
            'email' => 'required|unique:users,email,' . $user->id,
            'password' => 'required|confirmed|min:6|max:16',
        ];

        $messages = [
            'email.required' => '请输入邮箱',
            'email.unique' => '邮箱已存在',
            'password.required' => '密码不能为空',
            'password.confirmed' => '两次输入的密码不一致',
            'password.min' => '密码最少是6位',
            'password.max' => '密码长度不能超过16位',
        ];

        $this->validate($request, $rules, $messages);

        try {
            $user->password = $request->input('password');
            $user->save();
            return redirect()->route('users.edit_password', $user->id)->with('success', '密码修改成功!');
        } catch (\Exception $e) {
            \Log::error('密码修改错误');
            return back()->with('danger', '密码修改失败');
        }

    }

    /**
     * 关注和取消关注某个用户
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow(User $user)
    {
        try{
            Auth::user()->toggleFollow($user);

            return response()->json([
                'status' => 1,
                'msg' => 'success!',
                'user_id' => Auth::id(),
                'followable_id' => $user->id,
            ]);

        }Catch(\Exception $e){
            \Log::error('关注用户失败!');

            return response()->json([
                'status' => -1,
                'msg' => 'failed!',
                'user_id' => Auth::id(),
                'followable_id' => $user->id,
            ]);

        }
    }

    /**
     * 获得user的关注者
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followers(User $user)
    {
        $followers = $user->followers()->withCount(['topics','followers','replies'])->orderByDesc('pivot_created_at')->paginate(10);

        return view('users.followers',compact('user','followers'));
    }

    /**
     * 获得用户关注的人的
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followings(User $user)
    {
        $followings =  $user->followings()->withCount(['topics','followers','replies'])->orderByDesc('pivot_created_at')->paginate(10);
        return view('users.followings',compact('user','followings'));
    }
    /**
     * 用户的回复界面
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function replies(User $user)
    {
        $replies = $user->replies()->with('topic')->orderBy('updated_at','desc')->paginate(10);

        return view('users.replies',compact('user','replies'));
    }

    /**
     * 用户的文章界面
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function topics(User $user)
    {
        $topics = $user->topics()->with('category')->withCount(['votes','replies'])->orderBy('updated_at','desc')->paginate(10);

        return view('users.topics',compact('user','topics'));
    }
}
