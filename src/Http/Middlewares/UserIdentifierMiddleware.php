<?php

namespace SmartCms\Kit\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class UserIdentifierMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $uuid = Session::getId();
        $uuid = substr($uuid, 0, 30);
        if (! Cookie::get('uuid')) {
            Cookie::queue('uuid', $uuid, 60 * 24 * 365);
        }

        return $next($request);
    }
}
