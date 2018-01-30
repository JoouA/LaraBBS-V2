<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandlers;
use App\Zan;

class UsersController extends Controller
{
    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }

    /**
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
        $topics = $user->votes()->with('category')->withCount(['votes','replies'])->orderBy('pivot_created_at','desc')->paginate(5);

        return view('users.votes', compact('topics', 'user'));
    }
}
