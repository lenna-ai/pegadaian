<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Auth\Access\Response as ResponseAccess;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,... $roles): Response
    {
        if (!Auth::check()){
            throw new Exception("Please login the first", 1);
        }
        if (isset(Auth::user()->role)) {
            if (isset(Auth::user()->role[0])) {
                foreach ($roles as $value) {
                    if ($value ==Auth::user()->role[0]->name) {
                        return $next($request);
                    }
                }
            }
        }
        return response('Forbidden access.',403);
    }
}
