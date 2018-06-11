<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 2018/6/11
 * Time: 22:00
 */

namespace App\Transformers;


use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
{
    public function transform(DatabaseNotification $notification)
    {
        return [
            'id' => $notification->id,
            'type' => $notification->type,
            'data' => $notification->data,
            'read_at' => $notification->read_at instanceof Carbon ? $notification->read_at->toDateTimeString() : null,
            'created_at' => $notification->created_at instanceof Carbon ? $notification->created_at->toDateTimeString() : $notification->created_at,
            'updated_at' => $notification->updated_at instanceof Carbon ? $notification->updated_at->toDateTimeString() : $notification->updated_at,
        ];
    }
}