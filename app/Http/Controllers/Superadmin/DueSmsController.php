<?php

namespace App\Http\Controllers\Superadmin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Exception;
use App\IndividualBulkUploadIssues;
use Illuminate\Support\Str;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use Storage;
use General;
use App\DuesSmsLog;
use Session;
use App\Services\SmsService;
use Illuminate\Support\Collection;

class DueSmsController extends Controller
{
	public function index(Request $request){
		$AuthId = Auth::id();// dd($AuthId);
		$fromDate=$request->from_date;
		$toDate=$request->to_date;
		$records = new DuesSmsLog;
		$records = $records->whereHas('addedBy')->where('added_by','!=',$AuthId);

		if(!empty($fromDate) && !empty($toDate) ){
			$fromDate.=' 00:00:00'; 
			$toDate.=' 23:59:59';  
			$records = $records->where('created_at','>=',$fromDate)->where('created_at','<=',$toDate);
		}elseif(!empty($fromDate)){
            $fromDate.=' 00:00:00';
            $records = $records->where('created_at','>=',$fromDate);
        }elseif(!empty($toDate)){
            $toDate.=' 23:59:59';
            $records = $records->where('created_at','<=',$toDate);
        }

		if($request->customer_type=='INDIVIDUAL'){
			$records = $records->where('customer_type','=','Individual');
		}elseif($request->customer_type=='BUSINESS'){
			$records = $records->where('customer_type','=','Business');
		}


		if(empty($request->sms_status)){
			$records = $records->where('approve_reject_status',0);//pending approval
		}elseif($request->sms_status==1){
			$records = $records->where('approve_reject_status',1);// approved
		}else{
            $records = $records->where('approve_reject_status',2);// rejected
        }

		$records = $records->orderBy('id','DESC')->paginate(50);
		$records = $records->appends(request()->query());
		return view('superadmin.due-sms-notification.index',compact('records'));

	}	


	public function approveReject(Request $request){
		$validator = Validator::make($request->all(), [
            'sms_id' => 'required',
            'action'=>'required|in:APPROVE,REJECT',
        ]);

        if($validator->fails()){
            return response()->json(['error'=>true,'message'=>'Invalid Notification'], 401);            
        }

        $action = $request->action;
        $smsId = $request->sms_id;
        
        $smsNotification = DuesSmsLog::where('id',$smsId)->where('approve_reject_status',0)->first();
		if(empty($smsNotification)){
            return response()->json(['error'=>true,'message'=>'can not find sms notification'], 404);            
        }        

        $dateTime = Carbon::now();
        if($action=='REJECT'){
        	$smsNotification->approve_reject_status = 2;
        	$smsNotification->approve_reject_at = $dateTime;
        	$smsNotification->update();
        	return response()->json(['success'=>true,'message'=>'Rejected successfully','newStatus'=>'Rejected'], 200);
        }

        $smsService = new SmsService();
		$smsResponse = $smsService->sendSms($smsNotification->contact_phone,$smsNotification->message);
		$sent = true;
   		if($smsResponse['fail_to_send'] || !$smsResponse['sent']){
   			$sent = false;
   		}
   		if(!$sent){
   			return response()->json(['error'=>true,'message'=>'sms sending failed'], 500);
   		}
   		
		$smsNotification->status = 1;	     
        $smsNotification->approve_reject_status = 1;
    	$smsNotification->approve_reject_at = $dateTime;
        $smsNotification->update();
    	return response()->json(['success'=>true,'message'=>'Approved successfully','dateTime'=>$dateTime->format('F d, Y H:i'),'newStatus'=>'Approved'], 200);
	}


	public function approveRejectBulk(Request $request){
      	$validator = Validator::make($request->all(), [
            'ids' => 'required',
            'action'=>'required|in:APPROVE,REJECT',
        ]);

        if($validator->fails()) {
           return redirect()->back()
                       ->withErrors($validator)
                       ->withInput();
       }

        $action = $request->action;
        $smsId = explode(',',$request->ids);

        if(!count($smsId)){
        	return redirect()->back()->with(['message' => "Please select atleast one sms notification", 'alert-type' => 'error']);  
        }
        $smsNotification = DuesSmsLog::whereIn('id',$smsId)->where('approve_reject_status',0)->get();
		if(!$smsNotification->count()){
           return redirect()->back()->with(['message' => "can not find sms notification", 'alert-type' => 'error']);    
        }

        $dateTime = Carbon::now();
        if($action=='REJECT'){
        	foreach ($smsNotification as $data) {
        		$data->approve_reject_status = 2;
	        	$data->approve_reject_at = $dateTime;
	        	$data->update();
        	}
        	return redirect()->back()->with(['message' => "Rejected successfully'", 'alert-type' => 'success']);
        }
        $sent = true;
        $smsService = new SmsService();
		foreach ($smsNotification as $data) {
			$smsResponse = $smsService->sendSms($data->contact_phone,$data->message);
	   		if($smsResponse['fail_to_send'] || !$smsResponse['sent']){
	   			$sent = false;
	   		}else{
	   			$data->status = 1;	     
		        $data->approve_reject_status = 1;
		    	$data->approve_reject_at = $dateTime;
		        $data->update();
	   		}
		}
		
   		if(!$sent){
   			return redirect()->back()->with(['message' => "can not send sms to some phones.", 'alert-type' => 'error']); 
   		}
   		
    	return redirect()->back()->with(['message' => "Approved successfully", 'alert-type' => 'success']);
	}

}
