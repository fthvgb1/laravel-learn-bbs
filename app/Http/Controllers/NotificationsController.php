<?php

namespace App\Http\Controllers;

class NotificationsController extends Controller
{
    public function index()
    {
        $notifications = \Auth::user()->notifications()->paginate(20);
        \Auth::user()->markAsRead();
        return view('notifications.index', compact('notifications'));
    }
}
