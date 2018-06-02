<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    public function root()
    {
        return view('layouts.root');
    }

    public function permissionDenied()
    {
        $is_allow = config('administrator.permission');
        if (($is_allow && is_callable($is_allow) && $is_allow() === true) || $is_allow === true) {
            return redirect(url(config('administrator.uri')), 302);
        }
        return view('pages.permission_denied');
    }
}
