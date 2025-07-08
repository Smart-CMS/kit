<?php

namespace SmartCms\Kit\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;

class Maintenance
{
    public function handle(Request $request, Closure $next)
    {
        if (app('s')->get('system.maintenance', false) && ! request()->cookie('maintenance_bypass')) {
            abort(503);
        }

        return $next($request);
    }
}
