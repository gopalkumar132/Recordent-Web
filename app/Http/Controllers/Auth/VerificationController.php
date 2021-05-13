<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\User;
use Illuminate\Http\Request;
use App\Traits\MustSendEmail;
use App\Traits\MustVerifyEmail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use General;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails,MustSendEmail,MustVerifyEmail;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = 'admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    protected function redirectTo(){
     
        /*$adminData = User::where('role_id',1)->first();
        if(!empty($adminData)){

            $mailData = ['name'=>$adminData->name,
                         'email'=> $adminData->email,
                         'subject'=> 'New user verified',
                         'business_name'=>Auth::user()->business_name,
                         'business_email'=>Auth::user()->email
                         ];

             Mail::send('front.emails.after-verification', $mailData, function ($message) use ($mailData){
                $message->from(\Config::get('mail.from.address'), \Config::get('mail.from.name')); 
                $message->to($mailData['email']);
                $message->subject($mailData['subject']);
            });
        }*/
        $user = Auth::user();
        $user->status =1;
        $user->save();
        //Auth::logout();
        Session::put('status', 'Your email successfully verified. Please login to continue.');
        //Session::put('status', 'Your email successfully verified. You can login to your account once our admin activate your profile.');
        return url("admin");
    }
    public function verify(Request $request)
    {
        // if ($request->route('id') != $request->user()->getKey()) {
        //     throw new AuthorizationException;
        // }

        $user = User::find($request->route('id'));

        auth()->login($user);

        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect($this->redirectPath())->with('verified', true);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authVerify(Request $request)
    { 	
		//Auth::logout();
		return view('admin.verification-email-resend');
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emailResend(Request $request)
    { 	
		Auth::user()->sendEmailVerificationNotification();
		Auth::user()->markEmailAsSent();
		//Auth::logout();
		//return view('admin.verification-email-resend');
		return redirect(url('admin/auth/verify'));
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changeEmail(Request $request)
    { 	//dd($request->user()->id);
        if ($request->user()) {
			$userId = encrypt($request->user()->id);
            return view('auth.passwords.change-email', compact('userId'));
        }
		return redirect()->back();
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateEmail(Request $request)
    {	
		$this->validate($request,['userId'=>'required','email'=>'required|email']);
        $alreadyExists = User::where('email','LIKE',General::encrypt($request->email))->first();
        if(!empty($alreadyExists)){
            return redirect()->back()->withInput()->withErrors(['can not change email. The email has already been taken.']);
        }
		
		//dd($request->input('userId'));
        if ($request->input('userId')) {
			$userId = decrypt($request->input('userId'));
			if($request->input('email')){
				$user = User::whereId($userId)->first();
				if($user){
					$user->update(['email'=>General::encrypt($request->email)]);
				}
			}			
							  
        	//$request->user()->sendEmailVerificationNotification();

        	//return back()->with('resent', true);
        }

        $request->user()->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')->with('resent', true);
    }
}
