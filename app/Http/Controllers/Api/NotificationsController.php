<?php

namespace App\Http\Controllers\Api;

use App\Transformers\NotificationTransformer;

class NotificationsController extends Controller
{
    public function index()
    {
        $user = $this->user();
        $notifications = $user->notifications()->paginate(20);
        return $this->response->paginator($notifications, new NotificationTransformer());
    }

    /**
     * @return mixed
     * @throws \ErrorException
     */
    public function stats()
    {
        return $this->response->array([
            'unread_count' => $this->user()->notification_count,
        ]);
    }

    public function read()
    {
        $this->user()->markAsRead();
        return $this->response->noContent();
    }
}
