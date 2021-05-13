<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Response;
use App\User;
use General;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\SmsService;
use Illuminate\Support\Facades\DB;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

//    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'admin/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function showLoginForm()
    {
        return redirect( url('/admin/login'));
    }
    public function getOtp(Request $request){
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|numeric|digits:10|regex:/^[6-9]\d{9}$/u',
        ]);

        if($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'Invalid mobile number'], 401);
        }
        $mobile_number = $request->input('mobile_number');
        $user= User::where('mobile_number',General::encrypt($mobile_number))->where('status',1)->first();

        if(empty($user)){
            return response()->json(['error'=>true,'message'=>'No account associated with this mobile number'], 401);
        }
        $otp = sprintf("%06d", mt_rand(1, 999999));
        //$otpMessage = 'Your Recordent OTP is '.$otp;
        $otpMessage = $otp.' is your OTP for logging in on Recordent. Please do not share this with anyone.';
        $smsService = new SmsService();

        $smsResponse = $smsService->sendSms($mobile_number,$otpMessage);
        if($smsResponse['fail_to_send']){
            return response()->json(['error'=>true,'message'=>'server not responding'], 500);
        }
        // $otp='123456';
        // $smsResponse['sent']=1;
        if($smsResponse['sent']==1){
             $user->otp = $otp;
             $user->update();
             return Response::json(['success' => true,'mobile_number'=>$mobile_number,'message'=>'OTP sent to your mobile number'], 200);
        }
        return Response::json(['error' => true,'message'=>'can not send OTP right now. Try again'], 401);
    }

    public function loginWithOtp(Request $request)
    {
        $ruleMessage = [
            'mobile_number.regex' => 'Invalid Mobile Number.',
        ];

        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|numeric|digits:10|regex:/^[6-9]\d{9}$/u',
            'otp'=>'required|numeric|digits:6'
        ], $ruleMessage);

        if ($validator->fails()) {
            $errorHTML = '';
             foreach($validator->messages()->all() as $error){
                 $errorHTML.= "<p>$error</p>";
             }
             return response()->json(['error'=>true,'message'=>$errorHTML], 401);
        }

        $mobile_number = $request->input('mobile_number');
        $otp = $request->input('otp');
        $user = User::where('mobile_number',General::encrypt($mobile_number))->where('otp','LIKE',General::encrypt($otp))->where('status',1)->first();
        if(empty($user)){
            return Response::json(['error' => true,'message'=>'invalid otp'], 401);
        }

        $user->otp = NULL;
        $user->updated_at = Carbon::now();
        if(empty($user->mobile_verified_at) || $user->mobile_verified_at=='0000-00-00 00:00:00'){
            $user->mobile_verified_at = Carbon::now();
        }
        $user->update();

        if($request->input('campaign_id')!="") {
          DB::table('utm_containers_campaigns')
              ->where('id', $request->input('campaign_id'))
              ->update(array('lead_data' => $mobile_number, 'lead_type'=>2,'updated_at'=>Date('Y-m-d H:i:s')));
        }


        Auth::login($user);
        if($request->input('credit_report_redirect')) {
            return Response::json(['success' => true,'checkcredit'=>true], 200);
        } else if($request->input('membership_page_redirect')) {
            return Response::json(['success' => true,'membershippage'=>true], 200);
        } else if($request->input('help_support_redirect')) {
            return Response::json(['success' => true,'helpsupport'=>true], 200);
        }  else {
          return Response::json(['success' => true,'checkcredit'=>false,'membershippage'=>false,'helpsupport'=>false,'url'=> redirect()->intended()->getTargetUrl()], 200);
        }
    }


    public function loginWithEmailGetOtpToMobile(Request $request){
      //dd($request);
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password'=>'required'
        ]);

        if($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'Invalid credentials.'], 401);//
        }
        $email = strtolower($request->input('email'));
        $password = $request->input('password');
        $user= User::where('email',General::encrypt($email))->where('status',1)->first();

        if(empty($user)){
            return response()->json(['error'=>true,'message'=>'We dont have this email / phone number registered.'], 401);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json(['error'=>true,'message'=>'These credentials do not match our records.'], 401);
        }
        //Authorize login if mobile number is already verified. No need to send otp
        if(!empty($user->mobile_verified_at)){
          if($request->input('campaign_id')!="") {
            DB::table('utm_containers_campaigns')
                ->where('id', $request->input('campaign_id'))
                ->update(array('lead_data' => $request->input('email'), 'lead_type'=>2,'updated_at'=>Date('Y-m-d H:i:s')));
          }
            Auth::login($user);

            if($request->input('credit_report_redirect')) {
                return Response::json(['success' => true,'noNeedOtp'=>true,'checkcredit'=>true], 200);
            }   else if($request->input('membership_page_redirect')) {
                return Response::json(['success' => true,'noNeedOtp'=>true,'membershippage'=>true], 200);
            } else if($request->input('help_support_redirect')) {
                return Response::json(['success' => true,'noNeedOtp'=>true,'helpsupport'=>true], 200);
            }  
            else {
              return Response::json([
                    'success' => true,
                    'noNeedOtp' => true,
                    'checkcredit' => false,
                    'url' => redirect()->intended()->getTargetUrl()
                ], 200);
            }

        }
        //Authorize login is mobile number is not present. No need to send otp. // for old user.
        if(empty($user->mobile_number) || !empty($user->email_verified_at)){
            $user->mobile_verified_at = Carbon::now();
            $user->update();

            if($request->input('campaign_id')!="") {
              DB::table('utm_containers_campaigns')
                  ->where('id', $request->input('campaign_id'))
                  ->update(array('lead_data' => $request->input('email'), 'lead_type'=>2,'updated_at'=>Date('Y-m-d H:i:s')));
            }

            Auth::login($user);
            return Response::json(['success' => true,'noNeedOtp'=>true], 200);
        }

        $otp = sprintf("%06d", mt_rand(1, 999999));
        //$otpMessage = 'Your Recordent OTP is '.$otp;
        $otpMessage = $otp.' is your OTP for logging in on Recordent. Please do not share this with anyone.';

        $smsService = new SmsService();
        $smsResponse = $smsService->sendSms($user->mobile_number,$otpMessage);
        if($smsResponse['fail_to_send']){
            return response()->json(['error'=>true,'message'=>'server not responding'], 500);
        }
        if($smsResponse['sent']==1){
             $user->otp = $otp;
             $user->update();
             return Response::json([
                    'success' => true,
                    'message'=>'OTP sent to your mobile number xxxxxx'.substr($user->mobile_number,-4),
                    'email'=>$request->email,
                    'password'=>$request->password
                ],200);
        }
        return Response::json(['error' => true,'message'=>'can not send OTP right now. Try again'], 401);
    }

    public function loginWithEmailOtpToMobile(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password'=>'required',
            'otp'=>'required|numeric|digits:6'
        ]);

        if ($validator->fails()) {
            $errorHTML = '';
             foreach($validator->messages()->all() as $error){
                 $errorHTML.= "<p>$error</p>";
             }
             return response()->json(['error'=>true,'message'=>$errorHTML], 401);
        }

        $email = $request->input('email');
        $otp = $request->input('otp');
        $password = $request->input('password');
        $user = User::where('email',General::encrypt($email))->where('otp','LIKE',General::encrypt($otp))->where('status',1)->first();
        if(empty($user)){
            return Response::json(['error' => true,'message'=>'invalid otp'], 401);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json(['error'=>true,'message'=>'These credentials do not match our records.'], 401);
        }

        $user->otp = NULL;
        if(empty($user->mobile_verified_at) || $user->mobile_verified_at=='0000-00-00 00:00:00'){
            $user->updated_at = Carbon::now();
            $user->mobile_verified_at = Carbon::now();
        }
        $user->update();
        Auth::login($user);
        if($request->input('credit_report_redirect')) {
            return Response::json(['success' => true,'checkcredit'=>true], 200);
        } else if($request->input('membership_page_redirect')) {
            return Response::json(['success' => true,'membershippage'=>true], 200);
        } else if($request->input('help_support_redirect')) {
            return Response::json(['success' => true,'helpsupport'=>true], 200);
        }  else {
          return Response::json(['success' => true,'checkcredit'=>false,'membershippage'=>false,'helpsupport'=>false,'url'=> redirect()->intended()->getTargetUrl()], 200);
        }

    }

}
