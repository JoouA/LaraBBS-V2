<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandlers;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use App\Models\Link;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class TopicsController extends Controller
{

    /**
     * TopicsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
    }


    /**
     * @param Request $request
     * @param Topic $topic
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request,Topic $topic,User $user,Link $link)
    {
//        $topics = Topic::with(['user','category'])->paginate();
        // withOrder 定义在Topic里面的scopeWithOrder方法
        $topics = $topic->withOrder($request->input('order'))->paginate();

        $active_users = $user->getActiveUsers();

        $links = $link->getAllCached();

        return view('topics.index',compact('topics','active_users','links'));
    }

    /**
     * 创建topic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();
        return view('topics.create',compact('categories'));
    }

    /**
     * @param TopicRequest $request
     * @param Topic $topic
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TopicRequest $request,Topic $topic)
    {
        try{
            $topic->fill($request->all());

            $topic->user_id = \Auth::id();

            $topic->save();

            return redirect()->to($topic->link()) ->with('success','专题创建成功!');
        }catch(\Exception $e){

            return back()->withInput($request->all())->with('danger','专题创建失败!');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic)
    {
        // url 矫正
        if (!empty($topic->slug) && $topic->slug != \request()->slug ){
            return redirect()->to($topic->link(),301);
        }

        $replies = $topic->replies()->with('user')->orderBy('created_at','desc')->paginate(5);

        return view('topics.show',compact('topic','replies'));
    }

    /**
     * 编辑topic
     * @param Topic $topic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Topic $topic)
    {
        $this->authorize('edit',$topic);

        $categories = Category::all();
        return view('topics.edit',compact('topic','categories'));
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
        $this->authorize('update',$topic);

        try{
            $topic->update($request->all());
            return redirect()->to($topic->link())->with('success','主题更新成功!');
        }catch (\Exception $e){
            return back()->with('danger','主题更新失败!');
        }
    }

    /**
     * @param Topic $topic
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy',$topic);

        try{
            $topic->delete();
            return redirect()->route('topics.index')->with('success','主题删除成功!');
        }catch (\Exception $e){
            return back()->with('danger','主题删除失败');
        }

    }


    /**
     * 编辑器的图片上传
     * @param Request $request
     * @param ImageUploadHandlers $uploader
     * @return array
     */
    public function uploadImage(Request $request,ImageUploadHandlers $uploader)
    {

        $data = [
            'success'   => false,
            'msg'       => '上传失败!',
            'file_path' => ''
        ];

        if ($file = $request->file('upload_file')){
            // 保存照片到本地
            $result = $uploader->save($file,'topics',\Auth::id(),1024);

            if ($request){
                $data['file_path'] = $result['path'];
                $data['msg'] = '上传成功';
                $data['success'] = true;
            }
        }

        return $data;

    }
}
