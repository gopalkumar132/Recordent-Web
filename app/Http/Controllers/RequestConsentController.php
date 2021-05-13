<?php

namespace App\Http\Controllers;

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
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use Illuminate\Support\Collection;
use General;
use Illuminate\Support\Str;
use App\ConsentRequest;
use App\ConsentRequestDetail;
use App\Businesses;
use App\BusinessDueFees;
use App\BusinessPaidFees;
use App\Services\SmsService;
use Log;

class RequestConsentController extends Controller
{
	public function store(Request $request){
		$validator = Validator::make($request->all(), [
            'due_id' => 'required', // comma_seperated
            'contact_phone'=>'required',
            'customer_type'=>'required|in:INDIVIDUAL'
        ]);

        if($validator->fails()){
            if($request->report==1){
                return response()->json(['error'=>true,'message'=>'No Records Found For Mobile Number'], 401);
            }
        }
        $dueId = $request->due_id;
        $dueId = explode(",",$dueId);
        $dueId = array_unique($dueId);

        if(!count($dueId)){
            if($request->report==1){
                return response()->json(['error'=>true,'message'=>'No Records Found For Mobile Number'], 401);
            }
        }
		$name=$request->name;
		$mobile_number=$request->contact_phone;

        $canRequestConsent = General::requestConsentEligible(Auth::id(),$request->contact_phone,'INDIVIDUAL',null,$request->report);
        //getting records order by desc // last 24 hour // status 1
        if($canRequestConsent->count()){

            if($canRequestConsent->count()>=1){
                // return response()->json(['error'=>true,'message'=>'You have already raised consent for this user. You can raise consent maximum two times in last 24 hours.'], 401);
                return response()->json(['error'=>true,'message'=>'Consent request already under progress. You can raise another request only after '.date('d-m-Y', strtotime(Carbon::parse( $canRequestConsent->first()->created_at)->addDays(1))).' '.date('h:i A', strtotime($canRequestConsent->first()->created_at)).'. You can check the status of the consent request in Credit Reports'], 401);
            }
            //expire first link

            $canRequestConsentFirstRecord = $canRequestConsent->first();


            $next3Min = Carbon::createFromFormat('Y-m-d H:i:s', $canRequestConsentFirstRecord->created_at);
            $next3Min->addMinute(3);
            if($next3Min >=Carbon::now()){
                 return response()->json(['error'=>true,'message'=>'please wait for three minutes from your last request to request consent again.'], 401);
            }
            $canRequestConsentFirstRecord->is_expired_by_admin = 1;
            $canRequestConsentFirstRecord->update();
        }
        // else{
        //     $canRequestConsent = General::requestConsentEligible(Auth::id(),$request->contact_phone,'INDIVIDUAL',3,$request->report);
        //     //getting records order by desc // last 24 hour // status 3 records
        //     if($canRequestConsent->count()){
        //         $fisrt = $canRequestConsent->first();
        //         if($fisrt->response_valid_at>=Carbon::now()){
        //             return response()->json(['success'=>true,'lastRequestAccepted'=>true,'message'=>'No need to request again because your last consent request accepted by user. please refresh the page to view data.'], 200);
        //         }

        //     }
        // }
        $authId = Auth::id();
        $auth = User::with(['city','state'])->where('id',$authId)->first();
		$customerDues = StudentDueFees::with('profile')->whereHas('profile',function($q) use($name,$mobile_number){
            $q->where('contact_phone','LIKE',General::encrypt($mobile_number));
             if(!empty($name)){
                $q->where('person_name','LIKE',General::encrypt($name));
            }
        });
        $customerDues = $customerDues->where('added_by','!=',Auth::id());
        $customerDues = $customerDues->whereIn('id',$dueId);
        $customerDues = $customerDues->whereNull('deleted_at');
        $customerDues = $customerDues->get();

        if(!$customerDues->count()){
			if($request->report==1){
                return response()->json(['error'=>true,'message'=>'No Records Found For Mobile Number'], 404);
            }
		}

		$uniqueUrlCode = Str::random(10);
		$insert = [
			'added_by'=>Auth::id(),
			//'customer_id'=>$customerId,
			'customer_type'=>$request->customer_type,
			'created_at'=>Carbon::now(),
            'searched_at'=>Carbon::now(),
			'unique_url_code'=>$uniqueUrlCode,
			'status'=>0,
            'person_name'=>$name,
            'contact_phone'=>$mobile_number,
            'report'=>$request->report,
            'ip_address'=>request()->ip()
		];
		$insert = ConsentRequest::create($insert);
        if(count($customerDues)>0){
    	    foreach($customerDues as $customerDue){
                $insertDetail = [
                    'due_id'=>$customerDue->id,
                    'consent_request_id'=>$insert->id,
                ];
                ConsentRequestDetail::create($insertDetail);
            }
        }
        $otp = sprintf("%06d", mt_rand(1, 999999));
        $link = route('myconsent',[$uniqueUrlCode]);

        // Log::debug('b2c consent link = '.$link);
          if($auth->business_short=='')
        {
        $otpMessage = Auth::user()->business_name." has requested to view your report on Recordent. This report will consist of data reported to ";
        if($request->report==1){
            $otpMessage.= "Recordent. ";
        }else{
            $otpMessage.= "Recordent & Equifax. ";
        }
        $otpMessage.= "click ".$link. " to approve.";
        }
       else
       {
         $otpMessage = Auth::user()->business_short." has requested to view your report on Recordent. This report will consist of data reported to ";
        if($request->report=="Recordent Report"){
            $otpMessage.= "Recordent. ";
        }else{
            $otpMessage.= "Recordent & Equifax. ";
        }
        $otpMessage.= "click ".$link. " to approve.";
       }
        $smsService = new SmsService();
        $smsResponse = $smsService->sendSms($mobile_number,$otpMessage);
        if($smsResponse['fail_to_send']){
        	$insert->status = 2;
        	$insert->update();
            return response()->json(['error'=>true,'message'=>'server not responding'], 500);
        }
        if($smsResponse['sent']==1){

            $insert->status = 1;
        	$insert->update();


            $canRequestConsentAgain24Hour = true;
            $startCountDownTimer = true;
            $next3MinForCounDown ='';
            $currentTimeInMilli = '';
            $canRequestConsent = General::requestConsentEligible(Auth::id(),$request->contact_phone,'INDIVIDUAL',null,$request->report);
            if($canRequestConsent->count()){
                if($canRequestConsent->count()>=1){
                    $canRequestConsentAgain24Hour=false;
                }
                $canRequestConsentFirstRecord = $canRequestConsent->first();
                $next3MinForCounDown = Carbon::createFromFormat('Y-m-d H:i:s', $canRequestConsentFirstRecord->created_at);
                $next3MinForCounDown->addMinute(3);
                /*if($next3MinForCounDown >=Carbon::now()){
                    $canRequestConsentAgain = false;
                }*/
                $next3MinForCounDown = $next3MinForCounDown->format('F d,Y H:i:s');
                $currentTimeInMilli = Carbon::now()->format('F d,Y H:i:s');
            }
				if($request->report==1){
					$NotifyMessage= "Recordent Report ";
				}else{
					$NotifyMessage= "Recordent Comprehensive Report ";
				}
             // $request->session()->flash('myCustomSuccessMessage', 'We have sent consent request to user please wait for his response. We will notify you on your register number about user response.');
             return Response::json(['success' => true,'canRequestConsentAgain24Hour'=>$canRequestConsentAgain24Hour,'startCountDownTimer'=>$startCountDownTimer,'next3MinForCounDown'=>$next3MinForCounDown,'currentTimeInMilli'=>$currentTimeInMilli,'message'=>'Your consent request for '.$NotifyMessage.' was sent to the customer. We will send an SMS to your registered mobile number once the customer has provided their approval. This may take up to 24 hours. You can also check the status of the consent request in Credit Reports.'], 200);
        }else{
        	$insert->status = 2;
        	$insert->update();
        	return Response::json(['error' => true,'message'=>'Failed to sent otp'], 401);
        }


	}

