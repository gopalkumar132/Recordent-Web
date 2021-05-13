<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session as Session;
use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Students;
use App\User;
use App\StudentDueFees;
use App\StudentPaidFees;
use App\MembershipPayment;
use App\DuesSmsLog;
use App\Sector;
use App\Country;
use App\State;
use App\City;
use App\ConsentRequest;
use App\ConsentAPIResponse;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use PDF;
use App\Notifications\UpdateProfileEmail;
use Mail;
use General;
use HomeHelper;
use Illuminate\Support\Collection;
use App\Services\SmsService;
use PaytmWallet;
use App\ConsentPayment;
use Str;
use CreditReportHelper;

class AllStudentController extends Controller
{
	public function importExcelView()
    {
        return view('admin.import-excel');
    }

    public function importExcel()
    {
        Excel::import(new StudentsImport, request()->file('file'));

        return redirect()->back()->with('success', 'All good!');
    }

    public function export()
    {
       return Excel::download(new StudentsExport, 'MyRecords.xlsx');
    }
	public function studentRecords(Request $request)
    {

    	$User = Auth::user();
		if(!is_null($request->getQueryString()) && ($User->email_verified_at == NULL  || $User->email_sent_at == NULL))
		{
			//return redirect('admin/auth/verify');
		}
		if(!empty($request->input('student_first_name')) || !empty($request->input('student_last_name')) || !empty($request->input('student_dob')) || !empty($request->input('father_first_name')) || !empty($request->input('mother_first_name')) || !empty($request->input('aadhar_number')) || !empty($request->input('contact_phone')) || !empty($request->input('due_amount')) || !empty($request->input('due_date_period'))){

			$records = StudentDueFees::with(['addedBy','profile'])->whereHas('addedBy')->whereHas('profile',function($q) use ($request){
				if(!empty($request->input('student_first_name'))){

					$q->where('students.person_name', 'LIKE' , General::encrypt($request->input('student_first_name')));
				}

				if(!empty($request->input('student_dob'))){
					$dob = Carbon::createFromFormat('Y-m-d',$request->input('student_dob'));
					$dob =  $dob->format('Y-m-d');
					$dob = General::encrypt($dob);
					$q->where('dob','LIKE',$dob);
				}

				if(!empty($request->input('father_first_name'))){
					$q->where('father_name','=',General::encrypt($request->input('father_first_name')));
				}
				if(!empty($request->input('mother_first_name'))){
					$q->where('mother_name','=',General::encrypt($request->input('mother_first_name')));
				}
				if(!empty($request->input('aadhar_number'))){
					//dd($request->input('aadhar_number'));
					$q->where('aadhar_number','=',General::encrypt(str_replace('-','',$request->input('aadhar_number'))));
				}
				if(!empty($request->input('contact_phone'))){

					$q->where('students.contact_phone','=',General::encrypt($request->input('contact_phone')));
				}
			})
			->whereNull('deleted_at');

			if(!empty($request->input('due_date_period'))){

				$dueDatePeriod = $request->input('due_date_period');
				if($dueDatePeriod=='less than 30days'){
					$records = $records->whereRaw("datediff(CURDATE(),due_date) < 30");
				}elseif($dueDatePeriod=='30days to 90days'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=90 AND datediff(CURDATE(),due_date) >=30 ");

				}elseif($dueDatePeriod=='91days to 180days'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=180 AND datediff(CURDATE(),due_date) >=91 ");
				}elseif($dueDatePeriod=='181days to 1year'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=365 AND datediff(CURDATE(),due_date) >=181 ");
				}elseif($dueDatePeriod=='more than 1year'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) >365 ");
				}
			}

			if(!empty($request->input('due_amount'))){
				$records = $records->withCount([
					'paid AS totalPaid' => function ($query) {
	            		$query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at');
	        		}
	    		]);
			}

			//$records = $records->where('totalPaid','<',600);

			$records = $records->orderBy('id','DESC');
			$records = $records->get();

			if(!empty($request->input('due_amount'))){
				$records = $records->filter(function($key,$value) use ($request){

					$dueAmount = $request->input('due_amount');

					if(is_null($key->totalPaid))
					{
						if($dueAmount=='less than 1000'){
							return $key->due_amount > 0 && $key->due_amount < 1000;
						}elseif($dueAmount=='1000 to 5000'){
							return $key->due_amount >=1000 && $key->due_amount <= 5000;
						}elseif($dueAmount=='5001 to 10000'){
							return $key->due_amount >=5001 && $key->due_amount <= 10000;
						}elseif($dueAmount=='10001 to 25000'){
							return $key->due_amount >=10001 && $key->due_amount <= 25000;
						}elseif($dueAmount=='25001 to 50000'){
							return $key->due_amount >=25001 && $key->due_amount <= 50000;
						}elseif($dueAmount=='more than 50000'){
							return $key->due_amount >50000;
						}else{
							return true;
						}

					}else{
						$remain = $key->due_amount - $key->totalPaid;
						if($dueAmount=='less than 1000'){
							return $remain > 0 && $remain < 1000;
						}elseif($dueAmount=='1000 to 5000'){
							return $remain >=1000 && $remain <= 5000;
						}elseif($dueAmount=='5001 to 10000'){
							return $remain >=5001 && $remain <= 10000;
						}elseif($dueAmount=='10001 to 25000'){
							return $remain >=10001 && $remain <= 25000;
						}elseif($dueAmount=='25001 to 50000'){
							return $remain >=25001 && $remain <= 50000;
						}elseif($dueAmount=='more than 50000'){
							return $remain >50000;
						}else{
							return true;
						}
					}

				});
			}
		}else{
			$records = Collection::make();
		}
		$canRequestConsent = Collection::make();
		$next3MinForCounDown = '';
		$requestConsentCheckStatus = false;
		if($records->count()){
			$canRequestConsent = General::requestConsentEligible(Auth::id(),$request->contact_phone,'INDIVIDUAL');
			if($canRequestConsent->count()){
				if($canRequestConsent->count()<2){
		            $canRequestConsentFirstRecord = $canRequestConsent->first();
		            $next3MinForCounDown = Carbon::createFromFormat('Y-m-d H:i:s', $canRequestConsentFirstRecord->created_at);
		            $next3MinForCounDown->addMinute(3);
		            $next3MinForCounDown = $next3MinForCounDown->format('F d,Y H:i:s');
	           }
	    	}

	    	$requestConsentCheckStatus = General::requestConsentCheckStatus(Auth::id(),$request->contact_phone,$request->student_first_name,'INDIVIDUAL');

		}
		if(!empty($request->contact_phone)){
			General::updateConsentRequestSearchedAtToLatest(Auth::id(),$request->contact_phone,$request->student_first_name,'INDIVIDUAL');
		}


