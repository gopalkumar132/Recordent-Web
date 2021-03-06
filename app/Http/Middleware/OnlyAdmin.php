<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class OnlyAdmin
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
        $role = strtolower(Auth::user()->role->name);
        if($role=="admin"){
            return $next($request);
        }
        return redirect('admin');
    }
}
