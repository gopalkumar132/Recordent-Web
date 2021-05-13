<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\User;
use View;
use App\Traits\MustSendEmail;
use App\Traits\MustVerifyEmail;

class CustomVerification
{
	use MustSendEmail,MustVerifyEmail;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		
		/*if(Auth::check()){
			if (Auth::user()->email_sent_at != NULL && Auth::user()->email_verify_at != NULL) {
				return $next($request);
			}elseif (Auth::user()->email_sent_at == NULL || Auth::user()->email_verify_at == NULL) {
				return view('auth.verify');
			}else{
				return redirect('/');
			}
		}else{
			abort(403, 'Unauthorized action.');
		}*/
		
		if(!Auth::check()){
			abort(403, 'Unauthorized action.');
		}
		//dd(Auth::user()->email_sent_at);
		return $next($request);
		if(!$this->hasSentEmail(Auth::user()) || !$this->hasVerifiedEmail()){			
			return redirect('admin/auth/verify');
		}else{
			return $next($request);
		}
		

		

        /*$User = User::findOrFail(Auth::id());
		
		if (! $User ||
            ($User instanceof MustVerifyEmail &&
            ! $this->hasSentEmail($User))) { dd($request->expectsJson());
            return $request->expectsJson()
                    ? abort(403, 'Verification email is not sent.')
                    : Redirect::route($redirectToRoute ?: 'home');
        }
		
		if($User && ($User->email_verify_at == NULL  || $User->email_sent_at == NULL)){
			//dd(123);
            return View('auth.verify');
        
		}
		else{
			return $next($request);
		}*/
		

        /*if ( Auth::check() && ($User->email_verify_at == NULL  || $User->email_sent_at == NULL))
		{
			return redirect('sent-verification');
		}
		else{
            return $next($request);
        }*/

        //return redirect('/');
    }
}
