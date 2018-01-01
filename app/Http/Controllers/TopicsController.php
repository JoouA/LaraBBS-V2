<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandlers;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use App\Models\Topic;
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
    public function create(Topic $topic)
    {
        $categories = Category::all();
        return view('topics.create_and_edit',compact('categories','topic'));
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
        dd($topic);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function edit(Topic $topic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Topic $topic)
    {
        //
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
