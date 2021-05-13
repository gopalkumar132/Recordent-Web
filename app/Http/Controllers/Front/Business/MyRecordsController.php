<?php

namespace App\Http\Controllers\Front\Business;

use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\BusinessPaidFees;
use App\BusinessDueFees;
use App\Individuals;
use App\Businesses;
use Carbon\Carbon;
use General;
use Validator;
use Response;
use Auth;
use DB;
use PaytmWallet;
use Str;
use App\DuePayment;
use PDF;
use App\DisputeReason;
use App\Dispute;
use Storage;
use App\User;
use Log;


class MyRecordsController extends Controller
{

	public function index(Request $request){


		$htmlReport = $this->myReport();
		$sRecords = [];
		$message = '';
		
		if(Session::has('individual_client_mobile_number') || Session::has('individual_client_email')){
			//$conactPhone = Session::get('individual_client_mobile_number');
			$udiseGstn = Session::get('individual_client_udise_gstn');
			if(Session::has('individual_client_mobile_number')){
			       $individual = Businesses::whereHas('dues')->where('concerned_person_phone','=',General::encrypt(Session::has('individual_client_mobile_number')))->get();
			 } else {      
     		     	$individual = Businesses::whereHas('dues')->where('email','=',General::encrypt(Session::has('individual_client_email')))->get();
		     }
			
			if(count($individual)==0){
				$message = 'No Records';
				return view('front-ib.business.my-records.index',compact('message','sRecords'));
			}
			
			foreach($individual as $b){
				
				$records = Businesses::select('businesses.id','company_name','unique_identification_number','concerned_person_name','sector_id',
											  'concerned_person_designation','concerned_person_phone','concerned_person_alternate_phone',
											  'address','state_id','city_id','pincode','due.added_by as addedBy', DB::raw('da - IF(pa,pa,0) as total'));

				$records=$records->join(DB::raw('(SELECT sum(business_due_fees.due_amount) AS da,due_date,added_by,deleted_at,business_id from business_due_fees 
									WHERE business_id = '.$b->id. ' AND deleted_at is null GROUP BY business_due_fees.business_id) due'),function($q){
										$q->on('businesses.id','=','due.business_id');
										$q->where('due.deleted_at','=',null);
										//$q->where('due.added_by',Auth::id());

									});
				$records=$records->leftJoin(DB::raw('(SELECT sum(business_paid_fees.paid_amount) AS pa,added_by,deleted_at,business_id from business_paid_fees 
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
			//dd($sRecords);
		}		 
		
		return view('front-ib.business.my-records.index',compact('message','sRecords','htmlReport'));
	}
	
	public function businessData($businessId)
	{ 
		$businessDueData = BusinessDueFees::select('business_due_fees.id As dueId','business_due_fees.business_id','due_amount','due_date','business_due_fees.created_at As ReportedAt','paid_amount','paid_date','due_note','customer_no','invoice_no','users.business_name','users.id as userId','user_types.name as userType')
										->leftJoin('business_paid_fees','business_due_fees.business_id','=','business_paid_fees.business_id')
										->leftJoin('users','users.id','=','business_due_fees.added_by')
										->leftJoin('user_types','users.user_type','=','user_types.id')
										->where('business_due_fees.business_id','=',$businessId)
			->whereNull('business_due_fees.deleted_at')
			->groupBy('business_due_fees.id')
		    ->orderBy('business_due_fees.created_at','DESC')
			->get();
			$business = Businesses::where('id','=',$businessId)->first();
			//dd($businessDueData);
		return view('front-ib.business.my-records.data',compact('businessDueData','business','businessId'));
	}

	public function paymentHistory(Request $request)
	{
		//return Response::json(['error' => true,'message'=>'Record not found. Please try again'], 300);
		$dueId = $request->input('due_id');
		if(empty($dueId)){
			return Response::json(['error' => true,'message'=>'Due id can not be null'], 300);
		}
		$paymentHistory = BusinessPaidFees::select('id','paid_date','paid_amount','paid_note','deleted_at')->where('due_id',$dueId)->orderBy('id','DESC')->get();
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
			$withHtml = View('front-ib.business.my-records.payment-history', compact('paymentHistory'))->render();
			return Response::json(['success' => true,'noData'=>false,'paymentHistoryData'=>$withHtml], 200);
		}else{
			return Response::json(['success' => true,'message'=>'','noData'=>true], 200);
		}

		//return Response::json(['success' => true,'message'=>'','paymentHistory'=>$paymentHistory], 200);
	
	}

	public function myReport(){
		$records = Collection::make();
		// get business detail from ids
		if(Session::has('individual_client_mobile_number')){
		      $records = Businesses::whereHas('dues')->where('concerned_person_phone',General::encrypt(Session::get('individual_client_mobile_number')))->whereNull('deleted_at')->get();
   	    } else {
   	    	  $records = Businesses::whereHas('dues')->where('email',General::encrypt(Session::get('individual_client_email')))->whereNull('deleted_at')->get();
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
			}
		}	

		
		$dateTime = Carbon::now()->format('d-m-Y H:i');
		$htmlReport = view('front-ib.business.my-report.index',compact('records','dateTime'));		
		return $htmlReport;
	}

