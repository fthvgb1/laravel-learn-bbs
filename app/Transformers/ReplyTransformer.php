<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 2018/6/10
 * Time: 21:52
 */

namespace App\Transformers;


use App\Models\Reply;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ReplyTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'topic'];

    public function transform(Reply $reply)
    {
        return [
            'id' => (int)$reply->id,
            'user_id' => (int)$reply->user_id,
            'topic_id' => (int)$reply->topic_id,
            'content' => $reply->content,
            'created_at' => $reply->created_at instanceof Carbon ? $reply->created_at->toDateTimeString() : $reply->created_at,
            'updated_at' => $reply->updated_at instanceof Carbon ? $reply->updated_at->toDateTimeString() : $reply->updated_at,
        ];
    }

    public function includeTopic(Reply $reply)
    {
        return $this->item($reply->topic, new TopicTransformer());
    }

    public function includeUser(Reply $reply)
    {
        return $this->item($reply->user, new UserTransformer());
    }
}