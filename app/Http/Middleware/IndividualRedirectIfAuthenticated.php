<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class IndividualRedirectIfAuthenticated
{
   
    public function handle($request, Closure $next)
    {
    	if(!empty(Session::get('individual_client_id'))){
    		if($request->expectsJson()){
    			return response()->json(['error'=>true,'message'=>'Already logged in'], 401); 
    		}else{
				// if(Session::has('individual_client_udise_gstn') && !empty(Session::get('individual_client_udise_gstn')))
                if(Session::has('individual_client_report_type') && !empty(Session::get('individual_client_report_type')))
                {

					return redirect()->route('front-business.business-records');
				}
				else{
                   
					return redirect()->route('front-individual.my-records');
				}
    		}
    	}
        return $next($request);
    }

}