	public function myReportDownload(Request $request){

		if(empty($request->c_id) || empty($request->r_n)) {
			return redirect()->back()->withErrors(['Something went wrong.']);
		}
		$records = Collection::make();
		// get business detail from ids
		if(Session::has('individual_client_mobile_number')){
		    $records = Businesses::whereHas('dues')->where('concerned_person_phone',General::encrypt(Session::get('individual_client_mobile_number')))->where('id',$request->c_id)->whereNull('deleted_at')->get();
		} else {
			$records = Businesses::whereHas('dues')->where('email',General::encrypt(Session::get('individual_client_email')))->where('id',$request->c_id)->whereNull('deleted_at')->get();
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
			}
		}	

		
		$dateTime = Carbon::now()->format('d-m-Y H:i');

		//return view('admin.students.report.table',compact('records','dateTime'));
		$pdf = PDF::loadView('front-ib.business.my-report.table', ['records'=>$records,'dateTime'=>$dateTime,'reportNumber'=>$request->r_n]);
        //$pdf = PDF::loadView('admin.students.report.download', ['records'=>$records,'dateTime'=>$dateTime]);
        $fileName = $request->r_n.'.pdf';
        return $pdf->download('Recordent-'.$fileName);	
	}

	public function payment(Request $request){
		//dd($request->all());
		 $validator = Validator::make($request->all(), [
		   'due_id' => 'required',
           'pay_amount' => 'required|numeric|min:1',
           'agree_terms' => 'required',
       ]);
        if($validator->fails()) {
           return redirect()->back()
                       ->withErrors($validator)
                       ->withInput();
       }
       $dueId = $request->due_id;
       $payAmount = $request->pay_amount;
       //check due id 
       if(Session::has('individual_client_mobile_number')){
            $records = Businesses::whereHas('dues')->where('concerned_person_phone',General::encrypt(Session::get('individual_client_mobile_number')));
       } else {
       	    $records = Businesses::whereHas('dues')->where('email',General::encrypt(Session::get('individual_client_email')));
       }
       // $records = Businesses::whereHas('dues')->where('unique_identification_number',General::encrypt(Session::get('individual_client_udise_gstn')));
	   $records = $records->whereNull('deleted_at')->get();
	   if(!$records->count()){
	   		return redirect()->back()->withErrors(['No record found']);
	   }

	   $recordIdsArray = $records->pluck('id')->toArray();
	   $duesRecord = BusinessDueFees::where('id',$dueId)->whereIn('business_id',$recordIdsArray)->whereNull('deleted_at')->first();	 
	   if(empty($duesRecord)){
	   		return redirect()->back()->withErrors(['No record found']);
	   }

	   if(!empty($duesRecord)){
	   		$remainingAmount = $duesRecord->due_amount;

	   		if($request->pay_amount > $remainingAmount){
	   			return redirect()->back()->withErrors(['You are paying more than due amount']);
	   		}
	   
	   }
       


	   //check how many paids for this dues

	   $paidRecords = BusinessPaidFees::select(DB::raw('sum(paid_amount) As paidAmount'))->where('due_id',$duesRecord->id)->groupBy('due_id')->whereNull('deleted_at')->first();

	   if(!empty($paidRecords)){
	   		$remainingAmount = $duesRecord->due_amount - $paidRecords->paidAmount;
	   		if($request->pay_amount > $remainingAmount){
	   			return redirect()->back()->withErrors(['You are paying more than due amount']);
	   		}
	   }

		$order_id = Str::random(40);
	    $custom_payment_redirect_url = config('custom_payments_config.MEMBER_CUSTOM_PG.'.$duesRecord->added_by.'.pg_url');

	    if ($custom_payment_redirect_url) {
	   		$order_id = config('custom_payments_config.MEMBER_CUSTOM_PG.'.$duesRecord->added_by.'.pg_prefix').Carbon::now()->format('YmdHis');
	   	}

	    $duePayment = DuePayment::create([
			'order_id'=> $order_id,
			'customer_type'=>'BUSINESS',
			'customer_id'=>$duesRecord->business_id,
			'due_id'=>$duesRecord->id,
			'payment_value'=>$request->pay_amount,
			'status'=>1,//initiated
			'created_at'=>Carbon::now(),
			'added_by'=>Session::get('individual_client_id'),
		]);

	   	if ($custom_payment_redirect_url) {
	   		
	   		return redirect()->away($custom_payment_redirect_url);
	    }  else {

	    	if (Session::has('individual_client_mobile_number')) {
				$mobile_number = Session::get('individual_client_mobile_number');
			} else {
				$mobile_number = $records[0]->concerned_person_phone ?? '';
			}

			//$userDataToPaytm = User::findOrFail(Auth::user()->id);
			$duePayment->pg_type = setting('admin.payment_gateway_type');
			$duePayment->update();

			if(setting('admin.payment_gateway_type') == 'paytm'){
				
				$payment = PaytmWallet::with('receive');
				$payment->prepare([
					'order'=> $duePayment->order_id,
					'user'=> 'user',
					'mobile_number'=> $mobile_number,
					'email'=> '@',
					'amount'=> $request->pay_amount,
					'callback_url'=> route('front-business.my-records-payment-callback')
				]);

				General::add_to_payment_debug_log($duesRecord->business_id, 1);
				
				return $payment->view('admin.payment-submit')->receive();
			} else {

				$postData = [
					'amount'=>$request->pay_amount,
					'txnid'=>$duePayment->order_id,
					'phone' => $mobile_number,
					'surl'=>route('front-business.my-records-payment-callback'),
				];

				$payuForm = General::generatePayuForm($postData);
				
				return view('admin.payment-submit',compact('payuForm'));
			}
		}

	}

	public function paymentCallback(Request $request){
		if(setting('admin.payment_gateway_type')=='paytm'){
				$transaction = PaytmWallet::with('receive');
					try{
						$response = $transaction->response();
					}catch(\Exception $e){
						//add to db log
						return redirect()->route('front-business.dashboard')->withErrors(['Something went wrong']);
					}
				}else{
					try{
						$response = General::verifyPayuPayment($request->all());
						if(!$response){
							return redirect()->route('front-business.dashboard')->withErrors(['Something went wrong']);
						}
					}catch(\Exception $e){
						return redirect()->route('front-business.dashboard')->withErrors(['Something went wrong']);
					}	
				}	

		//dd($response);
		$duePayment = DuePayment::where('order_id','=',$response['ORDERID'])
			->where('status',1)
			->where('added_by',Session::get('individual_client_id'))
			->first();
		if(empty($duePayment)){
			Log::debug("Invalid payment.");
			return redirect()->route('front-business.dashboard')->withErrors(['Invalid payment.']);
		}

		$message ='';
		$alertType='info';
		
		if(setting('admin.payment_gateway_type')=='paytm'){
      		if($transaction->isSuccessful()){
      			$paymentStatus = 'success';
      		} else if ($transaction->isFailed()) {
      			$paymentStatus = 'failed';
      		}else{
      			$paymentStatus = 'open';
      		}
      	}else{
      		$paymentStatus = $response['paymentStatus']=='success' ? 'success': ($response['paymentStatus']=='failure' ? 'failed' : 'open');
      	}

		$duePayment->transaction_id = $response['TXNID'] ?? $response['mihpayid'] ?? '';
		$duePayment->payment_mode = $response['PAYMENTMODE'] ?? $response['mode'] ?? '';
		
		if($paymentStatus=='success'){
          	$duePayment->status = 4;
          	$alertType = 'success';
          	$message='Payment successful.';
			//make entry in paid_dues
			General::add_to_payment_debug_log($duePayment->customer_id, 4);
			
			$dueRecord = BusinessDueFees::where('id',$duePayment->due_id)->first();
			if(empty($dueRecord)){
				return redirect()->route('front-business.dashboard')->withErrors(['Invalid payment.']);
			}
			$duePaid = BusinessPaidFees::create([
				'business_id'=>$duePayment->customer_id,
				'due_id'=>$duePayment->due_id,
				'paid_date'=>Carbon::now(),
				'paid_amount'=>$duePayment->payment_value,
				'created_at'=>Carbon::now(),
				'added_by'=>$dueRecord->added_by,
				'payment_done_by'=>'CUSTOMER',
				'payment_done_by_id'=>Session::get('individual_client_id')
			]);
			$duePayment->paid_id = $duePaid->id;
			General::storeAdminNotificationForPaymentFromCustomer('Business',$duePaid->id);

			if($dueRecord->balance_due !=0)
			{
				General::Update_Balance_Due($duesRecord->balance_due,$duePayment->payment_value,"Business",$duePayment->due_id,$duePayment->customer_id);
			}

        }else if($paymentStatus=='failed'){
         	$duePayment->status = 5;
         	$alertType = 'error';
          	$message='Payment failed.';
          	General::add_to_payment_debug_log($duePayment->customer_id, 5);
        }else {
          	$duePayment->status = 2;
          	$alertType = 'info';
          	$message='Payment is in progress.';
          	General::add_to_payment_debug_log($duePayment->customer_id, 2);
        }

        $duePayment->raw_response = json_encode($response);
      	$duePayment->updated_at = Carbon::now();
        $duePayment->update();
        if($alertType=='error'){
        	return redirect()->route('front-business.dashboard')->withErrors([$message]);
        }
        return redirect()->route('front-business.dashboard')->with('message',$message);
	}



	public function raiseDispute($dueId){
       
       if(Session::has('individual_client_mobile_number')){ 
	       $records = Businesses::whereHas('dues')->where('concerned_person_phone',General::encrypt(Session::get('individual_client_mobile_number')));
	    } else {
	    	$records = Businesses::whereHas('dues')->where('email',General::encrypt(Session::get('individual_client_email')));
	    } 
	   $records = $records->whereNull('deleted_at')->get();
	   if(!$records->count()){
	   		return redirect()->back()->withErrors(['No record found']);
	   }

	   $recordIdsArray = $records->pluck('id')->toArray();
	   $duesRecord = BusinessDueFees::where('id',$dueId)->whereIn('business_id',$recordIdsArray)->whereNull('deleted_at')->first();	 
	   if(empty($duesRecord)){
	   		return redirect()->back()->withErrors(['No record found']);
	   }
	   $lastDispute = Dispute::where('due_id',$dueId)->where('customer_type','BUSINESS')->orderBy('id','DESC')->first();
	   if($lastDispute && $lastDispute->is_open==1){
	   		return redirect()->back()->withErrors(['Dispute already open. can not raise another.']);
	   }
	   $disputeReasons = DisputeReason::orderBy('reason','ASC')->get();

	   return view('front-ib.business.my-report.raise-dispute',compact('duesRecord','disputeReasons','lastDispute'));

	}


	public function raiseDisputeStore($dueId,Request $request){
		$disputeReasonIdArray = DisputeReason::get()->pluck('id')->toArray();
	    $disputeReasonIdArray = implode(',',$disputeReasonIdArray);	
	    $disputeCommentMaxLength = setting('admin.customer_dispute_comment_max_length') ? (int)setting('admin.customer_dispute_comment_max_length') : 100;
	    $validator = Validator::make($request->all(), [
		   'due_id' => 'required',
           'dispute_reason' => 'required|integer|in:'.$disputeReasonIdArray,	
           'dispute_comment'=>'nullable|string|max:'.$disputeCommentMaxLength,
           'proof_of_payment'=>'mimes:jpeg,bmp,png,gif,svg,pdf',
       ]);
        if($validator->fails()) {
           return redirect()->back()
                       ->withErrors($validator)
                       ->withInput();
       }

       $dueId = $request->due_id;
       $disputeComment = $request->dispute_comment;
       $disputeReasonId = $request->dispute_reason;
       if(Session::has('individual_client_mobile_number')){
	       $records = Businesses::whereHas('dues')->where('concerned_person_phone',General::encrypt(Session::get('individual_client_mobile_number')));
	    } else {
	         $records = Businesses::whereHas('dues')->where('email',General::encrypt(Session::get('individual_client_email')));	
	    }   
	    
	   $records = $records->whereNull('deleted_at')->get();
	   if(!$records->count()){
	   		return redirect()->back()->withErrors(['No record found']);
	   }

	   $recordIdsArray = $records->pluck('id')->toArray();
	   $duesRecord = BusinessDueFees::where('id',$dueId)->whereIn('business_id',$recordIdsArray)->whereNull('deleted_at')->first();	 
	   if(empty($duesRecord)){
	   		return redirect()->back()->withErrors(['No record found']);
	   }
	   //check last dispute
	   $lastDispute = Dispute::where('due_id',$dueId)->where('is_open',1)->where('customer_type','BUSINESS')->orderBy('id','DESC')->first();
	   if($lastDispute){
	   		return redirect()->back()->withErrors(['Dispute already open. can not raise another.']);
	   }
	   $proofOfPayment ='';
	   if(!empty($request->file('proof_of_payment'))){
			$proofOfPayment = Storage::disk('public')->put('business/dispute/proof_of_payment', $request->file('proof_of_payment'));
	   }
	   Dispute::create([
	   		'due_id'=>$dueId,
	   		'due_added_by'=>$duesRecord->added_by,
	   		'customer_type'=>'BUSINESS',
	   		'customer_id'=>$duesRecord->business_id, //individual id, the due is created for.
	   		'comment'=>$disputeComment,
	   		'proof_of_payment'=>$proofOfPayment,
	   		'dispute_reason_id'=>$disputeReasonId,
	   		'added_by'=>Session::get('individual_client_id'),
	   		'created_at'=>Carbon::now()
	   ]);

	   $Dispute_id=DB::getPdo()->lastInsertId();
	   $personName=$records[0]['concerned_person_name'];
	   General::storeAdminNotificationForDispute('Business',$duesRecord->added_by,$duesRecord->business_id,$personName,$Dispute_id);

	   return redirect()->route('front-business.dashboard')->with('message','Dispute raised successfully.');
	}
		
}
