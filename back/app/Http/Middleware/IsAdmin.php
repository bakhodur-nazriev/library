<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()->hasPermissionTo('admin')) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}