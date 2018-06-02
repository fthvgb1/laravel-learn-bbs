<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 2018/6/2
 * Time: 21:36
 */

namespace App\Observers;


use App\Models\Link;
use Cache;

class LinkObserver
{
    public function saved(Link $link)
    {
        Cache::forget($link->cache_key);
    }
}