		//dd($requestConsentCheckStatus);
		$consentListing = consentRequest::with('payment')
			->where('added_by',Auth::id())
			->where(function ($query)  {
  			  $query->where('customer_type','=','INDIVIDUAL')
    	      ->orWhere('customer_type','=', 'BUSINESS');
			})
			->orderBy('searched_at','DESC')
			->get();
			//DB::enableQueryLog();


			$consent_payment_dtls = DB::table('consent_payment')
						->join('consent_request', function($join){
							$join->on('consent_request.added_by', '=', 'consent_payment.added_by')
							->on('consent_request.id', '=', 'consent_payment.consent_id')
							->where('consent_request.added_by', Auth::id());
						})
						->join('consent_api_response', 'consent_request.id', '=', 'consent_api_response.consent_request_id')
            			->select('consent_payment.*', 'consent_payment.created_at as payment_date','consent_payment.status as payment_status', 'consent_api_response.consent_request_id', 'consent_api_response.created_at','consent_api_response.status as consent_api_response_status', 'consent_api_response.response as car_response')
						->where('consent_request.customer_type','=','USBUSINESS')
						->where('consent_payment.customer_type','=','')
						->orWhere('consent_payment.customer_type','=','USBUSINESS')
						->where('consent_payment.added_by', Auth::id())
						->groupBy('consent_request.id')
						->orderBy('consent_payment.id','DESC')
			            ->get();

						//dd(DB::getQueryLog());
						//echo $consent_payment_dtls->toSql(); die;
		$currentTime = Carbon::now();

