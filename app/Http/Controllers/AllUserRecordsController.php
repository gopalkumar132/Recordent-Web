<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\User;
use App\Students;
use App\StudentDueFees;
use App\StudentPaidFees;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use Illuminate\Support\Collection;
use General;
use App\IndividualAdminReports;

class AllUserRecordsController extends Controller
{	
    public function studentRecords(Request $request) 
    { 	//dd($request);
		$userId = $request->input('userId');
		if(empty($userId)){
			return redirect('admin/users');
		}
		$AuthUser = Auth::user();
		if(!is_null($request->getQueryString()) && ($AuthUser->email_verified_at == NULL  || $AuthUser->email_sent_at == NULL))
		{
			//return redirect('admin/auth/verify');
		}
    	$authId = Auth::id();
    	$currentDate =Carbon::now();
    	if(!empty($request->input('due_date_period'))){
    		$records = Students::select('students.id','students.person_name','dob','father_name','mother_name','aadhar_number','contact_phone','due2.due_date', DB::raw('da - IF(pa,pa,0) as total'));
    	}else{
			$records = Students::select('students.id','students.person_name','dob','father_name','mother_name','aadhar_number','contact_phone','custom_student_id',
			'sdf.external_student_id','sdf.id as dueid', DB::raw('da - IF(pa,pa,0) as total'))
		->join('student_due_fees as sdf',function($q){
					$q->on('students.id','=','sdf.student_id');
				}); 
		}

		$records=$records->join(DB::raw('(SELECT sum(student_due_fees.due_amount) AS da,due_date,
		added_by,deleted_at,student_id,external_student_id from student_due_fees 
		WHERE added_by ='.$userId .' AND deleted_at is null 
		GROUP BY student_due_fees.student_id,student_due_fees.added_by,student_due_fees.external_student_id) due'),function($q){
			$q->on('students.id','=','due.student_id');
			$q->where('due.deleted_at','=',null);
			//$q->groupBy('due.external_student_id');
			//$q->where('due.added_by',$userId);

		});

		if(!empty($request->input('due_date_period'))){

			$dueDatePeriod = $request->input('due_date_period');
			if($dueDatePeriod=='less than 30days'){
				$dueDatePeriodRaw = " datediff(CURDATE(),due1.due_date) < 30 ";
			}elseif($dueDatePeriod=='30days to 90days'){
				$dueDatePeriodRaw = " datediff(CURDATE(),due1.due_date) <=90 AND datediff(CURDATE(),due1.due_date) >=30 ";
				
			}elseif($dueDatePeriod=='91days to 180days'){
				$dueDatePeriodRaw = " datediff(CURDATE(),due1.due_date) <=180 AND datediff(CURDATE(),due1.due_date) >=91 ";
			}elseif($dueDatePeriod=='181days to 1year'){
				$dueDatePeriodRaw = " datediff(CURDATE(),due1.due_date) <=365 AND datediff(CURDATE(),due1.due_date) >=181 ";
			}elseif($dueDatePeriod=='more than 1year'){
				$dueDatePeriodRaw = " datediff(CURDATE(),due1.due_date) >365 ";
			}else{
				$dueDatePeriodRaw = "1=1";
			}

				$records=$records->leftJoin(DB::raw("
				(SELECT due_date,added_by,deleted_at,student_id from student_due_fees due1 WHERE $dueDatePeriodRaw AND deleted_at is null ) due2"),function($q) use($userId){
							$q->on('students.id','=','due2.student_id');
							$q->where('due2.deleted_at','=',null);
							$q->where('due2.added_by',$userId);
							//$q->where(DB::raw('datediff(CURDATE(),due2.due_date)'),'>',150);
			});	
		}


