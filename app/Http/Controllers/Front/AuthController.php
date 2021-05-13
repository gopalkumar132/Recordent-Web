<?php

namespace App\Http\Controllers\Front;

use App\User;
use App\Students;
use App\Businesses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Individuals;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use General;
use App\Services\SmsService;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailOtp;
use Log;
use Illuminate\Support\Facades\Mail as SendMail;

class AuthController extends Controller
{

    protected function register(Request $request)
    {
        //echo $request->input('utmsource'); die;
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|numeric|digits:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Invalid mobile number'
            ], 401);
        }
        $customerType = "global";
        $mobile_number = $request->input('mobile_number');
        if($request->input('utmsource')!="covid"){
        $check_customer_due = Students::where("contact_phone",General::encrypt($mobile_number))->first();
        $customerType = "individual";
        if (empty($check_customer_due)) {
            return response()->json([
                'error' => true,
                'message'=>'No Records found for this customer'
            ], 401);
        }
}
        $individual = Individuals::where('mobile_number', General::encrypt($mobile_number))
                                    ->where('status',1)
                                    ->first();

        if(empty($individual)){

            $individual = Individuals::create([
                'mobile_number'=>$mobile_number,
                'customer_type' => $customerType,
                'created_at'=>Carbon::now()
            ]);
        }

        $otp = sprintf("%06d", mt_rand(1, 999999));
        //$otpMessage = 'Your Recordent OTP is '.$otp;
        $otpMessage = $otp.' is your OTP for logging in on Recordent. Please do not share this with anyone.';
        $smsService = new SmsService();
        $smsResponse = $smsService->sendSms($mobile_number,$otpMessage);

        if($smsResponse['sent']==1){
            $individual->otp = $otp;
            $individual->update();

            return Response::json([
                'success' => true,
                'mobile_number' => $mobile_number,
                'message' => 'OTP sent to your mobile number'
            ], 200);
        }

        return Response::json([
            'error' => true,
            'message' => 'can not send OTP right now. Try again'
        ], 401);

    }


    protected function login(Request $request)
    {
        $mobile_number = $request->input('mobile_number');
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|numeric|digits:10',
            'otp'=>'required|numeric|digits:6',
        ]);

        if ($validator->fails()) {
            $errorHTML = '';

            foreach($validator->messages()->all() as $error){
                $errorHTML.= "<p>$error</p>";
            }

            return response()->json(['error' => true, 'message' => 'Invalid mobile number','message' => $errorHTML], 401);
        }


        $otp = $request->input('otp');

        $individual = Individuals::where('mobile_number', General::encrypt($mobile_number))
                                    ->where('otp','LIKE', General::encrypt($otp))
                                    ->where('status', 1)
                                    ->first();
        if(!$individual){
            return Response::json(['error' => true, 'message' => 'invalid otp'], 401);
        }

        $individual->otp = NULL;
        $individual->updated_at = Carbon::now();
        $individual->update();
        if($request->input('utmsource')!="covid"){
        Session::put('individual_client_id', $individual->id);
        Session::put('individual_client_mobile_number', $individual->mobile_number);

        $data = General::getBusinessBasicProfileInArray();
        if(count($data)){
            Session::put('individual_client_udise_gstn_sector_id', $data['sector_id'] ?? '');
            Session::put('individual_client_udise_gstn_sector_type', $data['sector_unique_identification_type']);
            Session::put('individual_client_udise_gstn_sector_type_text', $data['sector_unique_identification_type_text']);
        }

        Session::save();
}
        return Response::json(['success' => true], 200);
    }


    protected function business_register(Request $request)
    {
        $mobile_number = $request->input('mobile_number');
        $email = $request->input('email');
        $check_business_customer_due = array();

        if (!$mobile_number && !$email) {
            $validator = Validator::make($request->all(), [
                'mobile_number' => 'required|numeric|digits:10',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => true, 'message' => 'Enter valid mobile number or email'], 401);
            }
        }

        if ($mobile_number) {
            $check_business_customer_due = Businesses::where("concerned_person_phone", General::encrypt($mobile_number))->first();

            $individual_where_column_name = 'mobile_number';
            $individual_where_column_value = $mobile_number;
        } else {
            if ($email) {
                $check_business_customer_due = Businesses::where("email", General::encrypt($email))->first();
                $individual_where_column_name = 'email';
                $individual_where_column_value = $email;

                Log::debug('email dues = '.$check_business_customer_due);
            }
        }

        if (empty($check_business_customer_due)) {
            return response()->json([
                'error' => true,
                'message' => 'No Records found for this Business'
            ], 401);
        }

        $individual = Individuals::where($individual_where_column_name, General::encrypt($individual_where_column_value))
                                    ->where('status', 1)
                                    ->first();

        if(empty($individual)){
            $individual = Individuals::create([
                'mobile_number' => $mobile_number ? $mobile_number : 0,
                'email' => $email,
                'created_at' => Carbon::now(),
            ]);
        }

        $otp = sprintf("%06d", mt_rand(1, 999999));
        //$otpMessage = 'Your Recordent OTP is '.$otp;
        $otpMessage = $otp.' is your OTP for logging in on Recordent. Please do not share this with anyone.';

        if ($mobile_number) {

            $smsService = new SmsService();
            $smsResponse = $smsService->sendSms($mobile_number, $otpMessage);

            if($smsResponse['sent'] == 1){
                 $individual->otp = $otp;
                 $individual->update();

                 return Response::json([
                    'success' => true,
                    'mobile_number' => $mobile_number,
                    'message' => 'OTP sent to your mobile number'
                ], 200);
            }
        } else {
            try{
                SendMail::send('front.emails.send-otp-to-email', [
                    'otpMessage' => $otpMessage
                ], function($message) use ($email) {
                    $message->to($email)
                    ->subject("Recordernt OTP");
                });

                $individual->otp = $otp;
                $individual->update();

                return Response::json([
                    'success' => true,
                    'email' => $email,
                    'message' => 'OTP sent to your email'
                ], 200);
            }catch(JWTException $exception){
                $this->serverstatuscode = "0";
                $this->serverstatusdes = $exception->getMessage();
            }
        }

        return Response::json(['error' => true, 'message' => 'can not send OTP right now. Try again'], 401);
    }


    protected function business_login(Request $request)
    {
        Log::debug("business_login");
        $mobile_number = $request->input('mobile_number');
        $email = $request->input('email');

        Log::debug("email = ".$email);

        if($mobile_number !=''){
            $validator = Validator::make($request->all(), [
                'mobile_number' => 'required|numeric|digits:10',
                'otp'=>'required|numeric|digits:6',
            ]);

            if ($validator->fails()) {
                $errorHTML = '';

                foreach($validator->messages()->all() as $error){
                    $errorHTML.= "<p>$error</p>";
                }

                return response()->json([
                    'error' => true,
                    'message' => 'Invalid mobile number',
                    'message' => $errorHTML
                ], 401);
            }
        }

        if ($mobile_number) {
            $individual_where_column_name = "mobile_number";
            $individual_where_column_value = $mobile_number;
        } else {
            $individual_where_column_name = "email";
            $individual_where_column_value = $email;
        }


        $otp = $request->input('otp');
        $individual = Individuals::where($individual_where_column_name, General::encrypt($individual_where_column_value))
                                    ->where('otp','LIKE', General::encrypt($otp))
                                    ->where('status', 1)
                                    ->first();

        if(!$individual){
           return Response::json(['error' => true, 'message' => 'invalid otp'], 401);
        }

        $individual->otp = NULL;
        $individual->updated_at = Carbon::now();
        $individual->update();

        Session::put('individual_client_id', $individual->id);
        if ($mobile_number) {
            Session::put('individual_client_mobile_number', $individual->mobile_number);
        } else {
            Session::put('individual_client_email', $individual->email);
        }

        Session::put('individual_client_report_type', 'business_login');

        $data = General::getBusinessBasicProfileInArray();
        if(count($data)){
            Session::put('individual_client_udise_gstn_sector_id', $data['sector_id'] ?? '');
            Session::put('individual_client_udise_gstn_sector_type', $data['sector_unique_identification_type']);
            Session::put('individual_client_udise_gstn_sector_type_text', $data['sector_unique_identification_type_text']);
        }

        Session::save();
        return Response::json(['success' => true], 200);
    }

    public function logout(Request $request){//dd($request);
        Session::flush();
        return redirect()->route('your.reported.dues');
    }

    public function getLoginStatus(Request $request)
    {

        if (Auth::check() || !empty(Session::get('individual_client_id')) ) {
            return Response::json(['is_user_logged_in' => true], 200);
        }

        return Response::json(['is_user_logged_in' => false], 200);
    }
}
