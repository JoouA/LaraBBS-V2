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
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'votes']);
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


    /** 更新个人信息
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

    /** 返回用户的点赞文章
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function votes(User $user)
    {
        // 可以使用pivot来进行数据的排序
        $topics = $user->votes()->with('category')->withCount(['votes', 'replies'])->orderBy('pivot_created_at', 'desc')->paginate(5);

        return view('users.votes', compact('topics', 'user'));
    }

    /** 设置个人头像
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function avatar(User $user)
    {
        return view('users.avatar', compact('user'));
    }


    /** 更新个人头像
     * @param Request $request
     * @return string
     */
    public function updateAvatar(Request $request)
    {
        $user_id = '';
        if ($request->has('user_id')) {
            $user_id = $request->user_id;
        }


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
                    Auth::user()->update(['avatar' => $data['path']]);
                }
                return $data;
            }
            return '没有图片文件';
        }
        return '非使用slim cropper裁剪';
    }

    /** 显示重置密码的界面
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function passwordForm(User $user)
    {
        return view('users.password', compact('user'));
    }

    /**
     * 更新密码
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request, User $user)
    {
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
}