			$records=$records->leftJoin(DB::raw('(SELECT sum(student_paid_fees.paid_amount) AS pa,
			added_by,deleted_at,student_id,external_student_id from student_paid_fees 
			WHERE added_by='.$userId .' AND deleted_at is NULL 
			GROUP BY student_paid_fees.student_id,student_paid_fees.added_by,student_paid_fees.external_student_id) paid'),function($q) {
								$q->on('students.id','=','paid.student_id');
								$q->where('paid.deleted_at','=',null);
								//$q->where('paid.added_by',$userId);
							});
		
			if(!empty($request->input('student_first_name'))){
				
				$records = $records->where('person_name' , 'LIKE' , General::encrypt($request->input('student_first_name')));
			}	

			if(!empty($request->input('student_dob'))){
				$dob = Carbon::createFromFormat('Y-m-d',$request->input('student_dob'));
				$records = $records->whereDate('dob',$dob);
			}	

			if(!empty($request->input('father_first_name'))){
				$records = $records->where('father_name','LIKE',General::encrypt($request->input('father_first_name')));
			}	
			if(!empty($request->input('mother_first_name'))){
				$records = $records->where('mother_name','LIKE',General::encrypt($request->input('mother_first_name')));
			}
			if(!empty($request->input('aadhar_number'))){
				$records = $records->where('aadhar_number','=',General::encrypt(str_replace('-','',$request->input('aadhar_number'))));
			}
			if(!empty($request->input('contact_phone'))){
				$records = $records->where('contact_phone','LIKE',General::encrypt($request->input('contact_phone')));
			}

			//$records = $records->where('student_due_fees.added_by',$authId);
			//$records = $records->where('student_paid_fees.added_by',$authId);	
			//$records = $records->where('students.added_by',$authId);

			if(!empty($request->input('due_date_period'))){
				$records = $records->whereNotNull('due2.due_date');
				$records = $records->groupBy('due2.external_student_id');
			}else{
				$records = $records->where('sdf.added_by','=',$userId);
				$records = $records->where('sdf.deleted_at','=',NULL);
				$records = $records->groupBy('sdf.student_id');
				$records = $records->groupBy('sdf.added_by');
				$records = $records->groupBy('sdf.external_student_id');
			}

			//$records = $records->groupBy('students.id');
			//$records = $records->where('due.added_by',$authId);
			//$records = $records->where('paid.added_by',$authId);

			
			
			if(!empty($request->input('due_amount'))){
				$dueAmount = $request->input('due_amount');
				
				if($dueAmount=='less than 1000'){
					$records = $records->having('total','<',1000);
					$records = $records->having('total','>',0);
					
				}elseif($dueAmount=='1000 to 5000'){
					$records = $records->having('total','<=',5000);
					$records = $records->having('total','>=',1000);

				}elseif($dueAmount=='5001 to 10000'){
					$records = $records->having('total','<=',10000);
					$records = $records->having('total','>=',5001);
				}elseif($dueAmount=='10001 to 25000'){
					$records = $records->having('total','<=',25000);
					$records = $records->having('total','>=',10001);
				}elseif($dueAmount=='25001 to 50000'){
					$records = $records->having('total','<=',50000);
					$records = $records->having('total','>=',25001);
				}elseif($dueAmount=='more than 50000'){
					$records = $records->having('total','>',50000);
				}
			}	
			$records = $records->orderBy('students.id','DESC');
			$records = $records->get();

			
			if($records->count()){
				foreach ($records as &$record) {
					$record->delayNumber = StudentDueFees::where('student_id',$record->id)->count();
				}
			}
			$businessUser = User::where('id','=',$userId)->first();
			$businessName = $businessUser->business_name;
			return view('admin.students-by-user.my-records',compact('records','userId','businessName'));
    }
	
	
	public function getStudentRecords(Request $request) 
    { 	
		//DB::enableQueryLog();
		//$userId = $request->input('userId');
		$userId = Auth::id();
		/*if(empty($userId)){
			return redirect('admin/users');
		}*/
		$AuthUser = Auth::user();
		if(!is_null($request->getQueryString()) && ($AuthUser->email_verified_at == NULL  || $AuthUser->email_sent_at == NULL))
		{
			//return redirect('admin/auth/verify');
		}
    	$authId = Auth::id();
    	$currentDate =Carbon::now();
    	if(!empty($request->input('due_date_period'))){
    		$records = Students::select('students.id','students.person_name','dob','father_name',
			'mother_name','aadhar_number','contact_phone','due2.due_date','custom_business_id', 
			DB::raw('da - IF(pa,pa,0) as total'));
    	}else{
			$records = Students::select('students.id','students.person_name','dob','father_name',
			'mother_name','aadhar_number','contact_phone','custom_student_id',
			'sdf.external_student_id','sdf.id as dueid', DB::raw('da - IF(pa,pa,0) as total'))
				->join('student_due_fees as sdf',function($q){
					$q->on('students.id','=','sdf.student_id');
					//$q->groupBy('students.id');
					//$q->groupBy('sdf.added_by');
					//$q->groupBy('sdf.external_student_id');
				}); 
		}
			
		$records=$records->join(DB::raw('(SELECT sum(student_due_fees.due_amount) AS da,due_date,
		added_by,deleted_at,student_id,external_student_id from student_due_fees 
		WHERE added_by ='.$userId .' AND deleted_at is null 
		GROUP BY student_due_fees.student_id,student_due_fees.added_by,student_due_fees.external_student_id) due'),function($q){
			$q->on('students.id','=','due.student_id');
			$q->where('due.deleted_at','=',null);
			//$q->groupBy('due.external_student_id');
			//$q->where('due.added_by',$userId);

		});

		if(!empty($request->input('due_date_period'))){

			$dueDatePeriod = $request->input('due_date_period');
			if($dueDatePeriod=='less than 30days'){
				$dueDatePeriodRaw = " datediff(CURDATE(),due1.due_date) < 30 ";
			}elseif($dueDatePeriod=='30days to 90days'){
				$dueDatePeriodRaw = " datediff(CURDATE(),due1.due_date) <=90 AND datediff(CURDATE(),due1.due_date) >=30 ";
				
			}elseif($dueDatePeriod=='91days to 180days'){
				$dueDatePeriodRaw = " datediff(CURDATE(),due1.due_date) <=180 AND datediff(CURDATE(),due1.due_date) >=91 ";
			}elseif($dueDatePeriod=='181days to 1year'){
				$dueDatePeriodRaw = " datediff(CURDATE(),due1.due_date) <=365 AND datediff(CURDATE(),due1.due_date) >=181 ";
			}elseif($dueDatePeriod=='more than 1year'){
				$dueDatePeriodRaw = " datediff(CURDATE(),due1.due_date) >365 ";
			}else{
				$dueDatePeriodRaw = "1=1";
			}

			$records=$records->leftJoin(DB::raw("
				(SELECT due_date,added_by,deleted_at,student_id from student_due_fees due1 WHERE $dueDatePeriodRaw AND deleted_at is null ) due2"),function($q) use($userId){
							$q->on('students.id','=','due2.student_id');
							$q->where('due2.deleted_at','=',null);
							$q->where('due2.added_by',$userId);
							//$q->where(DB::raw('datediff(CURDATE(),due2.due_date)'),'>',150);
			});	
		}


			$records=$records->leftJoin(DB::raw('(SELECT sum(student_paid_fees.paid_amount) AS pa,
			added_by,deleted_at,student_id,external_student_id from student_paid_fees 
			WHERE added_by='.$userId .' AND deleted_at is NULL 
			GROUP BY student_paid_fees.student_id,student_paid_fees.added_by,student_paid_fees.external_student_id) paid'),function($q) {
								$q->on('students.id','=','paid.student_id');
								$q->where('paid.deleted_at','=',null);
								//$q->where('paid.added_by',$userId);
							});
			
		
			if(!empty($request->input('student_first_name'))){
				$records = $records->where('person_name' , 'LIKE' , General::encrypt($request->input('student_first_name')));
			}	

			if(!empty($request->input('student_dob'))){
				$dob = Carbon::createFromFormat('Y-m-d',$request->input('student_dob'));
				$records = $records->whereDate('dob',$dob);
			}	

			if(!empty($request->input('father_first_name'))){
				$records = $records->where('father_name','LIKE',General::encrypt($request->input('father_first_name')));
			}	
			if(!empty($request->input('mother_first_name'))){
				$records = $records->where('mother_name','LIKE',General::encrypt($request->input('mother_first_name')));
			}
			if(!empty($request->input('aadhar_number'))){
				$records = $records->where('aadhar_number','=',General::encrypt(str_replace('-','',$request->input('aadhar_number'))));
			}
			if(!empty($request->input('contact_phone'))){
				$records = $records->where('contact_phone','LIKE',General::encrypt($request->input('contact_phone')));
			}

			//$records = $records->where('student_due_fees.added_by',$authId);
			//$records = $records->where('student_paid_fees.added_by',$authId);	
			//$records = $records->where('students.added_by',$authId);

			if(!empty($request->input('due_date_period'))){
				$records = $records->whereNotNull('due2.due_date');
				$records = $records->groupBy('due2.external_student_id');
			}else{
				$records = $records->where('sdf.added_by','=',$userId);
				$records = $records->where('sdf.deleted_at','=',NULL);
				$records = $records->groupBy('sdf.student_id');
				$records = $records->groupBy('sdf.added_by');
				$records = $records->groupBy('sdf.external_student_id');
			}

			//$records = $records->groupBy('students.id');
			//$records = $records->where('due.added_by',$authId);
			//$records = $records->where('paid.added_by',$authId);

			
			
			if(!empty($request->input('due_amount'))){
				$dueAmount = $request->input('due_amount');
				
				if($dueAmount=='less than 1000'){
					$records = $records->having('total','<',1000);
					$records = $records->having('total','>',0);
					
				}elseif($dueAmount=='1000 to 5000'){
					$records = $records->having('total','<=',5000);
					$records = $records->having('total','>=',1000);

				}elseif($dueAmount=='5001 to 10000'){
					$records = $records->having('total','<=',10000);
					$records = $records->having('total','>=',5001);
				}elseif($dueAmount=='10001 to 25000'){
					$records = $records->having('total','<=',25000);
					$records = $records->having('total','>=',10001);
				}elseif($dueAmount=='25001 to 50000'){
					$records = $records->having('total','<=',50000);
					$records = $records->having('total','>=',25001);
				}elseif($dueAmount=='more than 50000'){
					$records = $records->having('total','>',50000);
				}
			}	
			$records = $records->orderBy('students.id','DESC')->paginate(25);
			// $records = $records->get();

			//echo "<pre>"; print_r($records); die;
			//dd($records);
			if($records->count()){
				foreach ($records as &$record) {
					$record->delayNumber = StudentDueFees::where('student_id',$record->id)->count();
				}
			}
		
			$businessUser = User::where('id','=',$userId)->first();
			$businessName = $businessUser->business_name;
			//$queries = DB::getQueryLog();
			//dd($queries);
			$Report_records=IndividualAdminReports::where('member_id',$authId)->get();

			return view('admin.students.students-customer-level',compact('records','userId','businessName','Report_records'));
    }
	
	
	public function studentData(Request $request,$studentId,$userId,$dueId=null)
	{ 
		if(isset($dueId)){
			$getCustomId = StudentDueFees::select('external_student_id')->where('student_id','=',$studentId)->where('id','=',$dueId);
		} else {
			$getCustomId = StudentDueFees::select('external_student_id')->where('student_id','=',$studentId);
		}	
		    $getCustomId = $getCustomId->first();
			$checkCustomId = $getCustomId->external_student_id;

		$studentDueData = StudentDueFees::select('student_due_fees.id As dueId','student_due_fees.student_id','due_amount','due_date','student_due_fees.created_at As ReportedAt','paid_amount','paid_date','due_note','customer_no','invoice_no','users.business_name','users.id as userId','user_types.name as userType', 'student_due_fees.added_by','student_due_fees.proof_of_due','student_due_fees.external_student_id')
										->leftJoin('student_paid_fees','student_due_fees.student_id','=','student_paid_fees.student_id')
										->leftJoin('users','users.id','=','student_due_fees.added_by')
										->leftJoin('user_types','users.user_type','=','user_types.id')
										->where('student_due_fees.student_id','=',$studentId)
										->where('student_due_fees.external_student_id', $checkCustomId)
										->whereNull('student_due_fees.deleted_at')
										->groupBy('student_due_fees.id');
		//if($request->notification==1){	
			$studentDueData = $studentDueData->where('student_due_fees.added_by','=',$userId);
		//}	

		$studentDueData = $studentDueData->orderBy('student_due_fees.created_at','DESC')
						  ->get();
						  
		$student = Students::where('id','=',$studentId)->first();
		//dd($studentDueData);
		$businessUser = User::where('id','=',$userId)->first();
		$businessName = $businessUser->business_name ?? '';
		
		$paid_records = StudentPaidFees::where('student_id','=',$studentId)->where('external_student_id', $checkCustomId)->where('due_id',0)->whereNotNull('payment_options_drop_down')->get();
		$settled_records = 0;
		foreach ($paid_records as $key => $value) {
		    	$settled_records = $value->payment_options_drop_down;
		}
		return view('admin.students-by-user.student-data',compact('studentDueData','student','studentId','userId','businessName','settled_records'));
	}

	public function paymentHistory(Request $request)
	{
		//return Response::json(['error' => true,'message'=>'Record not found. Please try again'], 300);
		$dueId = $request->input('due_id');
		$custom_id = $request->input('custom_id');
		$settled_records = $request->input('settled_records');
		$studentId = $request->input('studentId');
		if(isset($dueId)){
		if(empty($dueId)){
			return Response::json(['error' => true,'message'=>'Due id can not be null'], 300);
		 }
	    }
		$paymentHistory = StudentPaidFees::select('id','paid_date','paid_amount','paid_note','deleted_at','payment_options_drop_down')->whereNull('deleted_at')->orderBy('id','DESC');
		$paymentHistory1 = $paymentHistory->where('due_id', $dueId)->get();
		$exisitng_due_ids =[];

		$paid_history = StudentDueFees::where('external_student_id', $custom_id)->whereNull('deleted_at')->where('id', $dueId);
		$paid_history = $paid_history->withCount([
                        'paid AS totalPaid' => function ($query)  {
                        $query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at');
                    }
                    ]);
        $paid_history = $paid_history->first();
        $skip_record=0;
		foreach ($paymentHistory1 as $key => $value) {
		       $exisitng_due_ids = $value->id;
		        $exisitng_due_ids = explode(',', $exisitng_due_ids);
		      
		        
		}
		if($paid_history->due_amount-$paid_history->totalPaid <=0 ){
		   $skip_record = 1;
		 } else {
		   $skip_record = 0;
		 }
		if($skip_record==1){
			$paymentHistory=$paymentHistory1;
		}else {
		if(isset($settled_records)){
			

				$paymentHistory2 = StudentPaidFees::select('id', 'paid_date', 'paid_amount', 'paid_note', 'deleted_at','payment_options_drop_down')->whereNull('deleted_at')->orderBy('id', 'DESC');
				  $paymentHistory2 = $paymentHistory2->where('student_id', $studentId)->where('due_id',0)->where('external_student_id',$custom_id)->whereNotIn('id',$exisitng_due_ids)->get();
			}
		 }	
			
		 if(isset($paymentHistory2)){
        $paymentHistory=$paymentHistory2->merge($paymentHistory1);
        } else {
        	$paymentHistory=$paymentHistory1;
        }
		$paymentHistoryData=[];
		if($paymentHistory->count()){
			/*foreach ($paymentHistory as $payment) {
				$paymentHistoryData[]=[
					'id'=>$payment->id,
					'amount'=>$payment->paid_amount,
					'note'=>$payment->paid_note,
					'date'=>date('d F, Y H:i a',strtotime($payment->paid_date)),
					'deleted_at'=>$payment->deleted_at==null ? '' : $payment->deleted_at, 
				];
			}*/
			$withHtml = View('admin/students-by-user/payment-history', compact('paymentHistory'))->render();
			return Response::json(['success' => true,'noData'=>false,'paymentHistoryData'=>$withHtml], 200);
		}else{
			return Response::json(['success' => true,'message'=>'','noData'=>true], 200);
		}

		//return Response::json(['success' => true,'message'=>'','paymentHistory'=>$paymentHistory], 200);
	
	}
		
}
