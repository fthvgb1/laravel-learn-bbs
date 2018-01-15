<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyRequest;
use App\Models\Reply;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $replies = Reply::paginate();
        return view('replies.index', compact('replies'));
    }

    public function show(Reply $reply)
    {
        return view('replies.show', compact('reply'));
    }

    public function create(Reply $reply)
    {
        return view('replies.create_and_edit', compact('reply'));
    }

    public function store(ReplyRequest $request)
    {
        $reply = new Reply();
        $reply->setAttribute('topic_id', $request->get('topic_id'));
        $reply->setAttribute('user_id', \Auth::id());
        $reply->setAttribute('content', $request->get('content'));
        $reply->save();
        return redirect()->back()->with('success', '回复成功.');
    }

    public function edit(Reply $reply)
    {
        $this->authorize('update', $reply);
        return view('replies.create_and_edit', compact('reply'));
    }

    public function update(ReplyRequest $request, Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->update($request->all());

        return redirect()->route('replies.show', $reply->id)->with('message', 'Updated successfully.');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('destroy', $reply);
        $reply->delete();

        return redirect()->back()->with('success', '删除成功.');
    }
}