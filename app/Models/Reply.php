<?php

namespace App\Models;

/**
 * Class Reply
 * @property int id
 * @property string content
 * @property string created_at
 * @property string updated_at
 * @property int user_id
 * @property int topic_id
 * @property User user
 * @property Topic topic
 * @package App\Models
 */
class Reply extends Model
{
    protected $fillable = ['content'];


    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
