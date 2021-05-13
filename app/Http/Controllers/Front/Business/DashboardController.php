<?php

namespace App\Http\Controllers\Front\Business;

use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\BusinessPaidFees;
use App\BusinessDueFees;
use App\Businesses;
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
use Log;



class DashboardController extends Controller
{

	public function index(Request $request){
		$htmlReport = $this->myReport(); 
		// $unique_identification_number = Session::get('individual_client_udise_gstn');
		$individual_client_mobile_number = Session::get('individual_client_mobile_number');
		$individual_client_email = Session::get('individual_client_email');

		Log::debug("individual_client_mobile_number = ".$individual_client_mobile_number);
		Log::debug("individual_client_email = ".$individual_client_email);

		$TotalDue = 0;
		$numberOfBusinessReported = 0;
		
        
        if ($individual_client_mobile_number) {
			$businesses = Businesses::whereHas('dues')->where('concerned_person_phone','=',General::encrypt($individual_client_mobile_number))->whereNull('deleted_at')->get();
        } else {
        	$businesses = Businesses::whereHas('dues')->where('email','=', General::encrypt($individual_client_email))->whereNull('deleted_at')->get();
        }
		
		if($businesses->count()){
			foreach($businesses as $business){
				//Total dues
				//$proof_of_due = BusinessDueFees::where('business_id',$business->id)->whereNull('deleted_at')->first();
				$totalDueFee = BusinessDueFees::select(DB::raw('sum(due_amount) as Due'))->where('business_id',$business->id)->whereNull('deleted_at')->first();
				$totalPaidFee = BusinessPaidFees::select(DB::raw('sum(paid_amount) as Paid'))->where('business_id',$business->id)->whereNull('deleted_at')->first();

		    	$TotalDue = $TotalDue + ($totalDueFee->Due - $totalPaidFee->Paid);

		    	// Number of bussiness report this user
		    	$temp = BusinessDueFees::where('business_id',$business->id)->whereNull('deleted_at')->groupBy('added_by')->get()->count();
		    	$numberOfBusinessReported = $numberOfBusinessReported + $temp;
		    	
			}
		}

        $TotalDue = number_format($TotalDue);

        /* my records */
        $sRecords = [];
		$message = '';
		
		if(Session::has('individual_client_mobile_number') || Session::has('individual_client_email')){
			//$conactPhone = Session::get('individual_client_mobile_number');
			// $udiseGstn = Session::get('individual_client_udise_gstn');
			if ($individual_client_mobile_number) {
				$individual = Businesses::where('concerned_person_phone','=', General::encrypt($individual_client_mobile_number))->get();	
			} else {
				$individual = Businesses::where('email','=', General::encrypt($individual_client_email))->get();
			}

			if(count($individual) == 0){
				$message = 'No Records';
				return view('front-ib.business.my-records.index',compact('message','sRecords'));
			}
			
			foreach($individual as $b){
				
				$records = Businesses::select('businesses.id','company_name','unique_identification_number','concerned_person_name','sector_id',
											  'concerned_person_designation','concerned_person_phone','concerned_person_alternate_phone',
											  'address','state_id','city_id','pincode','due.added_by as addedBy','due.proof_of_due as proof_of_due', DB::raw('da - IF(pa,pa,0) as total'));

				$records = $records->join(DB::raw('(SELECT sum(business_due_fees.due_amount) AS da,due_date,added_by,deleted_at,business_id,proof_of_due from business_due_fees 
									WHERE business_id = '.$b->id. ' AND deleted_at is null GROUP BY business_due_fees.business_id) due'),function($q){
										$q->on('businesses.id','=','due.business_id');
										$q->where('due.deleted_at','=',null);
										//$q->where('due.added_by',Auth::id());

									});
				$records = $records->leftJoin(DB::raw('(SELECT sum(business_paid_fees.paid_amount) AS pa,added_by,deleted_at,business_id from business_paid_fees 
									WHERE business_id = '.$b->id. ' AND deleted_at is NULL GROUP BY business_paid_fees.business_id) paid'),function($q) {
										$q->on('businesses.id','=','paid.business_id');
										$q->where('paid.deleted_at','=',null);
										//$q->where('paid.added_by',Auth::id());
									});
				//$records = $records->where('contact_phone','=',$individualNumber);
				//$records = $records->orderBy('businesses.id','DESC');
				$records = $records->get(); 
				$sRecords[]=$records;
			}
		}

        return view('front-ib/business/dashboard/index',compact('TotalDue','numberOfBusinessReported','message','sRecords','htmlReport','individual'));
	}

	public function myReport(){
		$records = Collection::make();
		// get business detail from ids

		if(Session::get('individual_client_mobile_number')){
			$individual_client_mobile_number = Session::get('individual_client_mobile_number');

			$records = Businesses::whereHas('dues')
								->where('concerned_person_phone', General::encrypt($individual_client_mobile_number))
								->whereNull('deleted_at')
								->get();
		} else {
			$individual_client_email = Session::get('individual_client_email');
			$records = Businesses::whereHas('dues')
								->where('email', General::encrypt($individual_client_email))
								->whereNull('deleted_at')
								->get();
		}
	
		if($records->count()){	
			foreach ($records as $record) {
				//total due
				// ketli vkht report thyu e account ma aavse...
				$totalMemberReported = BusinessDueFees::select('id')->where('business_id',$record->id)->whereNull('deleted_at')->groupBy('added_by')->get();
				$record->summary_totalMemberReported = $totalMemberReported->count();

				//total due reported
				$totalDueReported = BusinessDueFees::where('business_id',$record->id)->whereNull('deleted_at')->sum('due_amount');
				$record->summary_totalDueReported = $totalDueReported;

				$totalDisputeCount = Dispute::where('customer_id',$record->id)->where('customer_type','=','BUSINESS')->get();
				$record->totalDispute = $totalDisputeCount->count();

				//overDueStatus
				//0-29
				$overDueStatusCount = BusinessDueFees::whereRaw("datediff(CURDATE(),due_date) < 30")->where('business_id',$record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus0To29Days = $overDueStatusCount;

				//30 to 59 days
				$overDueStatusCount = BusinessDueFees::whereRaw(" datediff(CURDATE(),due_date) <=59 AND datediff(CURDATE(),due_date) >=30 ")->where('business_id',$record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus30To59Days = $overDueStatusCount;

				//60 to 89 days
				$overDueStatusCount = BusinessDueFees::whereRaw(" datediff(CURDATE(),due_date) <=89 AND datediff(CURDATE(),due_date) >=60 ")->where('business_id',$record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus60To89Days = $overDueStatusCount;

				//90 to 119 days
				$overDueStatusCount = BusinessDueFees::whereRaw(" datediff(CURDATE(),due_date) <=119 AND datediff(CURDATE(),due_date) >=90 ")->where('business_id',$record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus90To119Days = $overDueStatusCount;

				//120 to 149 days
				$overDueStatusCount = BusinessDueFees::whereRaw(" datediff(CURDATE(),due_date) <=149 AND datediff(CURDATE(),due_date) >=120 ")->where('business_id',$record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus120To149Days = $overDueStatusCount;

				//150 to 179 days
				$overDueStatusCount = BusinessDueFees::whereRaw(" datediff(CURDATE(),due_date) <=179 AND datediff(CURDATE(),due_date) >=150 ")->where('business_id',$record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus150To179Days = $overDueStatusCount;

				//180plus
				$overDueStatusCount = BusinessDueFees::whereRaw("datediff(CURDATE(),due_date) >=180 ")->where('business_id',$record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus180PlusDays = $overDueStatusCount;		


				/* account detail */
				$accountDetails = BusinessDueFees::with(['addedBy','profile'])->whereHas('addedBy')->whereHas('profile')->where('business_id',$record->id)->whereNull('deleted_at')->get();

				$record->accountDetails = $accountDetails;
				$getCustomId = BusinessDueFees::select('external_business_id')->where('business_id','=',$record->id);
	   			 $getCustomId = $getCustomId->first();
				$checkCustomId = $getCustomId->external_business_id;
				 $paid_records = BusinessPaidFees::where('external_business_id', $checkCustomId)->where('due_id',0)->get();
					$settled_records = 0;
					foreach ($paid_records as $key => $value) {
					    if(isset($value->payment_options_external_id)){
					    	$settled_records = $value->payment_options_external_id;
					        $settled_records = explode(',', $settled_records);

					   	
					    }
					    if($settled_records==0){
                          $settled_records = null;
					    }
					    

					}
			}
		}	

		
		$dateTime = Carbon::now()->format('d-m-Y H:i');
		$htmlReport = view('front-ib.business.my-report.index',compact('records','dateTime','settled_records'));		
		return $htmlReport;
	}

		
}

		

