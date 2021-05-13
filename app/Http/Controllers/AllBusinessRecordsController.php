<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use PhpOffice\PhpSpreadsheet\Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Sector;
use App\State;
use App\City;
use App\Businesses;
use App\BusinessDueFees;
use App\BusinessPaidFees;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use Storage;
use General;
use App\DuesSmsLog;
use App\User;
use App\BusinessAdminReports;
use Log;

class AllBusinessRecordsController extends Controller
{

    public function index(Request $request) 
    { 
		$User = Auth::user();
		if(!is_null($request->getQueryString()) && ($User->email_verified_at == NULL  || $User->email_sent_at == NULL))
		{
			//return redirect('admin/auth/verify');
		}
    	$sectors = Sector::whereNull('deleted_at')->where('status',1)->get();
    	$states = State::where('country_id',101)->get(); 
	    $stateIds = []; 
	    foreach ($states as $state){
	       $stateIds[] =$state->id; 
	    } 
	    $cities = City::whereIn('state_id',$stateIds)->get();
    	$userId = $request->input('userId');
		if(empty($userId)){
			return redirect('admin/users');
		}
		$authId = Auth::id();
    	$currentDate =Carbon::now();
    	if(!empty($request->input('due_date_period'))){
    		$records = Businesses::select('businesses.unique_identification_number','businesses.custom_business_id','businesses.concerned_person_name','businesses.concerned_person_phone','businesses.id','businesses.company_name','businesses.sector_id','businesses.state_id','businesses.city_id','businesses.added_by','due2.due_date', DB::raw('da - IF(pa,pa,0) as total'));
    	}else{

			$records = Businesses::select('businesses.unique_identification_number',
			'businesses.custom_business_id','businesses.concerned_person_name',
			'businesses.concerned_person_phone','businesses.id','businesses.company_name',
			'businesses.sector_id','businesses.state_id','businesses.city_id',
			'businesses.added_by','bdf.external_business_id','bdf.id as dueid', DB::raw('da - IF(pa,pa,0) as total'))
			->join('business_due_fees as bdf',function($q){
				$q->on('businesses.id','=','bdf.business_id');
				//$q->groupBy('students.id');
				//$q->groupBy('sdf.added_by');
				//$q->groupBy('sdf.external_student_id');
				});				}
		$records=$records->join(DB::raw('(SELECT sum(business_due_fees.due_amount) AS da,due_date,added_by,
		deleted_at,business_id from business_due_fees WHERE added_by ='.$userId .' 
		AND deleted_at is null GROUP BY business_due_fees.business_id,business_due_fees.added_by,business_due_fees.external_business_id) due'),function($q){
			$q->on('businesses.id','=','due.business_id');
			$q->where('due.deleted_at','=',null);
			//$q->where('due.added_by',$userId);

		});
		if(!empty($request->input('company_name'))){
				
			$records = $records->where('businesses.company_name' , 'LIKE' , General::encrypt($request->input('company_name')));
		}	
		if(!empty($request->input('unique_identification_number'))){
			
			$records = $records->where('businesses.unique_identification_number' , 'LIKE' , General::encrypt($request->input('unique_identification_number')));
		}
		if(!empty($request->input('concerned_person_name'))){
			
			$records = $records->where('businesses.concerned_person_name' , 'LIKE' , General::encrypt($request->input('concerned_person_name')));
		}
		if(!empty($request->input('concerned_person_phone'))){
			
			$records = $records->where('businesses.concerned_person_phone' , 'LIKE' , General::encrypt($request->input('concerned_person_phone')));
		}
		if(!empty($request->input('sector_id'))){
			
			$records = $records->where('businesses.sector_id' , $request->input('sector_id'));
		}
		if(!empty($request->input('state_id'))){
			
			$records = $records->where('businesses.state_id' , $request->input('state_id'));
			if(!empty($request->input('city_id'))){
				$records = $records->where('businesses.city_id' , $request->input('city_id'));
			}
		}
			
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
				(SELECT due_date,added_by,deleted_at,business_id from business_due_fees due1 WHERE $dueDatePeriodRaw AND deleted_at is null ) due2"),function($q) use($userId){
							$q->on('businesses.id','=','due2.business_id');
							$q->where('due2.deleted_at','=',null);
							$q->where('due2.added_by',$userId);
							//$q->where(DB::raw('datediff(CURDATE(),due2.due_date)'),'>',150);
			});	
		}
		$records=$records->leftJoin(DB::raw('(SELECT sum(business_paid_fees.paid_amount) AS pa,added_by,deleted_at,business_id from business_paid_fees WHERE added_by='.$userId .' AND deleted_at is NULL GROUP BY business_paid_fees.business_id,business_paid_fees.added_by,business_paid_fees.external_business_id) paid'),function($q) {
								$q->on('businesses.id','=','paid.business_id');
								$q->where('paid.deleted_at','=',null);
								//$q->where('paid.added_by',$userId);
							});
		if(!empty($request->input('due_date_period'))){
				$records = $records->whereNotNull('due2.due_date');
				$records = $records->groupBy('due2.external_business_id');
		}else{
				$records = $records->where('bdf.added_by','=',$userId);
				$records = $records->where('bdf.deleted_at','=',NULL);
				$records = $records->groupBy('bdf.business_id');
				$records = $records->groupBy('bdf.added_by');
				$records = $records->groupBy('bdf.external_business_id');
		}
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
			$records = $records->orderBy('businesses.id','DESC');
			$records = $records->get();
			$businessUser = User::where('id','=',$userId)->first();
			$businessName = $businessUser->business_name ?? '';
			//dd($records);
		return view('admin.business.for-admin.index',compact('records','sectors','states','cities','userId','businessName'));
    }

   
   
   public function getMemberCustomers(Request $request) 
    { 
		$User = Auth::user();
		
    	$sectors = Sector::whereNull('deleted_at')->where('status',1)->get();
    	$states = State::where('country_id',101)->get(); 
	    $stateIds = []; 
	    foreach ($states as $state){
	       $stateIds[] =$state->id; 
	    } 
	    $cities = City::whereIn('state_id',$stateIds)->get();
    	//$userId = $request->input('userId');
		$userId = Auth::id();
		
		//$authId = Auth::id();
    	$currentDate =Carbon::now();
    	if(!empty($request->input('due_date_period'))){
    		$records = Businesses::select('businesses.unique_identification_number','businesses.custom_business_id','businesses.concerned_person_name','businesses.concerned_person_phone','businesses.id','businesses.company_name','businesses.sector_id','businesses.state_id','businesses.city_id','businesses.added_by','due2.due_date', DB::raw('da - IF(pa,pa,0) as total'));
    	}else{

			$records = Businesses::select('businesses.unique_identification_number',
			'businesses.custom_business_id','businesses.concerned_person_name',
			'businesses.concerned_person_phone','businesses.id','businesses.company_name',
			'businesses.sector_id','businesses.state_id','businesses.city_id',
			'businesses.added_by','bdf.external_business_id','bdf.id as dueid', DB::raw('da - IF(pa,pa,0) as total'))
			->join('business_due_fees as bdf',function($q){
				$q->on('businesses.id','=','bdf.business_id');
				//$q->groupBy('students.id');
				//$q->groupBy('sdf.added_by');
				//$q->groupBy('sdf.external_student_id');
				});				}
		$records=$records->join(DB::raw('(SELECT sum(business_due_fees.due_amount) AS da,due_date,added_by,
		deleted_at,business_id from business_due_fees WHERE added_by ='.$userId .' 
		AND deleted_at is null GROUP BY business_due_fees.business_id,business_due_fees.added_by,business_due_fees.external_business_id) due'),function($q){
			$q->on('businesses.id','=','due.business_id');
			$q->where('due.deleted_at','=',null);
			//$q->where('due.added_by',$userId);

		});
		if(!empty($request->input('company_name'))){
				
			$records = $records->where('businesses.company_name' , 'LIKE' , General::encrypt($request->input('company_name')));
		}	
		if(!empty($request->input('unique_identification_number'))){
			
			$records = $records->where('businesses.unique_identification_number' , 'LIKE' , General::encrypt($request->input('unique_identification_number')));
		}
		if(!empty($request->input('concerned_person_name'))){
			
			$records = $records->where('businesses.concerned_person_name' , 'LIKE' , General::encrypt($request->input('concerned_person_name')));
		}
		if(!empty($request->input('concerned_person_phone'))){
			
			$records = $records->where('businesses.concerned_person_phone' , 'LIKE' , General::encrypt($request->input('concerned_person_phone')));
		}
		if(!empty($request->input('sector_id'))){
			
			$records = $records->where('businesses.sector_id' , $request->input('sector_id'));
		}
		if(!empty($request->input('state_id'))){
			
			$records = $records->where('businesses.state_id' , $request->input('state_id'));
			if(!empty($request->input('city_id'))){
				$records = $records->where('businesses.city_id' , $request->input('city_id'));
			}
		}
			
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
				(SELECT due_date,added_by,deleted_at,business_id from business_due_fees due1 WHERE $dueDatePeriodRaw AND deleted_at is null ) due2"),function($q) use($userId){
							$q->on('businesses.id','=','due2.business_id');
							$q->where('due2.deleted_at','=',null);
							$q->where('due2.added_by',$userId);
							//$q->where(DB::raw('datediff(CURDATE(),due2.due_date)'),'>',150);
			});	
		}
		$records=$records->leftJoin(DB::raw('(SELECT sum(business_paid_fees.paid_amount) AS pa,added_by,deleted_at,business_id from business_paid_fees WHERE added_by='.$userId .' AND deleted_at is NULL GROUP BY business_paid_fees.business_id,business_paid_fees.added_by,business_paid_fees.external_business_id) paid'),function($q) {
								$q->on('businesses.id','=','paid.business_id');
								$q->where('paid.deleted_at','=',null);
								//$q->where('paid.added_by',$userId);
							});
		if(!empty($request->input('due_date_period'))){
				$records = $records->whereNotNull('due2.due_date');
				$records = $records->groupBy('due2.external_business_id');
		}else{
			
				$records = $records->where('bdf.added_by','=',$userId);
				$records = $records->where('bdf.deleted_at','=',NULL);
				$records = $records->groupBy('bdf.business_id');
				$records = $records->groupBy('bdf.added_by');
				$records = $records->groupBy('bdf.external_business_id');
			
		}
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
			$records =$records->orderBy('businesses.id','DESC')->paginate(25);
			// $records = $records->get();
			$businessUser = User::where('id','=',$userId)->first();
			$businessName = $businessUser->business_name ?? '';
			$Report_records=BusinessAdminReports::where('member_id',$userId)->get();
			//dd($records);
		return view('admin.business.my-records.business-customer-level',compact('records','sectors','states','cities','userId','businessName','Report_records'));
    }
   
   
   
   
	
	public function businessData(Request $request,$businessId,$userId,$dueId=null)
	{
        if(isset($duId)){
			$getCustomId = BusinessDueFees::select('external_business_id')->where('business_id','=',$businessId)->where('id','=',$dueId);
		} else {
			$getCustomId = BusinessDueFees::select('external_business_id')->where('business_id','=',$businessId);
		}
		    $getCustomId = $getCustomId->first();
			$checkCustomId = $getCustomId->external_business_id;
		

		$businessDueData = BusinessDueFees::select('business_due_fees.id As dueId','business_due_fees.business_id','due_amount','due_date','business_due_fees.created_at As ReportedAt','paid_amount','paid_date','due_note','business_due_fees.proof_of_due','users.business_name','users.id as userId','user_types.name as userType', 'business_due_fees.added_by','business_due_fees.external_business_id')
										->leftJoin('business_paid_fees','business_due_fees.business_id','=','business_paid_fees.business_id')
										->leftJoin('users','users.id','=','business_due_fees.added_by')
										->leftJoin('user_types','users.user_type','=','user_types.id')
										->where('business_due_fees.business_id','=',$businessId)
										->where('business_due_fees.external_business_id', $checkCustomId)
										->whereNull('business_due_fees.deleted_at')
										->groupBy('business_due_fees.id');
		//if($request->notification==1){	
			$businessDueData = $businessDueData->where('business_due_fees.added_by','=',$userId);
		//}	

		$businessDueData = $businessDueData->orderBy('business_due_fees.created_at','DESC')
						  ->get();
						  
		//$student = Students::where('id','=',$studentId)->first();
		//dd($studentDueData);
		$businessUser = User::where('id','=',$userId)->first();
		$businessName = $businessUser->business_name ?? '';
		$paid_records = BusinessPaidFees::where('business_id','=',$businessId)->where('external_business_id', $checkCustomId)->where('due_id',0)->whereNotNull('payment_options_drop_down')->get();
		$settled_records = 0;
		foreach ($paid_records as $key => $value) {
		    	$settled_records = $value->payment_options_drop_down;
		}
		return view('admin.business.for-admin.business-data',compact('businessDueData','businessId','userId','businessName','settled_records'));

	}
	public function paymentHistory(Request $request)
	{
		//return Response::json(['error' => true,'message'=>'Record not found. Please try again'], 300);
		$dueId = $request->input('due_id');
		$settled_records = $request->input('settled_records');
		$custom_id = $request->input('custom_id');
		$businessId = $request->input('businessId');
		if(isset($dueId)){
		if(empty($dueId)){
			return Response::json(['error' => true,'message'=>'Due id can not be null'], 300);
		 }
	   }
		$paymentHistory = BusinessPaidFees::select('id','paid_date','paid_amount','paid_note','deleted_at','payment_options_drop_down')->whereNull('deleted_at')->orderBy('id','DESC');
		$paymentHistory1 = $paymentHistory->where('due_id', $dueId)->get();
		$exisitng_due_ids =[];
		$paid_history = BusinessDueFees::where('external_business_id', $custom_id)->whereNull('deleted_at')->where('id', $dueId);
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
				$paymentHistory2 = BusinessPaidFees::select('id', 'paid_date', 'paid_amount', 'paid_note', 'deleted_at','payment_options_drop_down')->whereNull('deleted_at')->orderBy('id', 'DESC');
				$paymentHistory2 = $paymentHistory2->where('business_id', $businessId)->where('due_id',0)->where('external_business_id',$custom_id)->whereNotIn('id',$exisitng_due_ids)->get();
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
			$withHtml = View('admin/business/for-admin/payment-history', compact('paymentHistory'))->render();
			return Response::json(['success' => true,'noData'=>false,'paymentHistoryData'=>$withHtml], 200);
		}else{
			return Response::json(['success' => true,'message'=>'','noData'=>true], 200);
		}

		//return Response::json(['success' => true,'message'=>'','paymentHistory'=>$paymentHistory], 200);
	
	}

}

	
