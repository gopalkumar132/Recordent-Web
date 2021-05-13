<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Students;
use App\User;
use App\State;
use App\StudentDueFees;
use App\StudentPaidFees;
use App\MembershipPayment;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use PDF;
use Illuminate\Support\Collection;
use General;
use Illuminate\Support\Str;
use App\ConsentRequest;
use App\Businesses;
use App\BusinessDueFees;
use App\BusinessPaidFees;
use App\Services\SmsService;
use App\ConsentPayment;
use Mail;
use HomeHelper;
use Log;

class MyConsentController extends Controller
{
    public function index($uniqueUrlCode){
        //status 1 = Otp deliveried
        $currentDateTime = Carbon::now();

        $consentRequest = ConsentRequest::with(['addedBy','detail'])->where('unique_url_code',General::encrypt(strtolower($uniqueUrlCode)))
            ->where('status',1)
            ->whereRaw("created_at + INTERVAL 24 HOUR >=?",[$currentDateTime])
            ->where('is_expired_by_admin',2)
            ->whereNull('response_at')
            ->first();
          // dd(General::decrypt($consentRequest->address));
        $customerType = '';
        $dueRecords = collect([]);
        if(!empty($consentRequest)){
            $dueId = $consentRequest->detail->pluck('due_id')->toArray();
            $customerType = $consentRequest->customer_type;
            if($consentRequest->customer_type=='INDIVIDUAL'){
                $dueRecords = StudentDueFees::with(['addedBy','profile']);
            }else{
                $dueRecords = BusinessDueFees::with(['addedBy','profile']);
            }
             $dueRecords =  $dueRecords->whereHas('addedBy')
                                ->whereIn('id',$dueId)
                                ->whereNull('deleted_at')
                                ->orderBy('id','DESC')
                                ->get();
        }

        $states = State::where('country_id', 101)->get();
        $stateIds = [];
        foreach ($states as $state) {
            $stateIds[] = $state->id;
        }

        return view('front-ib/myconsent/index',compact('consentRequest','dueRecords','uniqueUrlCode','states'));
    }

