<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 2017/12/30
 * Time: 22:23
 */


function route_class()
{
    return str_replace('.', '-', \Illuminate\Support\Facades\Route::currentRouteName());
}