    public function storeBusiness(Request $request){

        $validator = Validator::make($request->all(), [
            'due_id' => 'required', // comma_seperated
            'contact_phone'=>'required',
            'customer_type'=>'required|in:BUSINESS'
        ]);

        if($validator->fails()){
            if($request->report==1){
                return response()->json(['error'=>true,'message'=>'No Records Found For Mobile Number'], 401);
            }
        }
        $dueId = $request->due_id;
        $dueId = explode(",",$dueId);
        $dueId = array_unique($dueId);


        if(!count($dueId)){
            if($request->report==1){
                return response()->json(['error'=>true,'message'=>'No Records Found For Mobile Number'], 401);
            }
        }

        $unique_identification_number=$request->unique_identification_number;
        $mobile_number=$request->contact_phone;
        $business_name =$request->business_name;
        $address = $request->address;
        $state = $request->state;
        $city = $request->city;
        $pincode = $request->pincode;

        $states = State::where('name', $request->state)->first();
        if(isset($business_name)){
          $stateId= $states->id;
        } else {
          $stateId='';
        }

        $canRequestConsent = General::requestConsentEligible(Auth::id(),$request->contact_phone,'BUSINESS',null,$request->report);
        //getting records order by desc // last 24 hour // status 1
        if($canRequestConsent->count()){
            if($canRequestConsent->count()>=1){
                // return response()->json(['error'=>true,'message'=>'You have already raised consent for this user. You can raise consent maximum two times in last 24 hours.'], 401);
                return response()->json(['error'=>true,'message'=>'Consent request already under progress. You can raise another request only after '.date('d-m-Y', strtotime(Carbon::parse( $canRequestConsent->first()->created_at)->addDays(1))).' '.date('h:i A', strtotime($canRequestConsent->first()->created_at)).'. You can check the status of the consent request in Credit Reports'], 401);
            }
            //expire first link
            $canRequestConsentFirstRecord = $canRequestConsent->first();


            $next3Min = Carbon::createFromFormat('Y-m-d H:i:s', $canRequestConsentFirstRecord->created_at);
            $next3Min->addMinute(3);
            if($next3Min >=Carbon::now()){
                 return response()->json(['error'=>true,'message'=>'please wait for three minutes from your last request to request consent again.'], 401);
            }
            $canRequestConsentFirstRecord->is_expired_by_admin = 1;
            $canRequestConsentFirstRecord->update();
        }
        // else{
        //     $canRequestConsent = General::requestConsentEligible(Auth::id(),$request->contact_phone,'BUSINESS',3,$request->report);
        //     //getting records order by desc // last 24 hour // status 3 records
        //     if($canRequestConsent->count()){
        //         $fisrt = $canRequestConsent->first();
        //         if($fisrt->response_valid_at>=Carbon::now()){
        //             return response()->json(['success'=>true,'lastRequestAccepted'=>true,'message'=>'No need to request again because your last consent request accepted by user. please refresh the page to view data.'], 200);
        //         }

        //     }
        // }
        $authId = Auth::id();
        $auth = User::with(['city','state'])->where('id',$authId)->first();
        $customerDues = BusinessDueFees::with('profile')->whereHas('profile',function($q) use($unique_identification_number,$mobile_number){
            $q->where('concerned_person_phone','LIKE',General::encrypt($mobile_number));
             if(!empty($unique_identification_number)){
                $q->where('unique_identification_number','LIKE',General::encrypt($unique_identification_number));
            }
        });
        $customerDues = $customerDues->where('added_by','!=',Auth::id());
        $customerDues = $customerDues->whereIn('id',$dueId);
        $customerDues = $customerDues->whereNull('deleted_at');
        $customerDues = $customerDues->get();
        if(!$customerDues->count()){
            if($request->report==1){
                return response()->json(['error'=>true,'message'=>'No Records Found For Mobile Number'], 404);
            }
        }

        $uniqueUrlCode = Str::random(10);
        $insert = [
            'added_by'=>Auth::id(),
            //'customer_id'=>$customerId,
            'customer_type'=>$request->customer_type,
            'created_at'=>Carbon::now(),
            'searched_at'=>Carbon::now(),
            'unique_url_code'=>$uniqueUrlCode,
            'status'=>0,
            'unique_identification_number'=>$unique_identification_number,
            'concerned_person_phone'=>$mobile_number,
            'report'=>$request->report,
            'business_name' =>$business_name,
            'address' =>$address,
            'state' =>$stateId,
            'city' =>$city,
            'pincode' =>$pincode,
            'ip_address'=>request()->ip()
        ];

        $insert = ConsentRequest::create($insert);
        foreach($customerDues as $customerDue){
            $insertDetail = [
                'due_id'=>$customerDue->id,
                'consent_request_id'=>$insert->id,
            ];
            ConsentRequestDetail::create($insertDetail);
        }

        $otp = sprintf("%06d", mt_rand(1, 999999));
        $link= route('myconsent',[$uniqueUrlCode]);

        Log::debug('b2b link = '.$link);
         if(Auth::user()->business_short=='')
        {
          $member_name = Auth::user()->business_name;
        }
        else {
           $member_name = Auth::user()->business_short;
        }
        if($request->report==1) {
            $otpMessage = $member_name." has requested to view the Business report on Recordent. This report will consist of data reported to ";
        } else {
            $otpMessage = $member_name." has requested to view your Business report of " .$business_name ." on Recordent. This report will consist of data reported to ";
        } 
        if($request->report==1) {
            $otpMessage.= "Recordent. ";
        } else {
            $otpMessage.= "Recordent & Equifax. ";
        }
        $otpMessage.= "Click ".$link." to approve.";

        $smsService = new SmsService();
        $config_mobile_number=config('custom_configs.B2B_SMS_Number');
        $env_type = Config('app.env');
        
        if($request->report==1){
            $smsResponse = $smsService->sendSms($mobile_number,$otpMessage);
        } else {
            if ($env_type == "production") {
               $smsResponse = $smsService->sendSms($mobile_number,$otpMessage);
            } else {
              $smsResponse = $smsService->sendSms($config_mobile_number,$otpMessage);
              Log::debug("config_mobile_number ".$config_mobile_number);
            }
        }
        if($smsResponse['fail_to_send']){
            $insert->status = 2;
            $insert->update();
            return response()->json(['error'=>true,'message'=>'server not responding'], 500);
        }
        if($smsResponse['sent']==1){

            $insert->status = 1;
            $insert->update();

            $canRequestConsentAgain24Hour = true;
            $startCountDownTimer = true;
            $next3MinForCounDown ='';
            $canRequestConsent = General::requestConsentEligible(Auth::id(),$request->contact_phone,'BUSINESS',null,$request->report);
            if($canRequestConsent->count()){
                if($canRequestConsent->count()>=1){
                    $canRequestConsentAgain24Hour=false;
                }
                $canRequestConsentFirstRecord = $canRequestConsent->first();
                $next3MinForCounDown = Carbon::createFromFormat('Y-m-d H:i:s', $canRequestConsentFirstRecord->created_at);
                $next3MinForCounDown->addMinute(3);
                /*if($next3MinForCounDown >=Carbon::now()){
                    $canRequestConsentAgain = false;
                }*/
                $currentTimeInMilli = Carbon::now()->format('F d,Y H:i:s');
                $next3MinForCounDown = $next3MinForCounDown->format('F d,Y H:i:s');

            }
            if($request->report==1){
                    $NotifyMessage= "Recordent Report ";
                }else{
                    $NotifyMessage= "Recordent Comprehensive Report ";
                }
             // $request->session()->flash('myCustomSuccessMessage', 'We have sent consent request to user please wait for his response. We will notify you on your register number about user response.');
             return Response::json(['success' => true,'canRequestConsentAgain24Hour'=>$canRequestConsentAgain24Hour,'startCountDownTimer'=>$startCountDownTimer,'next3MinForCounDown'=>$next3MinForCounDown,'currentTimeInMilli'=>$currentTimeInMilli,'message'=>'Your consent request for '.$NotifyMessage.' was sent to the customer. We will send an SMS to your registered mobile number once the customer has provided their approval. This may take up to 24 hours. You can also check the status of the consent request in Credit Reports.'], 200);

        } else {
            $insert->status = 2;
            $insert->update();
            return Response::json(['error' => true,'message'=>'Failed to sent otp'], 401);
        }


    }


}