     public function sendOtp(Request $request,$uniqueUrlCode){
        /*$validator = Validator::make($request->all(), [
            'otp'=>'required|numeric|digits:6'
        ]);

        if ($validator->fails()){
            $errorHTML = '';
             foreach($validator->messages()->all() as $error){
                 $errorHTML.= "<p>$error</p>";
             }
             return response()->json(['error'=>true,'message'=>$errorHTML], 401);
        }*/

        $currentDateTime = Carbon::now();
        $consentRequest = ConsentRequest::with('addedBy')->where('unique_url_code',General::encrypt(strtolower($uniqueUrlCode)))
            ->where('status',1)
            ->whereRaw("created_at + INTERVAL 24 HOUR >=?",[$currentDateTime])
            ->where('is_expired_by_admin',2)
            ->whereNull('response_at')
            ->first();
        $message = 'Invalid link or link expired';
        if(empty($consentRequest)){
            return response()->json(['error'=>true,'message'=>$message], 401);
        }
        $request_consent_response_otp_max_attempt = setting('admin.request_consent_response_otp_max_attempt') ? (int)setting('admin.request_consent_response_otp_max_attempt') : 1 ;
        $temp_response_otp_attemp_counter = $consentRequest->response_otp_attemp_counter +1;
        if($request_consent_response_otp_max_attempt < $temp_response_otp_attemp_counter){
            return  response()->json(['error'=>true,'message'=>'can not send otp after '.$request_consent_response_otp_max_attempt.' successful attempt.'], 401);
        }
        if($consentRequest->response_otp_attemp_counter>0){
            $next3Min = Carbon::createFromFormat('Y-m-d H:i:s', $consentRequest->response_otp_at);
            // $next3Min->addMinute(3);
            $next3Min->addSeconds(30);
            if($next3Min >=Carbon::now()){
                 // return response()->json(['error'=>true,'message'=>'please wait for three minutes from your last otp request to request again.'], 401);
                return response()->json(['error'=>true,'message'=>'Please wait for 30 seconds from your last otp request to request again.'], 401);
            }
        }


        if($consentRequest->customer_type=='INDIVIDUAL'){
            $mobile_number = $consentRequest->contact_phone;
        }elseif (!empty($request->authorized_mobile)) {
            $mobile_number = $request->authorized_mobile;
        } else {
            $mobile_number = $consentRequest->concerned_person_phone;
        }

        $otp = sprintf("%06d", mt_rand(1, 999999));
        Log::debug("otp = ".$otp);
        //$otpMessage = 'Your Recordent OTP is '.$otp;
        $otpMessage = $otp.' is your OTP for giving consent to view your report on Recordent. Please do not share this with anyone.';
        $config_mobile_number=config('custom_configs.B2B_SMS_Number');
        $env_type = Config('app.env');
        // Log::debug(print_r($env_type,true));
        $smsService = new SmsService();
          if($consentRequest->report==3){
            if ($env_type == "production") {
               $smsResponse = $smsService->sendSms($mobile_number,$otpMessage);
            } else {
              $smsResponse = $smsService->sendSms($config_mobile_number,$otpMessage);
            }
          } else {
            $smsResponse = $smsService->sendSms($mobile_number,$otpMessage);
          }
        if(!$smsResponse['fail_to_send']){
            if($smsResponse['sent']==1){
                $consentRequest->response_otp = $otp;
                $consentRequest->response_otp_at=Carbon::now();
                $consentRequest->response_otp_attemp_counter = $consentRequest->response_otp_attemp_counter +1;
                $consentRequest->update();

                $canRequestOtpAgain24Hour = true;
                $startCountDownTimer = true;
                $next3MinForCounDown ='';
                $currentTimeInMilli = '';
                if($consentRequest->response_otp_attemp_counter>=$request_consent_response_otp_max_attempt){
                    $canRequestOtpAgain24Hour=false;
                }
                $next3MinForCounDown = Carbon::createFromFormat('Y-m-d H:i:s', $consentRequest->response_otp_at);
                // $next3MinForCounDown->addMinute(3);
                $next3MinForCounDown->addSeconds(30);

                $next3MinForCounDown = $next3MinForCounDown->format('F d,Y H:i:s');
                $currentTimeInMilli = Carbon::now()->format('F d,Y H:i:s');

                return Response::json(['success' => true,'canRequestOtpAgain24Hour'=>$canRequestOtpAgain24Hour,'startCountDownTimer'=>$startCountDownTimer,'next3MinForCounDown'=>$next3MinForCounDown,'currentTimeInMilli'=>$currentTimeInMilli,'message'=>'OTP is sent to '.$mobile_number], 200);
            }
            return Response::json(['error' => true,'message'=>'can not send OTP right now. Try again'], 401);
        }else{
            $message = 'server not responding';
        }
        return response()->json(['error'=>true,'message'=>$message], 401);

    }

