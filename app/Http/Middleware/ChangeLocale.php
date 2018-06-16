<?php

namespace App\Http\Middleware;

use Closure;

class ChangeLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $language = $request->header('accept-language');
        if ($language) {
            preg_match('/^([A-Za-z-]+),?/', $language, $matches);
            \App::setLocale($matches ? $matches[1] : 'zh-CN');
        }
        return $next($request);
    }
}
