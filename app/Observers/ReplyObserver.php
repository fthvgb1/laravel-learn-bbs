<?php

namespace App\Observers;

use App\Models\Reply;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        $reply->setAttribute('content', clean($reply->getAttribute('content'), 'user_topic_body'));
    }

    public function updating(Reply $reply)
    {
        //
    }

    public function created(Reply $reply)
    {
        $reply->topic->increment('reply_count', 1);
    }
}