    public function accept(Request $request, $uniqueUrlCode){
        $validator = Validator::make($request->all(), [
            'otp'=>'required|numeric|digits:6'
        ]);

        if ($validator->fails()){
            $errorHTML = '';
             foreach($validator->messages()->all() as $error){
                 $errorHTML.= "$error<br>";
             }
             return response()->json(['error'=>true,'message'=>$errorHTML], 401);
        }

        $otp = $request->otp;
        $currentDateTime = Carbon::now();

        $consentRequest = ConsentRequest::with('addedBy')->where('unique_url_code',General::encrypt(strtolower($uniqueUrlCode)))
            ->where('status',1)
            ->whereRaw("created_at + INTERVAL 24 HOUR >=?",[$currentDateTime])
            ->whereNull('response_at')
            ->where('is_expired_by_admin',2)
            ->first();
        $message = 'Invalid link or link expired';
        if(empty($consentRequest)){
            return response()->json(['error'=>true,'message'=>$message], 401);
        }
        if($consentRequest->response_otp != $otp){
           return response()->json(['error'=>true,'message'=>'Invalid otp'], 401);
        }
        if($request->authorized_designation=="Others"){
            $request->authorized_designation = $request->type_of_others;
        }
        $count_consentRequest = ConsentRequest::where('added_by',$consentRequest->addedBy->id)->get();
        $count_rec_b2c = $count_consentRequest->where('report','2');
        $count_rec_b2b = $count_consentRequest->where('report','3');
        $free_limit_b2c = config('custom_configs.free_limit_b2c');
        $free_limit_b2b = config('custom_configs.free_limit_b2b');
        $total_free_reports=config('custom_configs.total_free_reports');

        // $consentRequest->response_otp = NULL;
        $consentRequest->status = 3;
        $consentRequest->response_at = Carbon::now();
        $currentTimePlusTenMins = Carbon::now()->addMinutes(10);
        $consentRequest->response_valid_at = $currentTimePlusTenMins;
        $consentRequest->person_name = $request->fullname;
        $consentRequest->business_name = $request->business_name;
        $consentRequest->address = $request->address;
        $consentRequest->state = $request->state;
        $consentRequest->city = $request->city;
        $consentRequest->pincode = $request->pincode;
        $consentRequest->company_id = $request->company_id;
        $consentRequest->authorized_signatory_name = $request->authorized_name;
        $consentRequest->authorized_signatory_dob = $request->authorized_dob;
        $consentRequest->link_contact_phone = $request->authorized_mobile;
        $consentRequest->directors_email = $request->directors_email;
        $consentRequest->authorized_signatory_designation = $request->authorized_designation;
        $consentRequest->idtype = $request->idtype;
        $consentRequest->idvalue = General::encrypt($request->idvalue);
        $consentRequest->update();

        $mobile_number = $consentRequest->addedBy->mobile_number ?? '';
        if(!empty($mobile_number)){

            $link = route('all-records');

            if($consentRequest->customer_type == 'INDIVIDUAL'){
                //$customer = Students::where('id',$consentRequest->customer_id)->first();
                $otpMessage='';
                if(!empty($consentRequest->person_name)){
                    // $otpMessage.=' ('.$consentRequest->person_name.')';
                    $otpMessage.=$consentRequest->person_name;
                }
                $otpMessage.=' ('.$consentRequest->contact_phone.')';
            }else{
                //$customer = Businesses::where('id',$consentRequest->customer_id)->first();
                $otpMessage='';
                // if(!empty($consentRequest->unique_identification_number)){
                //     $otpMessage.=$consentRequest->unique_identification_number;
                // }
                 if(!empty($consentRequest->business_name)){
                    $otpMessage.=$consentRequest->business_name;
                }
                $otpMessage.=' ('.$consentRequest->concerned_person_phone.')';
            }
            // $otpMessage.=' has accepted your consent request. Please refresh the page to view data.';
            $otpMessage.=' has accepted your request. Click '.$link.' to view the report in the Credit Reports tab.';
            $smsService = new SmsService();
            $smsResponse = $smsService->sendSms($mobile_number,$otpMessage);

        }

         // return Response::json(['success' => true,'message'=>'You have accepted the request.'], 200);
        $member = Ucfirst($consentRequest->addedBy->business_name) ?? '';
        $type = $consentRequest->addedBy->userType->name ?? '';

        // $invoice_no=MembershipPayment::where('created_at','>=',date('Y-m-d 00:00:00'))->where(function($q) {
        //   $q->where('status',4)
        //     ->orWhere('postpaid', 1);
        // })->count();
        $invoice_no = MembershipPayment::where('created_at','>=',date('Y-m-d 00:00:00'))->where('status',4)->count();
        $invoice_no=$invoice_no+1;

        $user=User::findOrFail($consentRequest->added_by);
        Log::debug($user);
        if($user->user_pricing_plan != NULL){

            if($consentRequest->report==2 || $consentRequest->report==3){
                if($consentRequest->report==2){
                    $consent_payment_value = $consentRequest->report == 2 ? HomeHelper::getConsentComprehensiveReportPrice($user) : HomeHelper::getConsentRecordentReportPrice($user);
                }

                if($consentRequest->report==3){
                    $consent_payment_value = $consentRequest->report == 3 ? HomeHelper::getConsentComprehensiveReportPrice($consentRequest->report==3,$user) : HomeHelper::getConsentRecordentReportPrice($user);
                }

                $consent_payment_value_gst_in_perc = HomeHelper::getConsentRecordentReportGst();
            } else {
                $consent_payment_value = setting('admin.consent_payment_value') ? (int)setting('admin.consent_payment_value') : 100 ;
                $consent_payment_value_gst_in_perc = setting('admin.consent_payment_value_gst_in_perc') ? (int)setting('admin.consent_payment_value_gst_in_perc') : 0 ;
            }


            // dd(2);

            if($consent_payment_value_gst_in_perc>0){
                $temp = ($consent_payment_value * $consent_payment_value_gst_in_perc)/100;
                $temp = round($temp);
                $temp = (int)$temp;
                $consent_payment_value_final = $consent_payment_value + $temp;
            }

            // if($consentRequest->report == 2 || $consentRequest->report == 3){
            if($consentRequest->report == 2){
                $invoice_type_id = $consentRequest->customer_type=="INDIVIDUAL" ? 3 : 5;
            }

            if($consentRequest->report == 3){
                $invoice_type_id = $consentRequest->customer_type=="BUSINESS" ? 2 : 4;
            }

            if($consentRequest->customer_type=="INDIVIDUAL"){
                $postpaid = $user->reports_individual==1 ? 1 : 0;
            }else{
                $postpaid = $user->reports_business==1 ? 1 : 0;
            }

            if($consentRequest->report == 3 || $consentRequest->report == 2){
                if($postpaid==1){
                    if(count($count_rec_b2c)<=$free_limit_b2c && count($count_rec_b2b)<=$free_limit_b2b && count($count_rec_b2b)+count($count_rec_b2c)<=$total_free_reports){

                        $valuesForMembershipPayment = [
                            'customer_id' => $user->id,
                            'invoice_id' => date('dmY').sprintf('%07d',$invoice_no),
                            'pricing_plan_id' =>0,
                            'customer_type' => $consentRequest->customer_type,
                            'payment_value' => $consent_payment_value,
                            'gst_perc' => $consent_payment_value_gst_in_perc,
                            'gst_value' => $temp,
                            'total_collection_value' => 0,
                            'particular' => ($consentRequest->customer_type=="INDIVIDUAL" ? "Individual " : "Business ").($consentRequest->report==1 ? "Recordent Report" : "Recordent Comprehensive Report"),
                            'consent_id' => $consentRequest->id,
                            'postpaid' => 0,
                            'status' => 4,
                            'discount' => $consent_payment_value_final,
                            'invoice_type_id' => $invoice_type_id
                        ];
                    } else {
                        $valuesForMembershipPayment = [
                            'customer_id' => $user->id,
                            'invoice_id' => date('dmY').sprintf('%07d',$invoice_no),
                            'pricing_plan_id' =>0,
                            'customer_type' => $consentRequest->customer_type,
                            'payment_value' => $consent_payment_value,
                            'gst_perc' => $consent_payment_value_gst_in_perc,
                            'gst_value' => $temp,
                            'total_collection_value' => $consent_payment_value_final,
                            'particular' => ($consentRequest->customer_type=="INDIVIDUAL" ? "Individual " : "Business ").($consentRequest->report==1 ? "Recordent Report" : "Recordent Comprehensive Report"),
                            'consent_id' => $consentRequest->id,
                            'postpaid' => $postpaid,
                            'status' => 4,
                            'invoice_type_id' => $invoice_type_id
                        ];
                    }

                    $consentPayment = [
                            'order_id' => Str::random(40),
                            'customer_type' => $consentRequest->customer_type,
                            'unique_identification_number' => $consentRequest->unique_identification_number,
                            'concerned_person_phone' => $consentRequest->concerned_person_phone,
                            'consent_id' => $consentRequest->id,
                            'payment_value' => $consent_payment_value_final,
                            'status' => 4, //initiated
                            'created_at' => Carbon::now(),
                            'added_by' => $consentRequest->added_by,
                            'business_name' =>$consentRequest->business_name,
                            'address' =>$consentRequest->address,
                            'state' =>$consentRequest->state,
                            'city' =>$consentRequest->city,
                            'pincode' =>$consentRequest->pincode,
                            'company_id' =>$consentRequest->company_id,
                            'authorized_signatory_name' =>$consentRequest->authorized_signatory_name,
                            'authorized_signatory_dob' =>$consentRequest->authorized_signatory_dob,
                            'directors_email' =>$consentRequest->directors_email,
                            'link_contact_phone' =>$consentRequest->link_contact_phone,
                            'authorized_signatory_designation' =>$consentRequest->authorized_signatory_designation,
                            'updated_at' => Carbon::now()
                        ];
                }
            }

            if($consentRequest->customer_type=="INDIVIDUAL"){
                if($user->reports_individual==1 && $consentRequest->report==2){
                    $membershipPayment = MembershipPayment::create($valuesForMembershipPayment);
                    $consent_payment = ConsentPayment::create($consentPayment);
                    Log::debug("INDIVIDUAL, reports_individual == 1 case ");
                }
            } else {
                if($user->reports_business==1){
                    if($consentRequest->report==3){
                        $membershipPayment = MembershipPayment::create($valuesForMembershipPayment);
                        $consent_payment = ConsentPayment::create($consentPayment);
                        Log::debug("else case, reports_business == 1 case ");
                    }
                }
            }
        }

        if(isset($membershipPayment)){
            // app('App\Http\Controllers\HomeController')->postpaid_invoice_sendmail($membershipPayment->id);
        }

         if($consentRequest->report == 2 || $consentRequest->report == 3){
            return Response::json(['success' => true,'message'=>"You have approved sharing credit profile with ".$member." on Recordent. Your credit profile will provide details of any records submitted to Recordent along with your credit data from Equifax."], 200);
         }else{
            return Response::json(['success' => true,'message'=>"You have approved sharing credit profile with ".$member." on Recordent. Your credit profile will provide details of any records submitted to Recordent."], 200);
         }
    }

