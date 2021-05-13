<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Students;
use App\StudentDueFees;
use App\StudentPaidFees;
use App\Businesses;
use App\BusinessDueFees;
use App\BusinessPaidFees;
use App\Dispute;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use Storage;
use General;
use App\Services\SmsService;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DisputesExport;

class DisputeController extends Controller{

	public function index(Request $request) {
    	if(Auth::user()->role_id == 1 || Auth::user()->role_id == 14){
    		$records = Dispute::select('*');
    	} else {
    		$records = Dispute::where('due_added_by',Auth::id());
    	}

		if($request->is_open==2){
			$records = $records->where('is_open',2);

		} else if($request->is_open == 3){
			$records = $records->whereIn('is_open',[1,2]);

		} else {
			$records = $records->where('is_open',1);
		}

		$fromDate = $request->from_date;
		$toDate = $request->to_date;

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

		$records = $records->orderBy('disputes.id','DESC')->paginate(25);

		$records = $records->appends(request()->query());
		return view('admin.dispute.index',compact('records'));
    }


	public function view($disputeId,Request $request) {
		 if(Auth::user()->role_id == 1 || Auth::user()->role_id == 14){
    	$data = Dispute::with('reason')->where('id',$disputeId)->first();
        }
        else{
		$data = Dispute::with('reason')->where('due_added_by',Auth::id())->where('id',$disputeId)->first();
		}
		$dueRecord = '';

		if(empty($data)){
			return redirect()->back()->with(['message' => "No record found", 'alert-type' => 'error']);
		}
		if($data->customer_type=='INDIVIDUAL'){
			$dueRecord = $data->individualDue;
		}else{
			$dueRecord = $data->businessDue;
		}

		return view('admin.dispute.view',compact('data','dueRecord'));
    }

    public function reject($disputeId,Request $request) {
         if(Auth::user()->role_id == 1 || Auth::user()->role_id == 14){
           $data = Dispute::where('id',$disputeId)->where('is_open',1)->first();
           }else{
		$data = Dispute::where('due_added_by',Auth::id())->where('id',$disputeId)->where('is_open',1)->first();
     	}
		if(empty($data)){
			return redirect()->back()->with(['message' => "No dispute record found to take action.", 'alert-type' => 'error']);
		}
		$data->action_taken = 'DISPUTE_REJECTED';
		$data->action_taken_at = Carbon::now();
		$data->created_at=Carbon::now();
		$data->is_open = 2;
		$data->save();
		//send sms to front customer
		$smsService = new SmsService();
		$message = "Your dispute is Rejected.";
		$smsResponse = $smsService->sendSms($data->frontIBProfile->mobile_number,$message);
		General::storeAdminNotificationForDisputeFromMemberSide($data->customer_type,$data->due_added_by,$disputeId,'DISPUTE_REJECTED');
		return redirect()->route('admin.dispute-list',$request->query())->with(['message'=>'Dispute rejected successfully.','alert-type'=>'success']);

    }

    public function deleteDue($disputeId,Request $request)
	{

		$authId = Auth::id();
		$validator = Validator::make($request->all(), [
           'due_id' => 'required',
           'dispute_id'=>'required',
           'delete_note' => 'required',
           'agree_terms' => 'required',
   		]);
   		if($validator->fails()) {
           return redirect()->back()->withErrors($validator);
        }
		$dueId = $request->input('due_id');
		$disputeId = $request->dispute_id;
		$deleteNote = $request->input('delete_note');
		$agreeTerms = $request->input('agree_terms');

        if(Auth::user()->role_id == 1 || Auth::user()->role_id == 14){
        $data = Dispute::where('id',$disputeId)->where('due_id',$dueId)->where('is_open',1)->first();
        }else {
		$data = Dispute::where('due_added_by',Auth::id())->where('id',$disputeId)->where('due_id',$dueId)->where('is_open',1)->first();
	     }
		if(empty($data)){
			return redirect()->back()->with(['message' => "No dispute record found to take action.", 'alert-type' => 'error']);
		}

		if($data->customer_type=='INDIVIDUAL'){
			$dueRecord = StudentDueFees::where('id',$dueId)->whereNull('deleted_at')->where('added_by',$authId)->first();
		}else{
			$dueRecord = BusinessDueFees::where('id',$dueId)->whereNull('deleted_at')->where('added_by',$authId)->first();
		}
		if(empty($dueRecord)){
			return redirect()->back()->with(['message' => "can not find due record.", 'alert-type' => 'error']);
		}
		if(!empty($dueRecord->proof_of_due)){
			Storage::disk('public')->delete($dueRecord->proof_of_due);
		}

		$dueRecord->deleted_at = Carbon::now();
		$dueRecord->delete_note = $deleteNote;
		$dueRecord->update();

		//mark as deleted for paid entries of this due
		if($data->customer_type=='INDIVIDUAL'){
			StudentPaidFees::whereNull('deleted_at')->where('added_by',$authId)->where('due_id',$dueId)->update([
				'deleted_at'=>Carbon::now(),
				'delete_note'=>$deleteNote
			]);
		}else{
			BusinessPaidFees::whereNull('deleted_at')->where('added_by',$authId)->where('due_id',$dueId)->update([
				'deleted_at'=>Carbon::now(),
				'delete_note'=>$deleteNote
			]);
		}
		$data->action_taken = 'RECORD_DELETED';
		$data->action_taken_at = Carbon::now();
		$data->created_at=Carbon::now();
		$data->is_open = 2;
		$data->save();
		//send sms to front customer
		$smsService = new SmsService();
		$message = "Your dispute is Accepted.";
		$smsResponse = $smsService->sendSms($data->frontIBProfile->mobile_number,$message);
		General::storeAdminNotificationForDisputeFromMemberSide($data->customer_type,$data->due_added_by,$disputeId,'RECORD_DELETED');
		return redirect()->route('admin.dispute-list',$request->query())->with(['message'=>'Record deleted successfully.','alert-type'=>'success']);
	}

