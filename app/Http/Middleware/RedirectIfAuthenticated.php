<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Response;
class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        
        if (Auth::guard($guard)->check()) {
            if($request->ajax()){
                return response()->json(['error'=>true,'message'=>'Yor are already logged in.'], 500);
            }
            // return redirect(url('admin'));
        }else{
            if(!empty(Session::get('individual_client_id'))){
                if(Session::has('individual_client_report_type') && !empty(Session::get('individual_client_report_type'))){
                    return redirect()->route('front-business.dashboard');
                }
                else{
                    return redirect()->route('front-individual.dashboard');
                }
            }
        }

        return $next($request);
    }
}
