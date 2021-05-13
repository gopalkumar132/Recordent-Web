<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OnlyIndividual
{
   
    public function handle($request, Closure $next)
    {
    	if(!empty(Session::get('individual_client_report_type'))){
    		if($request->expectsJson()){
    			return response()->json(['error'=>true,'message'=>'Unauthorized'], 401); 
    		}else{
    			return redirect()->route('front-business.dashboard');
    		}
    	}
        return $next($request);
    }

}
