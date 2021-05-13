<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use General;
use App\User;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use Illuminate\Support\Collection;
use App\UserEmailMobileOtp;
use App\Notifications\EmailOtp;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;
use App\Services\SmsService;

class ProfileController extends Controller
{
	public function editMobile(Request $request){
		return view('admin.profile.edit-mobile');
	}

	public function editMobileGetOtp(Request $request){
		$validator = Validator::make($request->all(), [
            'mobile_number' => 'required|numeric|digits:10',
        ]);

        if ($validator->fails()) {
            $errorHTML = '';
             foreach($validator->messages()->all() as $error){
                 $errorHTML.= "<p>$error</p>";
             }
             return response()->json(['error'=>true,'message'=>$errorHTML], 401);
        }

        $mobile_number = $request->input('mobile_number');
        $user= User::where('mobile_number',General::encrypt($mobile_number))->first();
        if(!empty($user)){
        	if($user->id == Auth::id()){
        		return response()->json(['error'=>true,'message'=>'This mobile number is already linked to your account'], 401);
        	}
            return response()->json(['error'=>true,'message'=>'Mobile number already taken'], 401);
        }
        $otp = sprintf("%06d", mt_rand(1, 999999));
        $otpMessage = 'Your Recordent OTP is '.$otp;
        $smsService = new SmsService();
        $smsResponse = $smsService->sendSms($mobile_number,$otpMessage);
        if($smsResponse['fail_to_send']){
            return response()->json(['error'=>true,'message'=>'server not responding'], 500);
        }
       if($smsResponse['sent']==1){
        	 UserEmailMobileOtp::where('type',1)->where('added_by',Auth::id())->delete();
             UserEmailMobileOtp::create([
             	'mobile_number'=>$mobile_number,
             	'type'=>1,
             	'otp'=>$otp,
             	'added_by'=>Auth::id(),
             	'created_at'=>Carbon::now()
             ]);
             return Response::json(['success' => true,'mobile_number'=>$mobile_number,'message'=>'OTP sent to your mobile number'], 200);
        }
        return Response::json(['error' => true,'message'=>'can not send OTP right now. Try again'], 401);
	}

	public function updateMobile(Request $request){
		$validator = Validator::make($request->all(), [
            'mobile_number' => 'required|numeric|digits:10',
            'otp'=> 'required|numeric|digits:6',
        ]);

        if ($validator->fails()) {
            $errorHTML = '';
             foreach($validator->messages()->all() as $error){
                 $errorHTML.= "<p>$error</p>";
             }
             return response()->json(['error'=>true,'message'=>$errorHTML], 401);
        }
        $mobile_number = $request->input('mobile_number');
        $otp = $request->input('otp');
        $user= User::where('mobile_number',General::encrypt($mobile_number))->first();
        if(!empty($user)){
        	if($user->id == Auth::id()){
        		return response()->json(['error'=>true,'message'=>'This mobile number is already linked to your account'], 401);
        	}
            return response()->json(['error'=>true,'message'=>'Mobile number already taken'], 401);
        }

        $checkOtp = UserEmailMobileOtp::where('mobile_number',General::encrypt($mobile_number))
        			->where('otp','LIKE',General::encrypt($otp))
        			->where('added_by',Auth::id())
        			->where('type',1)
        			->first();
        if(empty($checkOtp)){
        	return response()->json(['error'=>true,'message'=>'Invalid otp'], 401);
        }
        $userId= Auth::id();
        General::storeAdminNotificationForProfile($names='Mobile',$requestData="",$userId);
        $checkOtp->delete();
        $user =Auth::user();
        $user->mobile_verified_at = Carbon::now();
        $user->mobile_number = $mobile_number;
        $user->update();
        return Response::json(['success' => true,'message'=>'Mobile number successfully updated.'], 200);

	}

    public function editEmail(Request $request){
        return view('admin.profile.edit-email');
    }

    public function editEmailGetOtp(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:100',
        ]);

        if ($validator->fails()) {
            $errorHTML = '';
             foreach($validator->messages()->all() as $error){
                 $errorHTML.= "<p>$error</p>";
             }
             return response()->json(['error'=>true,'message'=>$errorHTML], 401);
        }

        $email = $request->input('email');
        $user= User::where('email',General::encrypt($email))->first();
        if(!empty($user)){
            if($user->id == Auth::id()){
                return response()->json(['error'=>true,'message'=>'This email is already linked to your account'], 401);
            }
            return response()->json(['error'=>true,'message'=>'Email already taken'], 401);
        }
        $otp = sprintf("%06d", mt_rand(1, 999999));
             UserEmailMobileOtp::where('type',2)->where('added_by',Auth::id())->delete();
             UserEmailMobileOtp::create([
                'email'=>$email,
                'type'=>2,
                'otp'=>$otp,
                'added_by'=>Auth::id(),
                'created_at'=>Carbon::now()
             ]);
             $user =Auth::user();
             $user->email_sent_at = Carbon::now();
             $user->update();
             Notification::route('mail', $email)->notify(new EmailOtp($otp));
             return Response::json(['success' => true,'email'=>$email,'message'=>'OTP sent to your email address'], 200);

    }

    public function updateEmail(Request $request){
         $email_max_character= General::maxlength('email');
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:'.$email_max_character,
            'otp'=> 'required|numeric|digits:6',
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
        $user= User::where('email',General::encrypt($email))->first();
        if(!empty($user)){
            if($user->id == Auth::id()){
                return response()->json(['error'=>true,'message'=>'This email is already linked to your account'], 401);
            }
            return response()->json(['error'=>true,'message'=>'Email already taken'], 401);
        }

        $checkOtp = UserEmailMobileOtp::where('email',General::encrypt($email))
                    ->where('otp','LIKE',General::encrypt($otp))
                    ->where('added_by',Auth::id())
                    ->where('type',2)
                    ->first();
        if(empty($checkOtp)){
            return response()->json(['error'=>true,'message'=>'Invalid otp'], 401);
        }
        $userId= Auth::id();
        General::storeAdminNotificationForProfile($names='Email',$requestData="",$userId);
        $checkOtp->delete();
        $user =Auth::user();
        $user->email_verified_at = Carbon::now();
        $user->email = $email;
        $user->update();
        return Response::json(['success' => true,'message'=>'Email successfully updated.'], 200);
    }


    public function editPassword(Request $request){
        return view('admin.profile.change-password');
    }
    public function changePassword(Request $request){

        $rules = [
            'new_password'=>'required|min:6|max:15',
            'confirm_password'=>'same:new_password'
        ];
        $user = Auth::user();
        if(!empty($user->password)){
            $rules['old_password'] = 'required';
        }
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
           return redirect()->back()->withErrors($validator);
        }
        if(!empty($user->password)){
            if (!Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->withErrors(['Old password is incorrect.']);
            }
        }
        $user->password = Hash::make($request->new_password);
        $user->updated_at =Carbon::now();
        $user->update();
        return redirect()->back()->withMessage('Success: Password changed successfully.');
    }

}
