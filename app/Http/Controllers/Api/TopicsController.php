<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Models\User;
use App\Transformers\TopicTransformer;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $this->user()->id;
        $topic->save();
        return $this->response->item($topic, new TopicTransformer())->setStatusCode(201);
    }

    /**
     * @param TopicRequest $request
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());
        return $this->response->item($topic, new TopicTransformer());
    }

    public function index(Request $request)
    {
        $query = Topic::query();
        if ($category_id = $request->get('category_id', 0)) {
            $query->where(['category_id', $category_id]);
        }
        $order = $request->get('order', 'recent');
        $query->withOrder($query, $order);
        $topics = $query->paginate(20);
        return $this->response->paginator($topics, new TopicTransformer());
    }

    public function userIndex(User $user)
    {
        $topics = $user->topics()->recent()->paginate(20);
        return $this->response->paginator($topics, new TopicTransformer());
    }

    public function show(Topic $topic)
    {
        return $this->response->item($topic, new TopicTransformer());
    }

    /**
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->delete();
        return $this->response->noContent();
    }
}
