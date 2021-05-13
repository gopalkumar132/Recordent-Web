<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use General;
// use Illuminate\Auth\Middleware\Authenticate as Middleware;
class IsMember
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
        // if (! $request->expectsJson()) {
        //     return route('login');
        // }
        $role = strtolower(Auth::user()->role->name ?? '');
        if($role=="admin" || $role=="sub admin"){
            return $next($request);           
        }
        if(!empty(General::user_pricing_plan())&&General::user_pricing_plan_status()=='success'){
            if(!empty(Auth::user()->profile_verified_at)) {
					return $next($request);
			} else {
				return redirect(route('update-profile'));
			}
			
        }

        if (isset($request->credit_report_type) && !empty($request->credit_report_type) ) {
            return redirect(route('get-pricing-plan').'?credit_report_type='.$request->credit_report_type);
        }

        return redirect(route('get-pricing-plan')); 
    }
}
