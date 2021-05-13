<?php

namespace App\Http\Controllers\Front\Individual;

use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\StudentPaidFees;
use App\StudentDueFees;
use App\Individuals;
use App\Students;
use Carbon\Carbon;
use General;
use Validator;
use Response;
use Auth;
use DB;
use App\DuePayment;
use PDF;
use App\DisputeReason;
use App\Dispute;
use Storage;
use App\ConsentRequest;
use Log;

class DashboardController extends Controller
{

	public function index(Request $request){

		$htmlReport = $this->myReport();
		$conactPhone = Session::get('individual_client_mobile_number');
		$TotalDue = 0;
		$numberOfBusinessReported = 0;
		//dd($conactPhone);
		$individuals = Students::whereHas('dues')->where('contact_phone','=',General::encrypt($conactPhone))->whereNull('deleted_at')->get();
		
		if($individuals->count()){
			foreach($individuals as $individual){
				//Total dues
				$totalDueFee = StudentDueFees::select(DB::raw('sum(due_amount) as Due'))->where('student_id',$individual->id)->whereNull('deleted_at')->first();
				$totalPaidFee = StudentPaidFees::select(DB::raw('sum(paid_amount) as Paid'))->where('student_id',$individual->id)->whereNull('deleted_at')->first();

		    	$TotalDue = $TotalDue + ($totalDueFee->Due - $totalPaidFee->Paid);

		    	// Number of bussiness report this user
		    	$temp = StudentDueFees::where('student_id',$individual->id)->whereNull('deleted_at')->groupBy('added_by')->get()->count();
		    	$numberOfBusinessReported = $numberOfBusinessReported + $temp;
		    	
			}
		}

        $TotalDue = number_format($TotalDue); 


        /* records */
        $sRecords = [];
		$message = '';
		
		if(Session::has('individual_client_mobile_number')){
			$conactPhone = Session::get('individual_client_mobile_number');
			 
			$individual = Students::where('contact_phone','=',General::encrypt($conactPhone))->whereNull('deleted_at')->get();

			// if($individual->count()==0){
			// 	$message = 'No Records';
			// 	return view('front-ib.individual.my-records.index',compact('message','sRecords'));
			// }
			
			foreach($individual as $b){
				
				$records = Students::select('students.id','students.person_name','dob','father_name','mother_name','aadhar_number','contact_phone','due.added_by as addedBy','due.proof_of_due as proof_of_due', DB::raw('da - IF(pa,pa,0) as total'));

				$records=$records->join(DB::raw('(SELECT sum(student_due_fees.due_amount) AS da,due_date,added_by,deleted_at,student_id,proof_of_due from student_due_fees 
									WHERE student_id = '.$b->id. ' AND deleted_at is null GROUP BY student_due_fees.student_id) due'),function($q){
										$q->on('students.id','=','due.student_id');
										$q->where('due.deleted_at','=',null);
										//$q->where('due.added_by',Auth::id());

									});
				$records=$records->leftJoin(DB::raw('(SELECT sum(student_paid_fees.paid_amount) AS pa,added_by,deleted_at,student_id from student_paid_fees 
									WHERE student_id = '.$b->id. ' AND deleted_at is NULL GROUP BY student_paid_fees.student_id) paid'),function($q) {
										$q->on('students.id','=','paid.student_id');
										$q->where('paid.deleted_at','=',null);
										//$q->where('paid.added_by',Auth::id());
									});
				//$records = $records->where('contact_phone','=',$individualNumber);
				//$records = $records->orderBy('students.id','DESC');
				$records = $records->get(); 
				$sRecords[]=$records;
			}
			//dd($sRecords);
		}
        
		return view('front-ib/individual/dashboard/index',compact('TotalDue','numberOfBusinessReported','message','sRecords','htmlReport','individual'));
	}

	public function myReport(){
		$dataList = Collection::make();
		$reportForYear = setting('admin.generate_report_from_consent_for_last_year') ? (int)setting('admin.generate_report_from_consent_for_last_year') : 0 ;

		/*if($reportForYear>0){
			$previousYears = Carbon::now()->subYear($reportForYear);
			
			->where('created_at','>=',$previousYears)
		}*/	
		$individualIds = [];
		$student = Students::with('addedBy')->whereHas('addedBy', function ($q) {
					$q->where('is_deleted',0);
				})->with('dues')->whereHas('dues', function ($q) {
					$q->whereNull('deleted_at');
				})->where('contact_phone', General::encrypt(Session::get('individual_client_mobile_number')));
				if (!empty($data->person_name)) {
					//$student = $student->where('person_name', General::encrypt($data->person_name));
				}
		$student = $student->whereNull('deleted_at')->get();
				// dd($student);


				if ($student->count()) {
					foreach ($student as $s) {
						if (!in_array($s->id, $individualIds)) {
							$individualIds[] = $s->id;
						}
					}
				}			

		$records = Collection::make();
         // dd($individualIds);
		$records = Students::whereHas('dues')->whereIn('id', $individualIds)->get();
		// $records = $records->whereNull('deleted_at')->get();
		// dd($records);


		if($records->count()){
			foreach ($records as $record) {
				$getCustomId = StudentDueFees::select('external_student_id')->where('student_id','=',$record->id);
			     $getCustomId = $getCustomId->first();
				 $checkCustomId = $getCustomId->external_student_id;
				//total due
				// ketli vkht report thyu e account ma aavse...
				$totalMemberReported = StudentDueFees::select('id')->where('student_id',$record->id)->where('external_student_id', $checkCustomId)->whereNull('deleted_at')->groupBy('added_by')->get();
				$record->summary_totalMemberReported = $totalMemberReported->count();

				//total due reported
				$totalDueReported = StudentDueFees::where('student_id',$record->id)->where('external_student_id', $checkCustomId)->whereNull('deleted_at')->sum('due_amount');
				$record->summary_totalDueReported = $totalDueReported;

				 $paidAmount=StudentPaidFees::where('student_id', '=', $record->id)
		   								->whereNull('deleted_at')
		   								->where('external_student_id', $checkCustomId)
										->select('paid_amount')
           			 					->groupBy('student_id')
										->sum('paid_amount');
				$record->summary_totalDuePaid = $paidAmount;						

				$totalDisputeCount = Dispute::where('customer_id',$record->id)->where('customer_type','=','INDIVIDUAL')->get();
				$record->totalDispute = $totalDisputeCount->count();

				//overDueStatus
				//0-29
				$overDueStatusCount = StudentDueFees::whereRaw("datediff(CURDATE(),due_date) < 30")->where('student_id',$record->id)->where('external_student_id', $checkCustomId)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus0To29Days = $overDueStatusCount;

				//30 to 59 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=59 AND datediff(CURDATE(),due_date) >=30 ")->where('student_id',$record->id)->where('external_student_id', $checkCustomId)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus30To59Days = $overDueStatusCount;

				//60 to 89 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=89 AND datediff(CURDATE(),due_date) >=60 ")->where('student_id',$record->id)->where('external_student_id', $checkCustomId)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus60To89Days = $overDueStatusCount;

				//90 to 119 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=119 AND datediff(CURDATE(),due_date) >=90 ")->where('student_id',$record->id)->where('external_student_id', $checkCustomId)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus90To119Days = $overDueStatusCount;

				//120 to 149 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=149 AND datediff(CURDATE(),due_date) >=120 ")->where('student_id',$record->id)->where('external_student_id', $checkCustomId)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus120To149Days = $overDueStatusCount;

				//150 to 179 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=179 AND datediff(CURDATE(),due_date) >=150 ")->where('student_id',$record->id)->where('external_student_id', $checkCustomId)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus150To179Days = $overDueStatusCount;

				//0-89
				$overDueStatusCount = StudentDueFees::whereRaw("datediff(CURDATE(),due_date) < 90")->where('student_id', $record->id)->where('external_student_id', $checkCustomId)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus0To89Days = $overDueStatusCount;


				//90 to 179 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=179 AND datediff(CURDATE(),due_date) >=90 ")->where('external_student_id', $checkCustomId)->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus90To179Days = $overDueStatusCount;

				//180plus
				$overDueStatusCount = StudentDueFees::whereRaw("datediff(CURDATE(),due_date) >=180 ")->where('student_id',$record->id)->where('external_student_id', $checkCustomId)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus180PlusDays = $overDueStatusCount;		


				/* account detail */
				$accountDetails = StudentDueFees::with(['addedBy','profile','dispute'])->whereHas('addedBy')->whereHas('profile')->where('student_id',$record->id)->where('external_student_id', $checkCustomId)->whereNull('deleted_at')->get();

				$record->accountDetails = $accountDetails;
				$getCustomId = StudentDueFees::select('external_student_id')->where('student_id','=',$record->id);
				$getCustomId = $getCustomId->first();
				$checkCustomId = $getCustomId->external_student_id;
				$paid_records = StudentPaidFees::where('external_student_id', $checkCustomId)->where('due_id',0)->get();
					$settled_records = 0;
					foreach ($paid_records as $key => $value) {
					    	$settled_records = $value->student_id;
					        $settled_records = explode(',', $settled_records);

					   	
					     if($settled_records==0){
                          $settled_records = null;
					    }
					    

					}
			}
		}	

		// dd($records);

		$dateTime = Carbon::now()->format('d-m-Y H:i');
		$htmlReport = view('front-ib.individual.my-report.index',compact('records','dateTime','settled_records'));	
		return $htmlReport;	
	
	}	


	  public function getIndividualReport(Request $request)
	{
		$data_id= $request->input('data_id');
		// Log::debug($data_id);
		$student = Students::where('id',$data_id);
				
		$student = $student->whereNull('deleted_at')->first();
		$mobile_number = $student->contact_phone;
		$person_name = $student->person_name;
		$dob = $student->dob;
		$aadhar_number = $student->aadhar_number;
		$records = Students::wherehas('dues')->where('id', $student->id)->first();

		$due = StudentDueFees::where('student_id',$student->id)->whereNull('deleted_at')->get();
		 // $paid_cnt = StudentPaidFees::where('student_id',$student->id);


		 $paid_cnt = StudentPaidFees::select('student_paid_fees.id As id','student_paid_fees.due_id As due_id','student_paid_fees.student_id','student_paid_fees.created_at As ReportedAt','paid_amount','paid_date','due_note','student_due_fees.due_amount','student_due_fees.due_date As due_date','student_due_fees.created_at As created_at','student_due_fees.proof_of_due As proof_of_due')
										->leftJoin('student_due_fees','student_paid_fees.due_id','=','student_due_fees.id')

										->where('student_paid_fees.student_id','=',$student->id)
			->whereNull('student_paid_fees.deleted_at')->latest()
			->get();

		 // $paid_cnt= $paid_cnt->withCount([
   //                  'dues AS totalDue' => function ($query)  {
   //                  $query->select(DB::raw("due_amount as dues"));
   //                  }
   //                  ])->get();


		// Log::debug(count($due));
		$dueamount=[];
		// $dueId=[];
       foreach ($due as $key => $value) {
               $dueamount[$key]['due_amount']=$value->due_amount;
               $dueamount[$key]['proof_of_due']=$value->proof_of_due;
              $dueamount[$key]['due_id'] =$value->id; 
              $dueamount[$key]['due_date'] =$value->due_date;
              $dueamount[$key]['date_reported'] =$value->created_at; 

              $paid = StudentPaidFees::where('due_id',$value->id)
                                        ->select('paid_amount')
           			 					->groupBy('due_id')
										->sum('paid_amount');

			  $latest_paid = StudentPaidFees::where('due_id',$value->id)
                                        ->latest()->first();	
                                        // Log::debug($latest_paid);
                 if(isset($latest_paid)){                      					
                 $dueamount[$key]['last_paid_amount'] = $latest_paid->paid_amount;
                   // $dueamount[$key]['last_paid_date'] = $latest_paid->created_at;
                   $dueamount[$key]['last_paid_date']= Carbon::createFromFormat('Y-m-d H:i:s', $latest_paid->created_at)->format('d-m-Y');

                   $now  = Carbon::today();
                   $dueamount[$key]['overDueStatus'] = $now->diffInDays($value->due_date);
                   if($now->diffInDays($value->due_date)>1){
                   	$day = "days"; 
                   } else {
                   	$day= "day";
                   } 
                    $dueamount[$key]['overDueStatus'] = $now->diffInDays($value->due_date) ." ". $day;
                 } else {
                 	$dueamount[$key]['last_paid_amount'] = '-';
                   $dueamount[$key]['last_paid_date'] = '-';
                   $now  = Carbon::today();
                   $dueamount[$key]['overDueStatus'] = '-';
                 }
		// $paid_count = StudentPaidFees::where('due_id',$value->id)
  //                                       // ->select('paid_amount')
  //          			 					// ->groupBy('due_id')
		// 								->count('paid_amount');								
			  $dueamount[$key]['paid_amount'] = $paid;
			   // $dueamount[$key]['paid_count'] = $paid_count;
			  $dueamount [$key]['unpaid'] = $value->due_amount-$paid;
			   // Log::debug($paid);

       		}		

		Log::debug($dueamount);

		$getCustomId = StudentDueFees::select('external_student_id')->where('student_id','=',$student->id);
			     $getCustomId = $getCustomId->first();
				 $checkCustomId = $getCustomId->external_student_id;
		$paid_records = StudentPaidFees::where('student_id','=',$student->id)->where('due_id',0)->first();
					
		 $settled_records = 0;

		     if(empty($paid_records)){
              $settled_records = 0;
		    } else {
		    	$settled_records = $paid_records->student_id;
		    }

				//total due
				// ketli vkht report thyu e account ma aavse...
				$totalMemberReported = StudentDueFees::select('id')->where('student_id',$student->id)->where('external_student_id', $checkCustomId)->whereNull('deleted_at')->groupBy('added_by')->get();
				
				// $record->summary_totalMemberReported = $totalMemberReported->count();
				$summary_totalMemberReported = $totalMemberReported->count();
				// Log::debug($summary_totalMemberReported);


				//total due reported
				$totalDueReported = StudentDueFees::where('student_id',$student->id)->where('external_student_id', $checkCustomId)->whereNull('deleted_at')->sum('due_amount');
				$summary_totalDueReported = $totalDueReported;
				// Log::debug("aasasasasasas".$summary_totalDueReported);

				 $paidAmount=StudentPaidFees::where('student_id', '=', $student->id)
		   								->whereNull('deleted_at')
		   								->where('external_student_id', $checkCustomId)
										->select('paid_amount')
           			 					->groupBy('student_id')
										->sum('paid_amount');
				$summary_totalDuePaid = $paidAmount;		
				// Log::debug($summary_totalDuePaid);				

				$totalDisputeCount = Dispute::where('customer_id',$student->id)->where('customer_type','=','INDIVIDUAL')->get();
				$totalDispute = $totalDisputeCount->count();
				// Log::debug($totalDispute);

				//0-89
				$overDueStatusCount = StudentDueFees::whereRaw("datediff(CURDATE(),due_date) < 90")->where('student_id', $student->id)->where('external_student_id', $checkCustomId)->whereNull('deleted_at')->count();
				$summary_overDueStatus0To89Days = $overDueStatusCount;
				// Log::debug($summary_overDueStatus0To89Days);


				//90 to 179 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=179 AND datediff(CURDATE(),due_date) >=90 ")->where('external_student_id', $checkCustomId)->where('student_id', $student->id)->whereNull('deleted_at')->count();
				$summary_overDueStatus90To179Days = $overDueStatusCount;
// Log::debug($summary_overDueStatus90To179Days);
				//180plus
				$overDueStatusCount = StudentDueFees::whereRaw("datediff(CURDATE(),due_date) >=180 ")->where('student_id',$student->id)->where('external_student_id', $checkCustomId)->whereNull('deleted_at')->count();
				$summary_overDueStatus180PlusDays = $overDueStatusCount;	
				// Log::debug($summary_overDueStatus180PlusDays);	


				/* account detail */
				$accountDetails = StudentDueFees::with(['addedBy','profile','dispute'])->whereHas('addedBy')->whereHas('profile')->where('student_id',$student->id)->where('external_student_id', $checkCustomId)->whereNull('deleted_at')->get();

				$accountDetails = $accountDetails;

				// Log::debug($paid_cnt);
				$result = array();
				$result['student'] = $student;
				$result['dueamount'] = $dueamount;
				$result['paid_cnt'] = count($paid_cnt);
				$result['paid_data'] = $paid_cnt;
				$result['due'] = count($due);
				$result['records'] = $records->dues;
				$result['paidAmount'] = $paidAmount;
				$result['summary_totalMemberReported'] = $summary_totalMemberReported;
				$result['summary_totalDueReported'] = $summary_totalDueReported;
				$result['summary_totalDuePaid'] = $summary_totalDuePaid;
				$result['totalDispute'] = $totalDispute;
				$result['summary_overDueStatus0To89Days'] = $summary_overDueStatus0To89Days;
				$result['summary_overDueStatus90To179Days'] = $summary_overDueStatus90To179Days;
				$result['summary_overDueStatus180PlusDays'] = $summary_overDueStatus180PlusDays;
				$result['accountDetails'] = $accountDetails;
				$result['settled_records'] = $settled_records;
				return $result;
				 // return response()->json(['success' => true,'student' => $student, 'summary_totalMemberReported' => $summary_totalMemberReported,'summary_totalDueReported' => $summary_totalDueReported, 'summary_totalDuePaid' => $summary_totalDuePaid,'totalDispute' => $totalDispute,'v' => $summary_overDueStatus0To89Days,'summary_overDueStatus90To179Days' => $summary_overDueStatus90To179Days,'summary_overDueStatus180PlusDays' => $summary_overDueStatus180PlusDays,'accountDetails' => $accountDetails,'mobile_number' => $mobile_number]);

	}
}