		return view('admin.all-students.index',compact('records','canRequestConsent','next3MinForCounDown','requestConsentCheckStatus','consent_payment_dtls','consentListing','currentTime'));
    }

	/* Code For Us Credit Report */
	public function uscreditreport(Request $request)
    {

		$User = Auth::user();
		if(!is_null($request->getQueryString()) && ($User->email_verified_at == NULL  || $User->email_sent_at == NULL))
		{
			//return redirect('admin/auth/verify');
		}
		//dd($requestConsentCheckStatus);
		$consentListing = consentRequest::with('payment')
			->where('added_by',Auth::id())
			->where('customer_type','=','INDIVIDUAL')
			//->where('is_expired_by_admin',2)
			->orderBy('searched_at','DESC')
			->get();
		$currentTime = Carbon::now();



		//echo "ENCRYPT:" . General::encrypt($response_new1);
		//die(':YESSSS');
		//echo "ENCRYPT" . $ency =  General::encrypt("9911045946");
		//echo "[]DECRYPT[]:" . General::decrypt($response_new1)."ROOP";

		//echo "[]DECRYPT[]" . General::decrypt($enc_val);

		//reset below listed session values, before loading form.
		if(!empty(Session::get('business_name'))){
				Session::remove('business_name');
		}
		if(!empty(Session::get('address_line1'))){
				Session::remove('address_line1');
		}
		if(!empty(Session::get('city_us'))){
				Session::remove('city_us');
		}
		if(!empty(Session::get('state_us'))){
				Session::remove('state_us');
		}
		if(!empty(Session::get('zip_us'))){
				Session::remove('zip_us');
		}

		 $states = State::where('country_id',231)->get();
	     $stateIds = [];
	     foreach ($states as $state){
	        $stateIds[] =$state->id;
	     }
	     $cities = City::whereIn('state_id',$stateIds)->orderBy('name','ASC')->get();
	     $sectors = Sector::where('status',1)->whereNull('deleted_at')->orderBy('id','ASC')->get();
    	//return view('admin.business.add-record.add-record',compact('states','cities','sectors'));


		//loading view for US-Credit Report
		return view('admin.us-creditreport.index',compact('currentTime','states','cities','sectors'));
    }

   
   	public function searchRequestConsent(Request $request){
        $dueId='';
		$records = StudentDueFees::with(['addedBy','profile'])->whereHas('addedBy')->whereHas('profile',function($q) use ($request){
				if(!empty($request->input('name'))){

					$q->where('students.person_name', 'LIKE' , General::encrypt($request->input('name')));
				}
				if(!empty($request->input('contact_phone'))){
					$q->where('students.contact_phone','=',General::encrypt($request->input('contact_phone')));
				}
			})
			->whereNull('deleted_at');
			$records = $records->orderBy('id','DESC');
			$records = $records->get();
		foreach($records as $data){
			if(!Auth::user()->hasRole('admin')){
                $dueId .= $data->id.',';
			}
		}
		$dueId = trim($dueId,",");
		return $dueId;
	}

	public function studentData($studentId)
	{
		$studentDueData = StudentDueFees::select('student_due_fees.id As dueId','student_due_fees.student_id','due_amount','due_date','student_due_fees.created_at As ReportedAt','paid_amount','paid_date','due_note','customer_no','invoice_no','users.business_name','users.address','users.id as userId','user_types.name as userType','states.name as stateName','cities.name as cityName')
										->leftJoin('student_paid_fees','student_due_fees.student_id','=','student_paid_fees.student_id')
										->leftJoin('users','users.id','=','student_due_fees.added_by')
										->leftJoin('states','users.state_id','=','states.id')
										->leftJoin('cities','users.city_id','=','cities.id')
										->leftJoin('user_types','users.user_type','=','user_types.id')
										->where('student_due_fees.student_id','=',$studentId)
			->whereNull('student_due_fees.deleted_at')
			->groupBy('student_due_fees.id')
		    ->orderBy('student_due_fees.created_at','DESC')
			->get();

			$student = Students::where('id','=',$studentId)->first();
			//dd($studentDueData);
		return view('admin.all-students.student-data',compact('studentDueData','student','studentId'));
	}


	public function reportedBy($id){
		$studentDueData = StudentDueFees::select('student_due_fees.id As dueId','student_due_fees.student_id','due_amount','due_date','student_due_fees.created_at As ReportedAt','paid_amount','paid_date','due_note','customer_no','invoice_no','users.business_name','users.id as userId','user_types.name as userType','students.person_name','students.aadhar_number')
										->leftJoin('student_paid_fees','student_due_fees.student_id','=','student_paid_fees.student_id')
										->leftJoin('users','users.id','=','student_due_fees.added_by')
										->leftJoin('user_types','users.user_type','=','user_types.id')
										->leftJoin('students','students.id','=','student_due_fees.student_id')
										->where('student_due_fees.added_by','=',$id)
										->whereNull('student_due_fees.deleted_at')

										//->orderBy('students.firstname','asc')
										->orderBy('student_due_fees.id','desc')
										->groupBy('student_due_fees.id')
										->get();

		return view('admin.all-students.student-reported',compact('studentDueData'));
	}



	public function storePayAmount($studentId, Request $request)
	{
	    $validator = Validator::make($request->all(), [
           'contact_phone' => 'required|digits:10,10',
           'outstanding' => 'required',
           'pay_date' => 'required|date',
           'pay_amount' => 'required|numeric',
           'agree_terms' => 'required',
       ]);
        if($validator->fails()) {
           return redirect()->back()
                       ->withErrors($validator)
                       ->withInput();
       }
		if($studentId == ''){
			//dd(1);
			return redirect()->back()->withError('Error: Student id is not given');
		}

		$student = Students::where('id','=',$studentId)->first();

		if(empty($student)){
			//dd(2);
			return redirect()->back()->withError('Error: Student record not exists');
		}

		$valuesForStudent = ['contact_phone' => $request->input('contact_phone'),
							 'updated_at' => Carbon::now(),
							];

		$student->update($valuesForStudent);


		/*$valuesForStudentDueFees = ['student_id' => $studentId,
									 'due_date' => Carbon::now(),
								     'due_note' => $request->input('due_note'),
								     'created_at' => Carbon::now()
								    ];

		if($request->input('outstanding') != 'all'){
			$studentDueFee = StudentDueFees::where('id','=',$request->input('outstanding'))->first();
			$valuesForStudentDueFees['due_amount'] = $studentDueFee->due_amount - $request->input('pay_amount');
		}*/


		$valuesForStudentPayFees = ['student_id' => $studentId,
									 'due_id' => $request->input('outstanding'),
									 'paid_date' => Carbon::now(),
									 'paid_amount' => $request->input('pay_amount'),
								     'paid_note' => $request->input('due_note'),
								     'created_at' => Carbon::now()
								    ];

		$studentFee = StudentPaidFees::create($valuesForStudentPayFees);
		if($studentFee->id == ''){
			//dd(3);
			return redirect()->back()->withError('Error: Paid Amount not stored');
		}

		return redirect()->route('student-data',$studentId)->withMessage('Success: Paid Amount stored');

	}

	public function paymentHistory(Request $request)
	{
		//return Response::json(['error' => true,'message'=>'Record not found. Please try again'], 300);
		$dueId = $request->input('due_id');
		if(empty($dueId)){
			return Response::json(['error' => true,'message'=>'Due id can not be null'], 300);
		}
		$paymentHistory = StudentPaidFees::select('id','paid_date','paid_amount','paid_note','deleted_at')->whereNull('deleted_at')->where('due_id',$dueId)->orderBy('id','DESC')->get();
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
			$withHtml = View('admin/students/payment-history', compact('paymentHistory'))->render();
			return Response::json(['success' => true,'noData'=>false,'paymentHistoryData'=>$withHtml], 200);
		}else{
			return Response::json(['success' => true,'message'=>'','noData'=>true], 200);
		}

		//return Response::json(['success' => true,'message'=>'','paymentHistory'=>$paymentHistory], 200);

	}

	public function paymentHistoryDelete(Request $request)
	{
		//return Response::json(['error' => true,'message'=>'Record not found. Please try again'], 300);
		$paymentId = $request->input('payment_id');
		if(empty($paymentId)){
			return Response::json(['error' => true,'message'=>'can not find payment history'], 300);
		}

		$paymentHistory = StudentPaidFees::where('id',$paymentId)->whereNull('deleted_at')->first();

		if(!empty($paymentHistory)){

			$paymentHistory->deleted_at = Carbon::now();
			$paymentHistory->update();
			return Response::json(['success' => true,200]);
		}else{
			return Response::json(['error' => true,'message'=>'can not find payment history'], 300);
		}


	}

	public function deleteDue(Request $request)
	{
			$validator = Validator::make($request->all(), [
	           'due_id' => 'required',
	           'delete_note' => 'required',
	           'agree_terms' => 'required',
       		]);
       		if($validator->fails()) {
	           return redirect()->back()->withErrors($validator);
	       }
			$dueId = $request->input('due_id');
			$deleteNote = $request->input('delete_note');
			$agreeTerms = $request->input('agree_terms');

			/*if(empty($dueId)){
				return redirect()->back()->withErrors(['Due id can not be null']);
			}

			if(empty($deleteNote)){
				return redirect()->back()->withErrors(['Delete-note can not be empty']);
			}*/

			$studentDue = StudentDueFees::where('id',$dueId)->whereNull('deleted_at')->first();
			if(empty($studentDue)){
				return redirect()->back()->withErrors(['can not find due record']);
			}

			$studentDue->deleted_at = Carbon::now();
			$studentDue->delete_note = $deleteNote;
			$studentDue->update();
			return redirect()->back()->withMessage('successfully deleted');


	}


	public function studentRecordsForSms(Request $request)
    {

		$User = Auth::user();
		$organizationId = $request->input('organization_id');
		if(!is_null($request->getQueryString()) && ($User->email_verified_at == NULL  || $User->email_sent_at == NULL))
		{
			//return redirect('admin/auth/verify');
		}
    	$authId = Auth::id();
    	$currentDate =Carbon::now();
		$records =StudentDueFees::with(['addedBy','profile'])->whereHas('addedBy')->whereHas('profile',function($q){
    		$q->where('students.contact_phone' , '!=' , '');
		})->whereNull('deleted_at');

		if(!empty($organizationId)){
    		$records = $records->where('added_by',$organizationId);
    	}
		if(!empty($request->input('due_date_period'))){

			$dueDatePeriod = $request->input('due_date_period');
			if($dueDatePeriod=='less than 30days'){
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) < 30 ");
			}elseif($dueDatePeriod=='30days to 90days'){
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=90 AND datediff(CURDATE(),due_date) >=30 ");

			}elseif($dueDatePeriod=='91days to 180days'){
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=180 AND datediff(CURDATE(),due_date) >=91 ");
			}elseif($dueDatePeriod=='181days to 1year'){
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=365 AND datediff(CURDATE(),due_date) >=181 ");
			}elseif($dueDatePeriod=='more than 1year'){
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) >365 ");
			}
		}

			$records = $records->withCount([
				'paid AS totalPaid' => function ($query) use($authId,$organizationId){
            		$query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at');
            		if(!empty($organizationId)){
	            		$query->where('added_by',$organizationId);

	            	}
        		}
    		]);
	   // $records = $records->groupBy(['due_date','added_by']);
		$records = $records->orderBy('id','DESC');
		$records = $records->get();
		if(!empty($request->input('due_amount'))){
			$records = $records->filter(function($key,$value) use ($request){

				$dueAmount = $request->input('due_amount');

				if(is_null($key->totalPaid))
				{
					if($dueAmount=='less than 1000'){
						return $key->due_amount > 0 && $key->due_amount < 1000;
					}elseif($dueAmount=='1000 to 5000'){
						return $key->due_amount >=1000 && $key->due_amount <= 5000;
					}elseif($dueAmount=='5001 to 10000'){
						return $key->due_amount >=5001 && $key->due_amount <= 10000;
					}elseif($dueAmount=='10001 to 25000'){
						return $key->due_amount >=10001 && $key->due_amount <= 25000;
					}elseif($dueAmount=='25001 to 50000'){
						return $key->due_amount >=25001 && $key->due_amount <= 50000;
					}elseif($dueAmount=='more than 50000'){
						return $key->due_amount >50000;
					}else{
						return true;
					}

				}else{
					$remain = $key->due_amount - $key->totalPaid;
					if($dueAmount=='less than 1000'){
						return $remain > 0 && $remain < 1000;
					}elseif($dueAmount=='1000 to 5000'){
						return $remain >=1000 && $remain <= 5000;
					}elseif($dueAmount=='5001 to 10000'){
						return $remain >=5001 && $remain <= 10000;
					}elseif($dueAmount=='10001 to 25000'){
						return $remain >=10001 && $remain <= 25000;
					}elseif($dueAmount=='25001 to 50000'){
						return $remain >=25001 && $remain <= 50000;
					}elseif($dueAmount=='more than 50000'){
						return $remain >50000;
					}else{
						return true;
					}
				}

			});
		}
		$records = $records->filter(function($key,$value){
			if(is_null($key->totalPaid))
			{
				return $key->due_amount > 0;
			}else{
				$remain = $key->due_amount - $key->totalPaid;
				return $remain > 0;
			}
		});
		$records = $records->customPaginate(50);
		$records = $records->appends(request()->query());

		$smsTemplates = \Config::get('sms_templates');
		$organizations = User::select('id','business_short','mobile_number','state_id','city_id')->where('status',1)->whereNotNull('email_verified_at')->get();
		$authUser = null;
		if(!empty($request->organization_id)){
			$authUser = User::where('status',1)->whereNotNull('email_verified_at')->where('id',$request->organization_id)->first();
		}

		return view('admin.all-students.send-sms',compact('records','organizations','smsTemplates','authUser'));
    }


	public function studentRecordsSendSms(Request $request){
		$validator = Validator::make($request->all(), [
		   'ids' => 'required',
		   'template_id' => 'required_without:message',
		   'message'=>'required_without:template_id|string|max:145',
		   'within_date'=>'nullable|date',
		   ],
		   [
		   	'ids.required'=>'Select records to send sms',
		   	'template_id.required_without'=>'Template is required when message is blank',
		   	'message.required_without'=>'The :attribute is required when no template is choosen'
		   ]
		);

	    if($validator->fails()) {
           return redirect()->back()
                       ->withErrors($validator)
                       ->withInput();
       }

       $ids = explode(",",$request->ids);
       $AuthId = Auth::id();

       $studentList = StudentDueFees::with(['profile','addedBy'])->whereHas('addedBy')->whereHas('profile',function($q){
	   		$q->whereNotNull('contact_phone')->where('contact_phone','!=','');
	   })->whereIn('id',$ids)->get();

       if(!$studentList->count()){
       		return redirect()->back()->with(['message' => "can not send sms ", 'alert-type' => 'error']);
       }

       $template_id = $request->template_id;
       $message = $request->message;
       if(empty($message)){
	       $message = \Config::get('sms_templates.'.$template_id.'.text');
			if(empty($message)){
				return redirect()->back()->with(['message' => 'can not find template', 'alert-type' => 'error']);
			}
       }
       $withinDate = $request->within_date;
	   $sent = true;
       $smsService = new SmsService();
       foreach ($studentList as $data) {

	        $authUser = $data->addedBy;
	        if($template_id){
				$message = General::replaceTextInSmsTemplate($template_id,'INDIVIDUAL',$authUser,$withinDate,'',$data);
			}
	   		$message =strip_tags($message);

	   		$smsResponse = $smsService->sendSms($data->profile->contact_phone,$message);
	   		if($smsResponse['fail_to_send']){
	   			$sent = false;
	   		}
	        $insert = [
        		'contact_phone'=>$data->profile->contact_phone,
        		'customer_id'=>$data->profile->id,
        		'due_id'=>$data->id,
        		'customer_type'=>'Individual',
        		'created_at'=>Carbon::now(),
        		'added_by'=>$AuthId,
        		'message'=>$message,
        		'approve_reject_status'=>1,
	        	'approve_reject_at'=>Carbon::now()
        	];

	        if($smsResponse['sent']==1){
	        	$insert['status'] = 1;
	        }else{
	        	$insert['status'] = 2;
	        }
	        DuesSmsLog::create($insert);
	   }

       if(!$sent){
       		return redirect()->back()->withInput()->with(['message' => "can not send sms to some phones. Server unavailable.", 'alert-type' => 'error']);
       }
       return redirect()->back()->with(['message' => "SMS sent successfully.", 'alert-type' => 'info']);
	}

	public function studentRecordsSentSms(Request $request){
		$AuthId = Auth::id();
		$records = DuesSmsLog::with('customer')->where('customer_type','=','Individual')->where('added_by',$AuthId)->orderBy('created_at','DESC')->paginate(50);

		return view('admin.all-students.sent-sms',compact('records'));

	}

	public function consentPayment($consentId,Request $request){
		
		$count_consentRequest = ConsentRequest::where('added_by',Auth::id())->get();
		// $consentRequest = ConsentRequest::all();
		$consentRequest = $count_consentRequest->where('id',$consentId)->where('status',3)->first();
		$count_rec_b2c = $count_consentRequest->where('report','2');
		$count_rec_b2b = $count_consentRequest->where('report','3');
		$free_limit_b2c = config('custom_configs.free_limit_b2c');
		$free_limit_b2b = config('custom_configs.free_limit_b2b');
		$total_free_reports=config('custom_configs.total_free_reports');
		
		if(count($count_rec_b2c) <= $free_limit_b2c && count($count_rec_b2b) <= $free_limit_b2b && count($count_rec_b2b)+count($count_rec_b2c) <= $total_free_reports){

            if($consentRequest->report == 2 || $consentRequest->report == 3){
            	if($consentRequest->report == 2){
           			$consent_payment_value = $consentRequest->report == 2 ? HomeHelper::getConsentComprehensiveReportPrice() : HomeHelper::getConsentRecordentReportPrice();
            	}
            	
            	if($consentRequest->report == 3){
            		$consent_payment_value = $consentRequest->report == 3 ? HomeHelper::getConsentComprehensiveReportPrice($consentRequest->report==3) : HomeHelper::getConsentRecordentReportPrice();
            	}
            	$consent_payment_value_gst_in_perc = HomeHelper::getConsentRecordentReportGst();
        	} else {
            
            	$consent_payment_value = setting('admin.consent_payment_value') ? (int)setting('admin.consent_payment_value') : 100 ;
            	$consent_payment_value_gst_in_perc = setting('admin.consent_payment_value_gst_in_perc') ? (int)setting('admin.consent_payment_value_gst_in_perc') : 0 ;
        	}

	        if($consent_payment_value_gst_in_perc > 0){
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
        	
        	$invoice_no = MembershipPayment::where('created_at','>=',date('Y-m-d 00:00:00'))->where('status',4)->count();
        	$invoice_no = $invoice_no+1;
         	$user = User::findOrFail($consentRequest->added_by);
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

            $valuesForMembershipPayment = [
                'customer_id' => $user->id,
                'invoice_id' => date('dmY').sprintf('%07d',$invoice_no),
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

            $consent_payment = ConsentPayment::create($consentPayment);
            $membershipPayment = MembershipPayment::create($valuesForMembershipPayment);
			
			return redirect()->back()->with('consent_payment_value',$consent_payment_value)->with('consent_payment_value_gst_in_perc',$consent_payment_value_gst_in_perc)->with('consent_payment_value_final',$consent_payment_value_final);
		} 


		if(empty($consentRequest)){
			return redirect()->back()->withInput()->with(['message' => "Something went wrong", 'alert-type' => 'error']);
		}

		 $consent_payment_successful_valid_for_in_year = setting('admin.consent_payment_successful_valid_for_in_year') ? (int)setting('admin.consent_payment_successful_valid_for_in_year') : 7 ;

		$currentTime = Carbon::now();
        $beforeDateTime = Carbon::now()->subYear($consent_payment_successful_valid_for_in_year);

        $consentPaymentAlready = ConsentPayment::where('consent_id',$consentRequest->id)
        	->where('status',4)
        	->where('added_by',Auth::id())
        	->where('updated_at','>=',$beforeDateTime)
        	->where('customer_type','=','INDIVIDUAL')
        	->orderBy('id','DESC')
        	->first();
        if(!empty($consentPaymentAlready)){
        	return redirect()->back()->withInput()->with(['message' => "Already paid for this consent. No need to pay again", 'alert-type' => 'error']);
        }
        // $consent_payment_value = setting('admin.consent_payment_value') ? (int)setting('admin.consent_payment_value') : 100 ;
        // $consent_payment_value_gst_in_perc = setting('admin.consent_payment_value_gst_in_perc') ? (int)setting('admin.consent_payment_value_gst_in_perc') : 0 ;

        if(Auth::user()->user_pricing_plan != NULL){
	        $consent_payment_value = $consentRequest->report == 2 ? HomeHelper::getConsentComprehensiveReportPrice() : HomeHelper::getConsentRecordentReportPrice();
			$consent_payment_value_gst_in_perc = HomeHelper::getConsentRecordentReportGst();
        }else{
	        $consent_payment_value = setting('admin.consent_payment_value') ? (int)setting('admin.consent_payment_value') : 100 ;
	        $consent_payment_value_gst_in_perc = setting('admin.consent_payment_value_gst_in_perc') ? (int)setting('admin.consent_payment_value_gst_in_perc') : 0 ;
        }

        if($consent_payment_value_gst_in_perc>0){
        	$temp = ($consent_payment_value * $consent_payment_value_gst_in_perc)/100;
        	$temp = round($temp);
        	$temp = (int)$temp;
        	$consent_payment_value = $consent_payment_value + $temp;
        }

        General::add_to_debug_log(Auth::id(), "Creating ConsentPayment info.");

		$consentPayment = ConsentPayment::create([
			'order_id'=>Str::random(40),
			'customer_type'=>$consentRequest->customer_type,
			'person_name'=>$consentRequest->person_name,
			'contact_phone'=>$consentRequest->contact_phone,
			'consent_id'=>$consentRequest->id,
			'payment_value'=>$consent_payment_value,
			'status'=>1,//initiated
			'created_at'=>Carbon::now(),
			'added_by'=>Auth::id(),
		]);

		General::add_to_debug_log(Auth::id(), "Creating ConsentPayment info success.");

		//dd(route('admin.consent.payment-callback'));
		$userDataToPaytm = User::findOrFail(Auth::user()->id);
		$userDataToPaytm_name = preg_replace('/\s+/', '_', $userDataToPaytm->name);

		$consentPayment->pg_type=setting('admin.payment_gateway_type');
	    $consentPayment->update();

		if(setting('admin.payment_gateway_type')=='paytm'){
			$payment = PaytmWallet::with('receive');
			$payment->prepare([
				'order'=> $consentPayment->order_id,
				'user'=> $userDataToPaytm_name,
				'mobile_number'=> $userDataToPaytm->mobile_number,
				'email'=> $userDataToPaytm->email,
				'amount'=> $consent_payment_value,
				'callback_url'=> route('admin.consent.payment-callback')
			]);

			General::add_to_payment_debug_log(Auth::id(), 1);

			return $payment->view('admin.payment-submit')->receive();
		} else {

			$postData = [
				'amount'=>$consent_payment_value,
				'txnid'=>$consentPayment->order_id,
				'firstname' => preg_replace('/\s+/', '', $userDataToPaytm->name),
				'email' => $userDataToPaytm->email,
				'phone' => $userDataToPaytm->mobile_number,
				'surl'=>route('admin.consent.payment-callback'),
			];

			General::add_to_debug_log(Auth::id(), "consent_payment_value = ".$consent_payment_value);

			$payuForm = General::generatePayuForm($postData);
			General::add_to_payment_debug_log(Auth::id(), 1);

			return view('admin.payment-submit',compact('payuForm'));
		}
	}

	
	/*
		Name : postpaid_invoice_sendmail()
		desc : sending invoice as PDF format as attachment, to clients(logged in User)
	*/
	public function usb2b_invoice_sendmail($consent_payment_id){
		
		$consent_payment = Consentpayment::where('id', $consent_payment_id)->first();

		$dateTime = date('d-m-Y H:i', strtotime($consent_payment->created_at));

		$usa_b2b_credit_report_price = Auth::user()->user_pricing_plan->usa_b2b_credit_report;
		$consent_payment_value_gst_in_perc = HomeHelper::getConsentRecordentReportGst();

		$gst_price = 0;
		if ($consent_payment_value_gst_in_perc > 0) {
			$temp = ($usa_b2b_credit_report_price * $consent_payment_value_gst_in_perc) / 100;
			$temp = round($temp);
			$gst_value = (int)$temp;
		}

		$user = Auth::user();
		$data["email"] = $user->email;
		$data["client_name"] = $user->name;
		$data["subject"] = 'Invoice for US Business Credit Report';

		$pdf = PDF::loadView('admin.us_report_invoice.us_pdf_report_invoice', compact('user', 'dateTime','consent_payment', 'gst_value', 'usa_b2b_credit_report_price'))->setPaper('a4','portrait');
		
		try{
			Mail::send('admin.us_report_invoice.us_report_invoice_mail', compact('user'), function($message)use($data,$pdf) {
				$message->to($data["email"], $data["client_name"])
				->subject($data["subject"])
				->attachData($pdf->output(), "us_report_invoice.pdf");
			});
		}catch(JWTException $exception){
			$this->serverstatuscode = "0";
			$this->serverstatusdes = $exception->getMessage();
		}
		
		if (Mail::failures()) {
			$this->statusdesc  =   "Error sending mail";
			$this->statuscode  =   "0";
		} else {

		   $this->statusdesc  =   "Message sent Succesfully";
		   $this->statuscode  =   "1";
		}

		return response()->json(compact('this'));
	}

	public function consentPaymentCallback(Request $request)
	{
		//redirect from here for USB2B request Response Handling section.
		$business_name = "";
		if(!empty(Session::get('business_name'))){
			//Code begins here
			$response = General::verifyPayuPayment($request->all());
			$paymentStatus = $response['paymentStatus']=='success' ? 'success': ($response['paymentStatus']=='failure' ? 'failed' : 'open');

			$user_name = Auth::user()->name;
			$user_email_id = Auth::user()->email;
			$mobile_number = Auth::user()->mobile_number;

			//When paymentgateway returns success full response.
			if($paymentStatus == 'success'){

				$payu_mihpayid       = $response['mihpayid'];    
				$payu_bank_ref_num   = $response['bank_ref_num']; 
				$payu_order_id       = $response['ORDERID'];

				$uniqueUrlCode = Str::random(10);
				$insert = [
					'added_by' => Auth::id(),
					'customer_type' => 'USBUSINESS',
					'created_at' => Carbon::now(),
					'searched_at' => Carbon::now(),
					'unique_url_code' => $uniqueUrlCode,
					'status' => 0,
					'person_name' => $user_name,
					'contact_phone' => $mobile_number
				];

				$consentRequestInsert = ConsentRequest::create($insert);

				//Adding Consentpayment, data, in TABLE: consent_payment.
				General::add_to_debug_log(Auth::id(), "Creating ConsentPayment info.");
				$consentPayment = ConsentPayment::create([
					'order_id' => $response['ORDERID'],
					'customer_type' => "USBUSINESS",
					'person_name' =>   $user_name,
					'contact_phone' => $mobile_number,
					'consent_id' => $consentRequestInsert->id,
					'payment_value' => $response['net_amount_debit'],
					'status' => ($response['status']=="success") ? "4" : "3",
					'created_at' => Carbon::now(),
					'added_by' => Auth::id(),
					'raw_response'=> json_encode($response),
					'pg_type' => 'payu'
				]);
				General::add_to_debug_log(Auth::id(), "Creating ConsentPayment info success.");

				$consentPayment->transaction_id = $response['TXNID'] ?? $response['mihpayid'] ?? '';
				$consentPayment->payment_mode = $response['PAYMENTMODE'] ?? $response['mode'] ?? '';

				//maintain new consent_request_id in session deleting previous one.
				if(!empty(Session::get('consent_request_us_id'))){
					Session::remove('consent_request_us_id');
				}

				Session::put('consent_request_us_id', $consentRequestInsert->id);

				//set variables.
				$set_business_name  = Session::get('business_name');
				$set_address 		= Session::get('address_line1');
				$set_state_code     = General::get_state_code(Session::get('state_us'));
				$set_city    	    = General::get_city_name(Session::get('city_us'));
				$set_zip_code    	= Session::get('zip_us');

				Session::remove('state_us');
				Session::remove('business_name');
				Session::remove('city_us');
				Session::remove('state_us');
				Session::remove('zip_us');

				//call API for Equifax report:
				$endPoint = config('app.equifax_us_b2b_url');
				$postData = '{
					"EfxCommercialRequest": {
						"serviceCode": "SB1",
						"version": "5.0",
						"tranID": "XSOF",
						"customerNumber":"'.config("app.us_customer_id").'",
						"securityCode": "'.config("app.us_security_code").'",
						"CustomerSecurityInfo": {
							"ProductCode": {
								"name": "'.config("app.us_score_name").'",
								"code": "'.config("app.us_score_code").'",
								"value": "RPT'.config("app.us_score_code").'"
							},
							"ProductCode": {
								"name": "'.config("app.us_product_name").'",
								"code": "'.config("app.us_product_code").'",
								"value": "RPT'.config("app.us_product_code").'"
							},
							"Channel": {
								"Name": "ISTS",
								"IdNumber": "3"
							}
						},
						"StandardRequest": {
							"Folder": {
								"IdTrait": {
									"CompanyNameTrait": {
										"BusinessName": "'.$set_business_name.'"
									},
									"AddressTrait": {
										"PostalCode": "'.$set_zip_code.'",
										"City": "'.$set_city.'",
										"State": "'.$set_state_code.'",
										"AddressLine1": "'.$set_address.'"
									}
								}
							}
						}
					}
				}';

			 	$response_api = General::process_equifax_request($postData, $endPoint);

			 	// Log::debug('response_api = '.print_r($response_api, true));
			 	// Log::debug('response_api = '.print_r($postData, true));
			 	$consent_payment_value = $response['net_amount_debit'];
			 	$set_business_name = base64_encode($set_business_name);

				if(empty($response_api) || (!empty($response_api->EfxTransmit->ProductCode[0]->value) && $response_api->EfxTransmit->ProductCode[0]->value=="Commercial - NoHit")) {

					//Code for INSERTING DATA in database, when API request fails.
					$api = new ConsentAPIResponse();
					$api->consent_request_id = Session::get('consent_request_us_id');
					$api->response           = General::encrypt(json_encode($response_api));
					$api->request_data 		 = General::encrypt(json_encode($postData));
					$api->ip_address 		 = request()->ip();
					$api->created_at         = Carbon::now();
					$api->status = 3;
					$api->save();

					//$data_sess = Session::all();
					//reset all session values before redirecting showing error messages.

					General::add_to_debug_log(Auth::id(), "Equifax response fails");

					$get_refund_response = General::payu_refund_api($consent_payment_value, $payu_mihpayid, $payu_bank_ref_num);
					$array_refund_rep = json_decode($get_refund_response, true);

					$refund_request = array(
											"key" => config('app.payu_merchant_key'),
											"salt" => config('app.payu_merchant_salt'),
											"command" => "cancel_refund_transaction",
											"refund_amt" => $consent_payment_value,
											"mihpayid" => $payu_mihpayid,
											"bank_ref_num" => $payu_bank_ref_num,
											"api_endpoint" => config('app.payu_refund_url'),
										);
					$array_refund_status = $array_refund_rep['status']??0;
												 
					$update_refund_resp  = General::update_refund_amount_status($payu_order_id, json_encode($refund_request, true), $get_refund_response, $array_refund_status);									
		
					return redirect(route('admin.consent.us-b2b-creditreport-no-hit-status', [$set_business_name, $consent_payment_value]));
				} else {
					//Code for INSERTING DATA in database.
					$api = new ConsentAPIResponse();
					$api->consent_request_id = Session::get('consent_request_us_id');
					$api->response           = General::encrypt(json_encode($response_api));
					$api->request_data 		 = General::encrypt(json_encode($postData));
					$api->ip_address 		 = request()->ip();
					$api->created_at         = Carbon::now();
					$api->status = 1;
					$api->save();

					$consentPayment->invoice_id = CreditReportHelper::getConsentPaymentInvoiceId();
					$consentPayment->save();

					CreditReportHelper::insertIntoMembershipPaymentsTable($consentRequestInsert->id);

					//Call request function for generating PDF & sending email sending email Invoice as attachment.
					// $equifax_id = !empty($response_api->EfxTransmit->efxInternalTranId) ? $response_api->EfxTransmit->efxInternalTranId : 'NA';
					$this->usb2b_invoice_sendmail($consentPayment->id);

					return redirect(route('admin.consent.us-b2b-creditreport-success-status', [$set_business_name, $consent_payment_value]));
				}
			} else {
				//Adding Consentpayment, data, in TABLE: consent_payment.	
				General::add_to_debug_log(Auth::id(), "Creating ConsentPayment info.");
				$consentPayment = ConsentPayment::create([
				
					'order_id' => $response['ORDERID'],
					'customer_type' => "USBUSINESS",
					'person_name' => $user_name,
					'contact_phone' => $mobile_number,
					'consent_id' => 0,
					'payment_value' => $response['net_amount_debit'],
					'status' => 5,
					'created_at' => Carbon::now(),
					'added_by' => Auth::id(),
					'raw_response' => json_encode($response),
					'pg_type' =>'payu'  
				]);			
				General::add_to_debug_log(Auth::id(), "Creating ConsentPayment info Fails.");
				
				General::add_to_debug_log(Auth::id(), "Equifax response fails");
				return redirect(url('admin/creditreports'))->with(['message' => "Payment from PayU response failed!", 'alert-type' => 'error']);
			}
			//Code ends at here.
		}
		//End of condition to handle request & Response of Equfax USB2B Report.
		
		if(setting('admin.payment_gateway_type')=='paytm'){
			$transaction = PaytmWallet::with('receive');
			try{
				$response = $transaction->response();
			}catch(\Exception $e){
				//add to db log
				return redirect()->route('all-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		}else{
			try{
				$response = General::verifyPayuPayment($request->all());
				if(!$response){
					return redirect()->route('all-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
				}
			}catch(\Exception $e){
				return redirect()->route('all-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		}

		//dd($response);
		$consentPayment = ConsentPayment::where('order_id','=',$response['ORDERID'])
			->where('status',1)
			->where('added_by',Auth::id())
			->first();
		if(empty($consentPayment)){
			General::add_to_debug_log(Auth::id(), "Invalid consent payment.");
			return redirect()->route('all-records')->with(['message' => "Invalid consent payment", 'alert-type' => 'error']);
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


		 if($paymentStatus=='success'){
			$consentPayment->transaction_id = $response['TXNID'] ?? $response['mihpayid'] ?? '';
			$consentPayment->payment_mode = $response['PAYMENTMODE'] ?? $response['mode'] ?? '';
          	$consentPayment->status = 4;
          	$alertType = 'success';
            $message='Payment successful.';

            $consentRequest = ConsentRequest::where('id', $consentPayment->consent_id)->first();

            // $invoice_no=MembershipPayment::where('created_at','>=',date('Y-m-d 00:00:00'))->where(function($q) {
            // $q->where('status',4)
            //     ->orWhere('postpaid', 0);
            // })->count();
            $invoice_no = MembershipPayment::where('created_at','>=',date('Y-m-d 00:00:00'))->where('status',4)->count();
            $invoice_no=$invoice_no+1;

            if(Auth::user()->user_pricing_plan != NULL){
                $consent_payment_value = $consentRequest->report == 2 ? Auth::user()->user_pricing_plan->pricing_plan->consent_comprehensive_report_price : Auth::user()->user_pricing_plan->pricing_plan->consent_recordent_report_price;
                $consent_payment_value_gst_in_perc = Auth::user()->user_pricing_plan->pricing_plan->consent_recordent_report_gst;
            }else{
                $consent_payment_value = setting('admin.consent_payment_value') ? (int)setting('admin.consent_payment_value') : 100 ;
                $consent_payment_value_gst_in_perc = setting('admin.consent_payment_value_gst_in_perc') ? (int)setting('admin.consent_payment_value_gst_in_perc') : 0 ;
            }

            if($consent_payment_value_gst_in_perc>0){
                $temp = ($consent_payment_value * $consent_payment_value_gst_in_perc)/100;
                $temp = round($temp);
                $temp = (int)$temp;
                $consent_payment_value_final = $consent_payment_value + $temp;
            }

            $valuesForMembershipPayment = [
	            'customer_id' => Auth::user()->id,
                'invoice_id' => date('dmY').sprintf('%07d',$invoice_no),
                'pricing_plan_id' =>0,
                'customer_type' => "INDIVIDUAL",
                'payment_value' => $consent_payment_value,
                'gst_perc' => $consent_payment_value_gst_in_perc,
                'gst_value' => $temp,
                'total_collection_value' => $consent_payment_value_final,
                'particular' => "Individual ".($consentRequest->report==1 ? "Recordent Report" : "Recordent Comprehensive Report"),
                'consent_id' => $consentRequest->id,
	            'invoice_type_id' => $consentRequest->report==2 ? 3 : 2,
                'postpaid' => 0,
                'status' => 4,
            ];
            $membershipPayment = MembershipPayment::create($valuesForMembershipPayment);

        }else if($paymentStatus=='failed'){
         	$consentPayment->status = 5;
         	$alertType = 'error';
          	$message='Payment failed.';
        }else{
          	$consentPayment->status = 2;
          	$alertType = 'info';
          	$message='Payment is in progress.';
        }

        $consentPayment->raw_response = json_encode($response);
      	$consentPayment->updated_at = Carbon::now();
        $consentPayment->update();

        if(isset($membershipPayment)){
          	app('App\Http\Controllers\HomeController')->postpaid_invoice_sendmail($membershipPayment->id);
        }
        
        General::add_to_debug_log(Auth::id(), $consentPayment->status);

        return redirect()->route('all-records')->with(['message' => $message, 'alert-type' => $alertType]);

	}


	public function consentPaymentCallbackORIGINAL(Request $request){
		if(setting('admin.payment_gateway_type')=='paytm'){
				$transaction = PaytmWallet::with('receive');
				try{
					$response = $transaction->response();
				}catch(\Exception $e){
					//add to db log
					return redirect()->route('all-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
				}
			}else{
				try{
					$response = General::verifyPayuPayment($request->all());
					if(!$response){
						return redirect()->route('all-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
					}
				}catch(\Exception $e){
					return redirect()->route('all-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
				}
			}

		//dd($response);
		$consentPayment = ConsentPayment::where('order_id','=',$response['ORDERID'])
			->where('status',1)
			->where('added_by',Auth::id())
			->first();
		if(empty($consentPayment)){
			General::add_to_debug_log(Auth::id(), "Invalid consent payment.");
			return redirect()->route('all-records')->with(['message' => "Invalid consent payment", 'alert-type' => 'error']);
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


		 if($paymentStatus=='success'){
			$consentPayment->transaction_id = $response['TXNID'] ?? $response['mihpayid'] ?? '';
			$consentPayment->payment_mode = $response['PAYMENTMODE'] ?? $response['mode'] ?? '';
          	$consentPayment->status = 4;
          	$alertType = 'success';
            $message='Payment successful.';

            $consentRequest = ConsentRequest::where('id',$consentPayment->consent_id)->first();

            // $invoice_no=MembershipPayment::where('created_at','>=',date('Y-m-d 00:00:00'))->where(function($q) {
            // $q->where('status',4)
            //     ->orWhere('postpaid', 0);
            // })->count();
            $invoice_no = MembershipPayment::where('created_at','>=',date('Y-m-d 00:00:00'))->where('status',4)->count();
            $invoice_no=$invoice_no+1;

            if(Auth::user()->user_pricing_plan != NULL){
                $consent_payment_value = $consentRequest->report == 2 ? HomeHelper::getConsentComprehensiveReportPrice() : HomeHelper::getConsentRecordentReportPrice();
				$consent_payment_value_gst_in_perc = HomeHelper::getConsentRecordentReportGst();
            }else{
                $consent_payment_value = setting('admin.consent_payment_value') ? (int)setting('admin.consent_payment_value') : 100 ;
                $consent_payment_value_gst_in_perc = setting('admin.consent_payment_value_gst_in_perc') ? (int)setting('admin.consent_payment_value_gst_in_perc') : 0 ;
            }

            if($consent_payment_value_gst_in_perc>0){
                $temp = ($consent_payment_value * $consent_payment_value_gst_in_perc)/100;
                $temp = round($temp);
                $temp = (int)$temp;
                $consent_payment_value_final = $consent_payment_value + $temp;
            }

            $valuesForMembershipPayment = [
	            'customer_id' => Auth::user()->id,
                'invoice_id' => date('dmY').sprintf('%07d',$invoice_no),
                'pricing_plan_id' =>0,
                'customer_type' => "INDIVIDUAL",
                'payment_value' => $consent_payment_value,
                'gst_perc' => $consent_payment_value_gst_in_perc,
                'gst_value' => $temp,
                'total_collection_value' => $consent_payment_value_final,
                'particular' => "Individual ".($consentRequest->report==1 ? "Recordent Report" : "Recordent Comprehensive Report"),
                'consent_id' => $consentRequest->id,
	            'invoice_type_id' => $consentRequest->report==2 ? 3 : 2,
                'postpaid' => 0,
                'status' => 4,
            ];
            $membershipPayment = MembershipPayment::create($valuesForMembershipPayment);

        }else if($paymentStatus=='failed'){
         	$consentPayment->status = 5;
         	$alertType = 'error';
          	$message='Payment failed.';
        }else{
          	$consentPayment->status = 2;
          	$alertType = 'info';
          	$message='Payment is in progress.';
        }

        $consentPayment->raw_response = json_encode($response);
      	$consentPayment->updated_at = Carbon::now();
        $consentPayment->update();

        General::add_to_debug_log(Auth::id(), $consentPayment->status);

        return redirect()->route('all-records')->with(['message' => $message, 'alert-type' => $alertType]);

	}

	public function checkConsentStatus($consentId){
		$consentList = consentRequest::with('payment')
			->where('added_by',Auth::id())
			->where('id',$consentId)
			//->where('customer_type','=','INDIVIDUAL')
			//->where('is_expired_by_admin',2)
			->first();
		if(empty($consentList))	{
			return Response::json(['error' => true,'message'=>'No record found'], 500);
		}
		$lastConsent =  consentRequest::where('added_by',Auth::id())
			// ->where('customer_type','=','INDIVIDUAL')
			->orderBy('searched_at','DESC')
			->first();

		$withHtml = View('admin.all-students.consent-payment-list.check-status-row', compact('consentList','lastConsent'))->render();
		return Response::json(['success' => true,'message'=>'Succefully checked','newStatus'=>$withHtml], 200);
	}

	public static function getName($mobile){
        $student = Students::where('contact_phone', General::encrypt($mobile))->first();
		return $student!=null ? $student->person_name : '-';
    }
}
