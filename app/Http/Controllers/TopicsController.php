<?php

namespace App\Http\Controllers;

use App\Events\CreateTopic;
use App\Events\CreateVote;
use App\Handlers\ImageUploadHandlers;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use App\Models\Link;
use App\Models\Topic;
use App\Models\User;
use App\Notifications\VoteNotification;
use Illuminate\Http\Request;
use Log;
use Auth;

class TopicsController extends Controller
{

    /**
     * TopicsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }


    /**
     * @param Request $request
     * @param Topic $topic
     * @param User $user
     * @param Link $link
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, Topic $topic, User $user, Link $link)
    {
//        $topics = Topic::with(['user','category'])->paginate();
        // withOrder 定义在Topic里面的scopeWithOrder方法
        $topics = $topic->withOrder($request->input('order'))->paginate();

        $active_users = $user->getActiveUsers();

        $links = $link->getAllCached();

        return view('topics.index', compact('topics', 'active_users', 'links'));
    }

    /**
     * 创建topic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();
        return view('topics.create', compact('categories'));
    }

    /**
     * @param TopicRequest $request
     * @param Topic $topic
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TopicRequest $request, Topic $topic)
    {
        try {
            $topic->fill($request->all());

            $topic->user_id = \Auth::id();

            $topic->save();

            event(new  CreateTopic(Auth::user(),$topic));

            return redirect()->to($topic->link())->with('success', '专题创建成功!');
        } catch (\Exception $e) {

            return back()->withInput($request->all())->with('danger', '专题创建失败!');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Topic $topic
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic)
    {
        //当用户访问某一个topic，就让view_count增加一

         $topic->increment('view_count');

        // url 矫正
        if (!empty($topic->slug) && $topic->slug != \request()->slug) {
            return redirect()->to($topic->link(), 301);
        }

        $replies = $topic->replies()->with('user')->orderBy('created_at', 'desc')->paginate(5);

        $vote_users = $topic->votes()->orderBy('pivot_created_at', 'desc')->get();

        return view('topics.show', compact('topic', 'replies', 'vote_users'));
    }

    /**
     * 编辑topic
     * @param Topic $topic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Topic $topic)
    {
        $this->authorize('edit', $topic);

        $categories = Category::all();
        return view('topics.edit', compact('topic', 'categories'));
    }

    /**
     * 更新Topic
     * @param Request $request
     * @param Topic $topic
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        try {
            $topic->update($request->all());
            return redirect()->to($topic->link())->with('success', '主题更新成功!');
        } catch (\Exception $e) {
            return back()->with('danger', '主题更新失败!');
        }
    }

    /**
     * @param Topic $topic
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);

        try {
            $topic->delete();
            return redirect()->route('topics.index')->with('success', '主题删除成功!');
        } catch (\Exception $e) {
            return back()->with('danger', '主题删除失败');
        }

    }


    /**
     * 编辑器的图片上传
     * @param Request $request
     * @param ImageUploadHandlers $uploader
     * @return array
     */
    public function uploadImage(Request $request, ImageUploadHandlers $uploader)
    {

        $data = [
            'success' => false,
            'msg' => '上传失败!',
            'file_path' => ''
        ];

        if ($file = $request->file('upload_file')) {
            // 保存照片到本地
            $result = $uploader->save($file, 'topics', \Auth::id(), 1024);

            if ($request) {
                $data['file_path'] = $result['path'];
                $data['msg'] = '上传成功';
                $data['success'] = true;
            }
        }

        return $data;

    }

    /**
     * 用户Topic点赞
     * @param Topic $topic
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote(Topic $topic)
    {
        $avatar = Auth::user()->avatar;
        $vote_id = \request()->vote_user;
        $topic_id = \request()->topic_id;
        $user_link = route('users.show', Auth::id());

        try {
            $result = $topic->votes()->toggle($vote_id);
            $type = count($result['attached']);

            // 如果是点赞就给通知
            if (! empty($type)){
                if (!Auth::user()->isAuthorOf($topic)){
                    $topic->user->notify(new VoteNotification(Auth::user(),$topic));
                }
                event(new CreateVote(Auth::user(),$topic));
            }


            return response()->json([
                'vote_id' => $vote_id,
                'topic_id' => $topic_id,
                'status' => 'success',
                'user_avatar' => $avatar,
                'user_link' => $user_link,
                'type' => $type,
            ]);
        } catch (\Exception $e) {
            Log::error($vote_id . ' id的用户赞' . $topic_id . ' id的专题失败');

            return response()->json([
                'vote_id' => $vote_id,
                'topic_id' => $topic_id,
                'status' => 'failed',
            ]);
        }

    }

    /**
     * 返回emojis
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function emojis()
    {
        return view('common.emoite');
    }
}
