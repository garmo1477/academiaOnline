<?php

namespace App\Http\Middleware;

use Closure;

class TeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $route = redirect(route('welcome'));
        if(!auth()->check() || !auth()->user()->isTeacher())
        {
            return $route;
        }       

        return $next($request);
    }
}