    public function deny(Request $request, $uniqueUrlCode){
        // $validator = Validator::make($request->all(), [
        //     'otp'=>'required|numeric|digits:6'
        // ]);

        // if ($validator->fails()){
        //     $errorHTML = '';
        //      foreach($validator->messages()->all() as $error){
        //          $errorHTML.= "$error<br>";
        //      }
        //      return response()->json(['error'=>true,'message'=>$errorHTML], 401);
        // }
        // $otp = $request->otp;
        $currentDateTime = Carbon::now();
        $consentRequest = ConsentRequest::with('addedBy')->where('unique_url_code',General::encrypt(strtolower($uniqueUrlCode)))
            ->where('status',1)
            ->whereRaw("created_at + INTERVAL 24 HOUR >=?",[$currentDateTime])
            ->whereNull('response_at')
            ->where('is_expired_by_admin',2)
            ->first();
        $message = 'Invalid link or link expired';

        if(empty($consentRequest)){
            return response()->json(['error'=>true,'message'=>$message], 401);
        }
        // if($consentRequest->response_otp != $otp){
        //    return response()->json(['error'=>true,'message'=>'Invalid otp'], 401);
        // }

        $consentRequest->response_otp = NULL;
        $consentRequest->status = 4;
        $consentRequest->response_at = Carbon::now();
        $consentRequest->person_name = $request->fullname;
        $consentRequest->idtype = $request->idtype;
        $consentRequest->idvalue = General::encrypt($request->idvalue);
        $consentRequest->update();
        $mobile_number = $consentRequest->addedBy->mobile_number ?? '';
        if(!empty($mobile_number)){
            $link = route('all-records');
            if($consentRequest->customer_type == 'INDIVIDUAL'){
                //$customer = Students::where('id',$consentRequest->customer_id)->first();
                $otpMessage=$consentRequest->contact_phone;
                // if(!empty($consentRequest->person_name)){
                //     $otpMessage.=' ('.$consentRequest->person_name.')';
                // }
            }else{
                //$customer = Businesses::where('id',$consentRequest->customer_id)->first();
                $otpMessage=$consentRequest->concerned_person_phone;
                // if(!empty($consentRequest->unique_identification_number)){
                //     $otpMessage.=' ('.$consentRequest->unique_identification_number.')';
                // }
            }
            // $otpMessage.=' has denied your consent request.';
            $otpMessage.=' has rejected your request to view the credit report. Click '.$link.' to check the status.';
            $smsService = new SmsService();
            $smsResponse = $smsService->sendSms($mobile_number,$otpMessage);
        }
         return Response::json(['success' => true,'message'=>'You have rejected sharing your credit profile. We will notify '.$consentRequest->addedBy->business_name], 200);


    }


