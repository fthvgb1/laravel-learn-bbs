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
        if (config('administrator.permission')) {
            return redirect(url(config('administrator.uri')), 302);
        }
        return view('pages.permission_denied');
    }
}
