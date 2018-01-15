<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopicRequest;
use App\Models\Category;
use App\Models\Topic;
use App\Tools\ImageUploadTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function create(Topic $topic)
    {
        $categories = Category::all();
        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    public function index(Request $request, Topic $topic)
    {
        $topics = $topic->with('user', 'category')->withOrder($request->order)->paginate();
        return view('topics.index', compact('topics'));
    }

    public function show(Topic $topic, Request $request)
    {
        if (!empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }
        $replies = $topic->replies;
        return view('topics.show', compact('topic', 'replies'));
    }

    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = Auth::id();
        $topic->save();
        return redirect()->to($topic->link())->with('success', '添加成功！.');
    }

    public function edit(Topic $topic)
    {
        $this->authorize('update', $topic);
        $categories = Category::all();
        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    public function uploadImage(Request $request, ImageUploadTool $imageUploadTool)
    {
        // 初始化返回数据，默认是失败的
        $data = [
            'success' => false,
            'msg' => '上传失败!',
            'file_path' => ''
        ];
        // 判断是否有上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $imageUploadTool->save($request->upload_file, 'topics', \Auth::id(), 1024);
            // 图片保存成功的话
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg'] = "上传成功!";
                $data['success'] = true;
            }
        }
        return $data;
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());

        return redirect()->route('topics.show', [$topic->id, $topic->slug])->with('success', '编辑成功！');
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();

        return redirect()->route('topics.index')->with('message', '删除成功.');
    }
}