    public function thankyou(){
        return view('front-ib/myconsent/response');
    }

    public function storeIndividualCustomCreditReport(Request $request) {

      $name_max_character= General::maxlength('name');
      $rule = [
  			'full_name' => 'required|max:'.$name_max_character.'|regex:/^[\pL\s]+$/u',
  			'customer_pan' => 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/i',
  			'registered_ip' => 'required|regex:/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/',
  			'registration_date' => 'required|date_format:d/m/Y',
  			'contact_phone' => 'required|numeric|digits:10|starts_with:6,7,8,9',
  			'otp_code' => 'required',
  			'otp_generated_time' => 'required|date_format:d/m/Y',
  			'otp_verified' => 'required'
  		];
  		$ruleMessage =
  			[
  				'full_name.regex' => 'The :attribute may only contain letters and space.',
  			];
  		$validator = Validator::make($request->all(), $rule, $ruleMessage);
  		if ($validator->fails()) {
  			return redirect()->back()->withErrors($validator)->withInput();
  		}

          $insert = [
          'added_by'=>Auth::id(),
          'customer_type'=>'INDIVIDUAL',
          'created_at'=>Carbon::createFromFormat('d/m/Y', $request->registration_date)->toDateTimeString(),
          'searched_at'=>Carbon::now(),
          'status'=>3,
          'person_name'=>$request->full_name,
          'contact_phone'=>$request->contact_phone,
          'report'=>2,
          'ip_address'=>$request->registered_ip,
          'unique_identification_number'=>$request->customer_pan,
          'response_otp'=>$request->otp_code,
          'response_otp_at'=>Carbon::createFromFormat('d/m/Y', $request->otp_generated_time)->toDateTimeString()
          ];
  		$insertId = ConsentRequest::create($insert)->id;

      $consentRequest = ConsentRequest::where('id',$insertId)->first();

      $count_consentRequest = ConsentRequest::where('added_by',$consentRequest->addedBy->id)->get();
      $count_rec_b2c = $count_consentRequest->where('report','2');
      $count_rec_b2b = $count_consentRequest->where('report','3');
      $free_limit_b2c = config('custom_configs.free_limit_b2c');
      $free_limit_b2b = config('custom_configs.free_limit_b2b');
      $total_free_reports=config('custom_configs.total_free_reports');

      $invoice_no = MembershipPayment::where('created_at','>=',date('Y-m-d 00:00:00'))->where('status',4)->count();
      $invoice_no=$invoice_no+1;

      $user=User::findOrFail($consentRequest->added_by);
      Log::debug($user);
      if($user->user_pricing_plan != NULL){
          if($consentRequest->report==2 || $consentRequest->report==3){
              if($consentRequest->report==2){
                  $consent_payment_value = $consentRequest->report == 2 ? HomeHelper::getConsentComprehensiveReportPrice($user) : HomeHelper::getConsentRecordentReportPrice($user);
              }

              $consent_payment_value_gst_in_perc = HomeHelper::getConsentRecordentReportGst();
          } else {
              $consent_payment_value = setting('admin.consent_payment_value') ? (int)setting('admin.consent_payment_value') : 100 ;
              $consent_payment_value_gst_in_perc = setting('admin.consent_payment_value_gst_in_perc') ? (int)setting('admin.consent_payment_value_gst_in_perc') : 0 ;
          }

          if($consent_payment_value_gst_in_perc>0){
              $temp = ($consent_payment_value * $consent_payment_value_gst_in_perc)/100;
              $temp = round($temp);
              $temp = (int)$temp;
              $consent_payment_value_final = $consent_payment_value + $temp;
          }

          if($consentRequest->report == 2){
              $invoice_type_id = $consentRequest->customer_type=="INDIVIDUAL" ? 3 : 5;
          }


          if($consentRequest->customer_type=="INDIVIDUAL"){
              $postpaid = $user->reports_individual==1 ? 1 : 0;
          }else{
              $postpaid = $user->reports_business==1 ? 1 : 0;
          }

          if($consentRequest->report == 3 || $consentRequest->report == 2){
              if($postpaid==1){
                  if(count($count_rec_b2c)<=$free_limit_b2c && count($count_rec_b2b)<=$free_limit_b2b && count($count_rec_b2b)+count($count_rec_b2c)<=$total_free_reports){

                      $valuesForMembershipPayment = [
                          'customer_id' => $user->id,
                          'invoice_id' => date('dmY').sprintf('%07d',$invoice_no),
                          'pricing_plan_id' =>0,
                          'customer_type' => $consentRequest->customer_type,
                          'payment_value' => $consent_payment_value,
                          'gst_perc' => $consent_payment_value_gst_in_perc,
                          'gst_value' => $temp,
                          'total_collection_value' => 0,
                          'particular' => ($consentRequest->customer_type=="INDIVIDUAL" ? "Individual " : "Business ").($consentRequest->report==1 ? "Recordent Report" : "Recordent Comprehensive Report"),
                          'consent_id' => $consentRequest->id,
                          'postpaid' => 0,
                          'status' => 4,
                          'discount' => $consent_payment_value_final,
                          'invoice_type_id' => $invoice_type_id
                      ];
                  } else {
                      $valuesForMembershipPayment = [
                          'customer_id' => $user->id,
                          'invoice_id' => date('dmY').sprintf('%07d',$invoice_no),
                          'pricing_plan_id' =>0,
                          'customer_type' => $consentRequest->customer_type,
                          'payment_value' => $consent_payment_value,
                          'gst_perc' => $consent_payment_value_gst_in_perc,
                          'gst_value' => $temp,
                          'total_collection_value' => $consent_payment_value_final,
                          'particular' => ($consentRequest->customer_type=="INDIVIDUAL" ? "Individual " : "Business ").($consentRequest->report==1 ? "Recordent Report" : "Recordent Comprehensive Report"),
                          'consent_id' => $consentRequest->id,
                          'postpaid' => $postpaid,
                          'status' => 4,
                          'invoice_type_id' => $invoice_type_id
                      ];
                  }

                  $consentPayment = [
                          'order_id' => Str::random(40),
                          'customer_type' => $consentRequest->customer_type,
                          'unique_identification_number' => $consentRequest->unique_identification_number,
                          'concerned_person_phone' => $consentRequest->concerned_person_phone,
                          'consent_id' => $consentRequest->id,
                          'payment_value' => $consent_payment_value_final,
                          'status' => 4, //initiated
                          'created_at' => Carbon::now(),
                          'added_by' => $consentRequest->added_by,
                          'business_name' =>$consentRequest->business_name,
                          'address' =>$consentRequest->address,
                          'state' =>$consentRequest->state,
                          'city' =>$consentRequest->city,
                          'pincode' =>$consentRequest->pincode,
                          'company_id' =>$consentRequest->company_id,
                          'authorized_signatory_name' =>$consentRequest->authorized_signatory_name,
                          'authorized_signatory_dob' =>$consentRequest->authorized_signatory_dob,
                          'directors_email' =>$consentRequest->directors_email,
                          'link_contact_phone' =>$consentRequest->link_contact_phone,
                          'authorized_signatory_designation' =>$consentRequest->authorized_signatory_designation,
                          'updated_at' => Carbon::now()
                      ];
              }
          }

              if($user->reports_individual==1 && $consentRequest->report==2){
                  $membershipPayment = MembershipPayment::create($valuesForMembershipPayment);
                  $consent_payment = ConsentPayment::create($consentPayment);
                  Log::debug("Custom INDIVIDUAL, reports_individual == 1 case ");
              }
              //return redirect()->back()->withMessage('Success: Request raised successfully. You can check the status in Report History tab');
              return redirect('admin/individual-custom-creditreports')->with('successpopup', 'Request raised successfully. You can check the status in Report History tab');
      } else {
              return redirect()->back()->withMessage('error: Failed to generate credit report');
      }



    }
}