	public function editDue($disputeId, Request $request)
	{
		//dd($request->all());
		$dueId = $request->input('due_id');
		$disputeId = $request->dispute_id;
		if(Auth::user()->role_id == 1 || Auth::user()->role_id == 14){
        $data = Dispute::where('id',$disputeId)->where('due_id',$dueId)->where('is_open',1)->first();
		}else{
		$data = Dispute::where('due_added_by',Auth::id())->where('id',$disputeId)->where('due_id',$dueId)->where('is_open',1)->first();
		}
		if(empty($data)){
			return redirect()->back()->with(['message' => "No dispute record found to take action.", 'alert-type' => 'error']);
		}

		$lte = 1000000000;
		if($data->customer_type=='INDIVIDUAL'){
			$lte=100000000;
		}
		$validator = Validator::make($request->all(), [
		   'due_date' => 'required|date',
		   'due_amount'=>'required|numeric|gt:0|lte:'.$lte,
		   'agree_terms' => 'required',
		   'proof_of_due'=>'mimes:jpeg,bmp,png,gif,svg,pdf',
		   'due_note'=>'nullable|string|max:300',
		]);

		if($validator->fails()) {
		   return redirect()->back()->withErrors($validator)->withInput();
		}

		if($data->customer_type=='INDIVIDUAL'){
			$dueData = StudentDueFees::where('id','=',$dueId)->whereNull('deleted_at')->first();
		}else{
			$dueData = BusinessDueFees::where('id','=',$dueId)->whereNull('deleted_at')->first();
		}
		if(empty($dueData)){
			return redirect()->back()->with(['message' => "can not find due record.", 'alert-type' => 'error']);
		}

		$proofOfDue ='';
		if(!empty($request->file('proof_of_due'))){
			$proofOfDue = Storage::disk('public')->put('proof_of_due', $request->file('proof_of_due'));
			if(!empty($dueData->proof_of_due)){
				Storage::disk('public')->delete($dueData->proof_of_due);
			}
		}
		$dataToUpdate =[
			'due_amount'=>$request->due_amount,
		    'due_date'=>$request->input('due_date'),
		    'due_note'=>$request->input('due_note'),
		    'updated_at'=>Carbon::now()
		];
		if(!empty($proofOfDue)){
			$dataToUpdate['proof_of_due']=$proofOfDue;
		}
		$dueData->update($dataToUpdate);
		$data->action_taken = 'RECORD_UPDATED';
		$data->action_taken_at = Carbon::now();
		$data->created_at = Carbon::now();
		$data->is_open = 2;
		$data->save();
		//send sms to front customer
		$smsService = new SmsService();
		$message = "Your dispute is Accepted.";
		$smsResponse = $smsService->sendSms($data->frontIBProfile->mobile_number,$message);
		General::storeAdminNotificationForDisputeFromMemberSide($data->customer_type,$data->due_added_by,$disputeId,'RECORD_UPDATED');
		return redirect()->route('admin.dispute-list',$request->query())->with(['message'=>'Record updated successfully.','alert-type'=>'success']);
	}

	public function export(Request $request)
	{
		$date = $request->input('date') ?? '0';
		//$loginId = Auth::user()->role_id;
		return Excel::download(new DisputesExport($date), 'DisputesRecords-' . Carbon::now() . '.xlsx');
	}
}
