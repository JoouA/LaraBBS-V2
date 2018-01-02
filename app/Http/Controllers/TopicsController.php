<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandlers;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use Mockery\Exception;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request,Topic $topic)
    {
//        $topics = Topic::with(['user','category'])->paginate();
        // withOrder 定义在Topic里面的scopeWithOrder方法
        $topics = $topic->withOrder($request->input('order'))->paginate();

        return view('topics.index',compact('topics'));
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

            return redirect()->route('topics.show',$topic->id)->with('success','专题创建成功!');
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
      return view('topics.show',compact('topic'));
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
            return redirect()->route('topics.show',$topic->id)->with('success','主题更新成功!');
        }catch (Exception $e){
            return back()->with('danger','主题更新失败!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topic $topic)
    {
        //
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
