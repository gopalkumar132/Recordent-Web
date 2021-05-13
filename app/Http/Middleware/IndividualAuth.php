<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class IndividualAuth
{
   
    public function handle($request, Closure $next)
    {
        if(empty(Session::get('individual_client_id'))){
            if($request->expectsJson()){
                return response()->json(['error'=>true,'message'=>'Please login to continue'], 401); 
            }else{
                return redirect()->route('your.reported.dues');
            }
        }
        return $next($request);
    }

}
