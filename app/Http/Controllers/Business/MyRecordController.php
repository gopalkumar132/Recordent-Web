<?php

namespace App\Http\Controllers\Business;

use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use PhpOffice\PhpSpreadsheet\Exception;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail as SendMail;
use App\Http\Controllers\Controller;
use App\Sector;
use App\State;
use App\City;
use App\Businesses;
use App\BusinessDueFees;
use App\BusinessPaidFees;
use App\MembershipPayment;
use App\ConsentAPIResponse;
use App\UserType;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Log;
use Auth;
use Storage;
use General;
use App\DuesSmsLog;
use App\Services\SmsService;
use Illuminate\Support\Collection;
use App\ConsentRequest;
use PDF;
use App\ConsentPayment;
use App\Dispute;
use App\DuePayment;
use App\TempDuePayment;
use PaytmWallet;
use Illuminate\Support\Str;
use App\User;
use HomeHelper;
use Session;
use App\MemberCustomerMapping;



class MyRecordController extends Controller
{
    public function importExcelView()
    {
        return view('admin.import-excel');
    }

    public function importExcel(Request $request)
    {
    	//dd(request()->file('file'));
		$import = new StudentsImport;
		try {
       		Excel::import($import, request()->file('file'));
	  	} catch (\Exception $e) {
			return redirect()->back()->with(['message' => 'Something wrong with your excel sheet. Please check it before upload. ', 'alert-type' => 'error']);
	  	}

		$totalRows = $import->getRowCount();
        //dd($totalRows);
        //return redirect()->back()->with('success', 'All good!');
		//dd($request->session()->all());
        return redirect()->back()->with(['message' => $totalRows['Updated'].' Record imported and because of format error '.$totalRows['Skipped'].' record skipped', 'alert-type' => 'success']);
    }

    public function export()
    {
       return Excel::download(new StudentsExport, 'MyRecords-'.Carbon::now().'.xlsx');
    }



	public function MyBusinessRecords(Request $request,$businessId,$dueId)
    {
		$getCustomId = BusinessDueFees::select('external_business_id')->where('business_id','=',$businessId)->where('id','=',$dueId);
	    $getCustomId = $getCustomId->first();
		$checkCustomId = $getCustomId->external_business_id;

		$User = Auth::user();
		if(!is_null($request->getQueryString()) && ($User->email_verified_at == NULL  || $User->email_sent_at == NULL))
		{
			//return redirect('admin/auth/verify');
		}
    	$authId = Auth::id();
    	$currentDate =Carbon::now();
    	$sectors = Sector::whereNull('deleted_at')->where('status',1)->get();
    	$states = State::where('country_id',101)->get();
	    $stateIds = [];
	    foreach ($states as $state){
	       $stateIds[] =$state->id;
	    }
	    $cities = City::whereIn('state_id',$stateIds)->get();
	    $paid_records = BusinessPaidFees::where('business_id','=',$businessId)->where('external_business_id', $checkCustomId)->where('due_id',0)->where('added_by',Auth::id())->whereNotNull('payment_options_drop_down')->get();
		$settled_records = 0;
		foreach ($paid_records as $key => $value) {
		    	$settled_records = $value->payment_options_drop_down;
		}
    	$records = BusinessDueFees::with(['profile'])->whereHas('profile',function($q) use($request){
    			if(!empty($request->input('company_name'))){

					$q->where('businesses.company_name' , 'LIKE' , General::encrypt(strtolower($request->input('company_name'))));
				}
				if(!empty($request->input('unique_identification_number'))){

					$q->where('businesses.unique_identification_number' , 'LIKE' , General::encrypt(strtoupper($request->input('unique_identification_number'))));
				}
				if(!empty($request->input('concerned_person_name'))){

					$q->where('businesses.concerned_person_name' , 'LIKE' , General::encrypt(strtolower($request->input('concerned_person_name'))));
				}
				if(!empty($request->input('concerned_person_phone'))){

					$q->where('businesses.concerned_person_phone' , 'LIKE' , General::encrypt($request->input('concerned_person_phone')));
				}
				if(!empty($request->input('sector_id'))){

					$q->where('businesses.sector_id' , $request->input('sector_id'));
				}
				if(!empty($request->input('state_id'))){

					$q->where('businesses.state_id' , $request->input('state_id'));
					if(!empty($request->input('city_id'))){
						$q->where('businesses.city_id' , $request->input('city_id'));
					}
				}
    		})
    		->where('added_by',$authId)
			->where('external_business_id', $checkCustomId)
			->where('business_id',$businessId)
    		->whereNull('deleted_at');

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
					'paid AS totalPaid' => function ($query) use($authId){
	            		$query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at')->where('added_by',$authId);
	        		}
	    		]);

			//$records = $records->where('totalPaid','<',600);
			//$records = $records->groupBy('due_date');
			$records = $records->orderBy('id','DESC')->paginate(50);
			//$records = $records->get();
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

		$editDueAmount = 'readonly';
		if (General::checkMemberEligibleToEditDueAmount()) {
				$editDueAmount = "";
			}
		return view('admin.business.my-records.business-customer-dues-level',compact('records','sectors','states','cities','editDueAmount','settled_records'));
    }




    public function MyRecords(Request $request)
    {
		$User = Auth::user();
		if(!is_null($request->getQueryString()) && ($User->email_verified_at == NULL  || $User->email_sent_at == NULL))
		{
			//return redirect('admin/auth/verify');
		}
    	$authId = Auth::id();
    	$currentDate =Carbon::now();
    	$sectors = Sector::whereNull('deleted_at')->where('status',1)->get();
    	$states = State::where('country_id',101)->get();
	    $stateIds = [];
	    foreach ($states as $state){
	       $stateIds[] =$state->id;
	    }
	    $cities = City::whereIn('state_id',$stateIds)->get();
    	$records = BusinessDueFees::with(['profile'])->whereHas('profile',function($q) use($request){
    			if(!empty($request->input('company_name'))){

					$q->where('businesses.company_name' , 'LIKE' , General::encrypt(strtolower($request->input('company_name'))));
				}
				if(!empty($request->input('unique_identification_number'))){

					$q->where('businesses.unique_identification_number' , 'LIKE' , General::encrypt(strtoupper($request->input('unique_identification_number'))));
				}
				if(!empty($request->input('concerned_person_name'))){

					$q->where('businesses.concerned_person_name' , 'LIKE' , General::encrypt(strtolower($request->input('concerned_person_name'))));
				}
				if(!empty($request->input('concerned_person_phone'))){

					$q->where('businesses.concerned_person_phone' , 'LIKE' , General::encrypt($request->input('concerned_person_phone')));
				}
				if(!empty($request->input('sector_id'))){

					$q->where('businesses.sector_id' , $request->input('sector_id'));
				}
				if(!empty($request->input('state_id'))){

					$q->where('businesses.state_id' , $request->input('state_id'));
					if(!empty($request->input('city_id'))){
						$q->where('businesses.city_id' , $request->input('city_id'));
					}
				}
    		})
    		->where('added_by',$authId)
    		->whereNull('deleted_at');

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
					'paid AS totalPaid' => function ($query) use($authId){
	            		$query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at')->where('added_by',$authId);
	        		}
	    		]);

			//$records = $records->where('totalPaid','<',600);
			//$records = $records->groupBy('due_date');
			$records = $records->orderBy('id','DESC')->paginate(50);
			//$records = $records->get();
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

		$editDueAmount = 'readonly';
		if (General::checkMemberEligibleToEditDueAmount()) {
				$editDueAmount = "";
			}
		return view('admin.business.my-records.index',compact('records','sectors','states','cities','editDueAmount'));
    }



	public function businessData($businessId)
	{
		$authId = Auth::id();
		$businessDueData = BusinessDueFees::select('business_due_fees.id As dueId','business_due_fees.business_id','due_amount','due_date','business_due_fees.created_at As ReportedAt','paid_amount','paid_date','due_note','business_due_fees.proof_of_due','business_due_fees.grace_period','business_due_fees.collection_date')
										->leftJoin('business_paid_fees','business_due_fees.business_id','=','business_paid_fees.business_id')

										->where('business_due_fees.business_id','=',$businessId)
			->whereNull('business_due_fees.deleted_at')
			->where('business_due_fees.added_by',$authId)
			->groupBy('due_date')->get();

		$business = Businesses::where('id','=',$businessId)->first();
		return view('admin.business.my-records.business-data',compact('businessDueData','business','businessId'));
	}

	public function storeDueAmount($businessId, Request $request)
	{
		$authId = Auth::id();
		$authUserType = Auth::user()->user_type;
		$grace_period = $request->grace_period_hidden;

		if($authUserType==2){
	    	$validator = Validator::make($request->all(), [
			   //'contact_phone' => 'required|digits:10,10',
	    		'business_id'=>'required',
			   'due_date' => 'required',
			   'due_amount' => 'required|numeric|gte:500|lte:1000000000',
			   'customer_no'=>'required',
			   'invoice_no'=>'required',
			  // 'proof_of_due'=>'mimes:jpeg,jpg,bmp,xls,xlsx,png,pdf,doc,docx,txt',
			   'due_note'=>'nullable|string|max:300',
			  'collection_date'=>'required',
       	 	]);
		}else{
			$validator = Validator::make($request->all(), [
			   //'contact_phone' => 'required|digits:10,10',
				'business_id'=>'required',
			   'due_date' => 'required',
			   'due_amount' => 'required|numeric|gte:500|lte:1000000000',
			  // 'proof_of_due'=>'mimes:jpeg,jpg,bmp,xls,xlsx,png,pdf,doc,docx,txt',
			   'due_note'=>'nullable|string|max:300',
		   'collection_date'=>'required',
      		 ]);
		}
        if($validator->fails()) {
           return redirect()->back()
                       ->withErrors($validator)
                       ->withInput();
       }
       $businessId = $request->business_id;
		if($businessId == ''){

			return redirect()->back()->withError('Error: Business id is not given');
		}


		$business = Businesses::where('id','=',$businessId)->first();

		if(empty($business)){

			return redirect()->back()->withError('Error: Business record not exists');
		}

		/*$proofOfDue ='';
		if(!empty($request->file('proof_of_due'))){
				$proofOfDue = Storage::disk('public')->put('business/proof_of_due', $request->file('proof_of_due'));
		}*/

		$proofOfDue ='';
			if(!empty($request->file('proof_of_due'))){

				$ImgListNames=$request->file('proof_of_due');
				$encrptImgname="";
						foreach($ImgListNames as $Imgname)
						{
							$proofOfDue = Storage::disk('public')->put('business/proof_of_due', $Imgname);
							$encrptImgname.=$proofOfDue.",";
						}

					$update_proof=str_replace("business/proof_of_due/","",$encrptImgname);
					$final_updateProof=trim($update_proof,",");
					$proofOfDue="business/proof_of_due/".$final_updateProof;

			}

		$due_date_formated = Carbon::createFromFormat('d/m/Y', $request->due_date)->toDateTimeString();
	   	$collection_date_formated = Carbon::createFromFormat('d/m/Y', $request->collection_date)->toDateTimeString();
		//$customBusinessId = isset($request->input('external_business_id')) ? trim($request->input('external_business_id')) : NULL;
		// $external_business_id = $request->input('external_business_id');
		$external_business_id = $request->input('custom_id');
		$customBusinessId = NULL;
		if(isset($external_business_id)) {
			$customBusinessId = $external_business_id;
		}
		$valuesForStudentDueFees = ['business_id' => $businessId,
									 'due_date' => $due_date_formated,
								     'due_amount' => $request->input('due_amount'),
								     'due_note' => $request->input('due_note'),
								     'created_at' => Carbon::now(),
								     'added_by'=>$authId,
									 'proof_of_due'=>$proofOfDue,
									 'collection_date'=>$collection_date_formated,
									 'grace_period'=>$grace_period,
									 'external_business_id' => $customBusinessId
									];

		$busiesssFee = BusinessDueFees::create($valuesForStudentDueFees);
		// echo $busiesssFee;exit();
		if($busiesssFee->id == ''){

			return redirect()->back()->withError('Error: Due Amount not stored');
		}

		// return redirect()->back()->withMessage('Success: Outstanding Amount stored');
		return redirect()->back()->withMessage('Success: Record Added');

	}

	public function dueDataByDueID(Request $request)
	{
		$dueId = $request->input('due_id');
		if(empty($dueId)){
			return Response::json(['error' => true,'message'=>'Due id can not be null'], 300);
		}
		if ($request->with_html == 'yes') {
		$data = BusinessDueFees::with('addedBy', 'profile')->where('id', '=', $dueId)->first();
		$withHtml = View('admin/business/partials/due-data-popup', compact('data'))->render();
		return Response::json(['success' => true, 'data' => $withHtml], 200);
		} else {
		$dueData = BusinessDueFees::where('id','=',$dueId)->first();
		$business_id=$dueData['business_id'];
			$dueDataStudent = Businesses::where('id', '=', $business_id)->first();
			$paidAmount=BusinessPaidFees::where('business_id', '=', $business_id)->where('due_id', '=', $dueId)->select('paid_amount')
            ->groupBy('business_id')->sum('paid_amount');;
			if(empty($paidAmount))
			{
				$paidAmount=0;
			}
		//return $dueData;
		if(!empty($dueData)){
			$dueDate = date('Y-m-d', strtotime($dueData->due_date));
			return Response::json(['success' => true,'data'=>$dueData,'due_date'=>$dueDate,'personal_data'=>$dueDataStudent,'paid_amount'=>$paidAmount], 200);
		}else{
			return Response::json(['success' => false,'message'=>''], 200);
		}
	}
  }



	public function editDueAmount($businessId, Request $request)
	{
		$dueId = $request->input('outstanding');
		$businessId = $request->business_id;
		if($dueId){
			$validator = Validator::make($request->all(), [//'contact_phone' => 'required|digits:10,10',
							'business_id'=>'required',
						   'due_date' => 'required|date',
						   'due_amount' => 'required|numeric|gte:500|lte:1000000000',
						  // 'proof_of_due'=>'mimes:jpeg,jpg,bmp,xls,xlsx,png,pdf,doc,docx,txt',
						   'due_note'=>'nullable|string|max:300',
						]);

			if($validator->fails()) {
			   return redirect()->back()->withErrors($validator)->withInput();
			}

			$businessData = Businesses::where('id','=',$businessId)->first();
			if(empty($businessData)){
			    return redirect()->back()->withError('Error: Record Not Found');
			}

			$dueData = BusinessDueFees::where('id','=',$dueId)->whereNull('deleted_at')->first();
			if(empty($dueData)){
				return redirect()->back()->withError('Error: Record Not Found');
			}

			$Duereport_Date = date('d/m/Y', strtotime($dueData['created_at']));
			$today_date =date('d/m/Y');
			$to = \Carbon\Carbon::createFromFormat('d/m/Y', $Duereport_Date);
			$from = \Carbon\Carbon::createFromFormat('d/m/Y',$today_date);
			$diff_in_days = $to->diffInDays($from);

			$dbdueAmount=$dueData['due_amount'];
			$userdueAmount=$request->due_amount;

			if(setting('admin.number_of_days') < $diff_in_days)
			{
				if($dbdueAmount <=$userdueAmount)
				{}else{
					return redirect()->back()->withError('Error: Amount due should be equual or  more then amount due');
				}
			}

			$proofOfDue ='';
			if(!empty($request->file('proof_of_due'))){

				$ImgListNames=$request->file('proof_of_due');
				$encrptImgname="";
						foreach($ImgListNames as $Imgname)
						{
							$proofOfDue = Storage::disk('public')->put('business/proof_of_due', $Imgname);
							$encrptImgname.=$proofOfDue.",";
						}

					$update_proof=str_replace("business/proof_of_due/","",$encrptImgname);
					$final_updateProof=trim($update_proof,",");
					$proofOfDue=$dueData->proof_of_due.",".$final_updateProof;

					/*if(!empty($dueData->proof_of_due)){
						Storage::disk('public')->delete($dueData->proof_of_due);
					}*/
			}

			$invoice_no=$request->input('invoice_no');
			if(empty($invoice_no))
			{
				$invoice_no = NULL;
			}
		if(!empty($proofOfDue)){
				$dueData->update(['due_date'=>$request->input('due_date'),

								  'due_note'=>$request->input('due_note'),
								  'updated_at'=>Carbon::now(),
								  'proof_of_due'=>$proofOfDue,
								  'due_amount'=> $request->input('due_amount'),
								//   'external_business_id'=>$external_business_id,
								  'invoice_no'=>$invoice_no,
							]);


			}else{
				$dueData->update(['due_date'=>$request->input('due_date'),

								  'due_note'=>$request->input('due_note'),
								  'updated_at'=>Carbon::now(),
								  'due_amount'=> $request->input('due_amount'),
								  'invoice_no'=>$invoice_no,
								//   'external_business_id'=>$external_business_id,
							]);

			}

			$businessData ->update([
				'company_name' => $request->input('company_name'),
				'concerned_person_phone' => $request->input('concerned_person_phone'),
				'email'=> $request->input('email'),
				'updated_at' => Carbon::now()
			]);

			return redirect()->back()->withMessage('Success: Record Updated');
		}
		else{
			return redirect()->back()->withError('Error: Record Not Found');
		}

	}

	public function storePayAmount($businessId, Request $request)
	{
        $authId = Auth::id();
		$validator = Validator::make($request->all(), [
	    	'business_id'=>'required',
		   'due_amount' => 'numeric',
           'outstanding' => 'required',
           'payment_date' => 'required|date_multi_format:d/m/Y',
           'payment_amount' => 'required|numeric|lte:due_amount|min:1',]
        ,['payment_date.date_multi_format'=>'The payment date is not a valid date.']
       );
        if($validator->fails()) {
           return redirect()->back()
                       ->withErrors($validator)
                       ->withInput();
       }
        $businessId = $request->business_id;
        try{
	        $payment_date = Carbon::createFromFormat('d/m/Y', $request->payment_date)->toDateString();
	    }catch(\Exception $e){
    		return redirect()->back()->with(['message' => "Invalid payment date", 'alert-type' => 'error']);
    	}

		$business = Businesses::where('id','=',$businessId)->first();

		if(empty($business)){
			return redirect()->back()->with(['message' => "Record not found.", 'alert-type' => 'error']);
		}
		$duesRecord = BusinessDueFees::where('id',$request->outstanding)->where('added_by',Auth::id())->whereNull('deleted_at')->first();
		if(empty($duesRecord)){
			return redirect()->back()->with(['message' => "Due data not found.", 'alert-type' => 'error']);
		}

		//$skipCollection = false;
		$skipCollectionRequest = $request->skipcollectionpayment==0 ? false:true;
		$skipCollection = $skipCollectionRequest;
		if($request->skip_payment){
			if(General::checkMemberEligibleToSkipCollectionPayment()){
				$skipCollection = true;
			}
		}
        //$duesRecord->collection_date = Carbon::parse($duesRecord->collection_date)->addDays(45);
		if(!$skipCollection){
			if(!empty($duesRecord->collection_date)){
				if($payment_date<=$duesRecord->collection_date){
					$skipCollection = $skipCollectionRequest;
				}

			}elseif($payment_date<=Carbon::createFromFormat('Y-m-d H:i:s',$duesRecord->due_date)->toDateString()){
				$skipCollection = $skipCollectionRequest;
			}
		}
		$customBusinessId = isset($duesRecord->external_business_id) ? $duesRecord->external_business_id : NULL;
		if($skipCollection){
			$valuesForStudentPayFees = [
				'business_id' => $businessId,
				'due_id' => $request->outstanding,
				'paid_date' =>$payment_date,
				'paid_amount' => $request->payment_amount,
			    'paid_note' => $request->payment_note,
			    'created_at' => Carbon::now(),
			    'added_by'=>$authId,
			    'external_business_id' => $customBusinessId
			    ];

			$businessFee = BusinessPaidFees::create($valuesForStudentPayFees);
		if(array_key_exists('send_updatepayment_sms',$request->all())) {
			 if(!empty($businessFee)){
				   $mobile_number= $business->concerned_person_phone;
				   $name= $business->concerned_person_name;
				   $business_name = Auth::user()->business_name;
				   $email = $business->email;
				   $amount = $request->payment_amount;
				   // $message = $name. ' we thank you for the payment of INR ' .$amount .' made on '.Carbon::now()->format('d-M-Y') . ' to '.$business_name .'.'. ' To view your updated record, click here ' .  route('your.reported.bussinesdues') ;
				   $message='We thank you for the payment of INR ' .$amount .' made on '.Carbon::now()->format('d-M-Y') . ' to '.$business_name .'. To view your updated record, click here ' . route('your.reported.dues');
					$smsService = new SmsService();

					$smsResponse = $smsService->sendSms($mobile_number,$message);

				if($smsResponse['fail_to_send']){
					return response()->json(['error'=>true,'message'=>'server not responding'], 500);
				 }
				 /*if(isset($email)){
					 try{
						SendMail::send('front.emails.send-otp-to-email', [
							'otpMessage' => $message
						], function($message) use ($email) {
							$message->to($email)
							->subject("Your Payment to Recordent");
						});

				}catch(JWTException $exception){
					$this->serverstatuscode = "0";
					$this->serverstatusdes = $exception->getMessage();
				}
			}*/
			}
		}
			if($businessFee->id == ''){
				return redirect()->back()->with(['message' => "can not update payment. Please try again.", 'alert-type' => 'error']);
			}

			General::storeAdminNotificationForPayment('Business',$businessFee->id);

			if($duesRecord->balance_due !=0){
				General::Update_Balance_Due($duesRecord->balance_due,$request->payment_amount,"Business",$request->outstanding,$businessId);
				}
			
			return redirect()->back()->with(['message' => 'Payment updated successfully.', 'alert-type' => 'success']);
		}

		/*$valuesForStudentPayFees = ['business_id' => $businessId,
									 'due_id' => $request->input('outstanding'),
									 'paid_date' =>$request->input('pay_date'),
									 'paid_amount' => $request->input('pay_amount'),
								     'paid_note' => $request->input('due_note'),
								     'created_at' => Carbon::now(),
								     'added_by'=>$authId,
								    ];

		$businessFee = BusinessPaidFees::create($valuesForStudentPayFees);
		if($businessFee->id == ''){
			//dd(3);
			return redirect()->back()->withError('Error: Paid Amount not stored');
		}*/



		$consent_payment_value_gst_in_perc = setting('admin.consent_payment_value_gst_in_perc') ? (int)setting('admin.consent_payment_value_gst_in_perc') : 0 ;
		$collectionFee1 = 0;
		$collectionFee = 0;
		$totalGSTValue = 0;
		$totalCollectionValue = 0;

		$collectionFeePerc = HomeHelper::getMyRecordsCollectionFeePercent();

		//1% collection fee
        // $temp = ($tempDuePayment->payment_value * 1)/100;

        //collection fee percentage based on pricing plan
        $temp = ($request->payment_amount * $collectionFeePerc)/100;

    	$collectionFee1 = $collectionFee1 + $temp;
    	// $collectionFee = bcdiv($collectionFee,1,2);
    	if($collectionFee1>50){
    		$collectionFee = bcdiv($collectionFee1,1,2);
    	}else{
    		$collectionFee = 50;
    	}

		//GST
        if($consent_payment_value_gst_in_perc>0){
        	$temp = ($collectionFee * $consent_payment_value_gst_in_perc)/100;
        	$totalGSTValue = $totalGSTValue + $temp;
        	$totalGSTValue = bcdiv($totalGSTValue,1,2);
        }

    	$totalCollectionValue = $collectionFee + $totalGSTValue;
    	if($totalCollectionValue<1){
    		$totalCollectionValue = 1;
    	}

		$invoice_no = MembershipPayment::where('created_at','>=',date('Y-m-d 00:00:00'))->where('status',4)->count();
        $invoice_no=$invoice_no+1;
		$valuesForMembershipPayment = [
            'customer_id' => Auth::user()->id,
            'invoice_id' => date('dmY').sprintf('%07d',$invoice_no),
            'pricing_plan_id' =>0,
            'customer_type' => "BUSINESS",
            'payment_value' => $collectionFee,
            'gst_perc' => $consent_payment_value_gst_in_perc,
            'gst_value' => $totalGSTValue,
            'total_collection_value' => $totalCollectionValue,
            'particular' => "Collection Fee",
            'due_id' => $request->outstanding,
        	'postpaid' => Auth::user()->collection_fee_business==1 ? 1 : 0,
            'status' => 4,
            'invoice_type_id' => 6
        ];
		$customBusinessId = isset($duesRecord->external_business_id) ? $duesRecord->external_business_id : NULL;
		if(Auth::user()->collection_fee_business==1){
			$valuesForStudentPayFees = [
				'business_id' => $businessId,
				'due_id' => $request->outstanding,
				'paid_date' =>$payment_date,
				'paid_amount' => $request->payment_amount,
			    'paid_note' => $request->payment_note,
			    'created_at' => Carbon::now(),
			    'added_by'=>$authId,
			    'external_business_id' => $customBusinessId
			    ];

			$businessFee = BusinessPaidFees::create($valuesForStudentPayFees);
			if($businessFee->id == ''){
				return redirect()->back()->with(['message' => "can not update payment. Please try again.", 'alert-type' => 'error']);
			}

	        $membershipPayment = MembershipPayment::create($valuesForMembershipPayment);

            // $response=app('App\Http\Controllers\HomeController')->postpaid_invoice_sendmail($membershipPayment->id);

			General::storeAdminNotificationForPayment('Business',$businessFee->id);
			if(array_key_exists('send_updatepayment_sms',$request->all())) {
			 if(!empty($businessFee)){
				   $mobile_number= $business->concerned_person_phone;
				   $name= $business->concerned_person_name;
				   $business_name = Auth::user()->business_name;
				   $email = $business->email;
				   $amount = $request->payment_amount;
				   // $message = $name. ' we thank you for the payment of INR ' .$amount .' made on '.Carbon::now()->format('d-M-Y') . ' to '.$business_name .'.'. ' To view your updated record, click here ' .  route('your.reported.bussinesdues') ;
				   $message='We thank you for the payment of INR ' .$amount .' made on '.Carbon::now()->format('d-M-Y') . ' to '.$business_name .'. To view your updated record, click here ' . route('your.reported.dues');
					$smsService = new SmsService();

					$smsResponse = $smsService->sendSms($mobile_number,$message);

				if($smsResponse['fail_to_send']){
					return response()->json(['error'=>true,'message'=>'server not responding'], 500);
				 }
				 /*if(isset($email)){
					 try{
						SendMail::send('front.emails.send-otp-to-email', [
							'otpMessage' => $message
						], function($message) use ($email) {
							$message->to($email)
							->subject("Your Payment to Recordent");
						});

				}catch(JWTException $exception){
					$this->serverstatuscode = "0";
					$this->serverstatusdes = $exception->getMessage();
				}
			}*/
			}
		}

		if($duesRecord->balance_due !=0){
			General::Update_Balance_Due($duesRecord->balance_due,$request->payment_amount,"Business",$request->outstanding,$businessId);
			}
		
			return redirect()->back()->with(['message' => 'Payment updated successfully', 'alert-type' => 'success']);
		}
		DB::beginTransaction();
		try{

			$send_sms_email = 0;
			if(array_key_exists('send_updatepayment_sms',$request->all())) { $send_sms_email = 1; }
			$tempDuePayment = TempDuePayment::create([
				'order_id'=>Str::random(40),
				'customer_type'=>'BUSINESS',
				'customer_id'=>$duesRecord->business_id,
				'due_id'=>$duesRecord->id,
				'payment_value'=>$request->payment_amount,
				'created_at'=>Carbon::now(),
				'added_by'=>Auth::id(),
				'payment_note'=>$request->payment_note,
				'payment_date'=>$payment_date,
				'redirect_query_string'=>$request->redirect_query_string,
				'send_sms_email' => $send_sms_email,
				'external_business_id' => $customBusinessId
			]);

			$duePayment = DuePayment::create([
				'order_id'=>$tempDuePayment->order_id,
				'customer_type'=>$tempDuePayment->customer_type,
				'customer_id'=>$tempDuePayment->customer_id,
				'due_id'=>$tempDuePayment->due_id,
				'payment_value'=>$tempDuePayment->payment_value,

				'status'=>1,//initiated
				'created_at'=>Carbon::now(),
				'added_by'=>Auth::id(),
				'payment_done_by'=>'ADMIN_MEMBER',

				'collection_fee_perc'=>1,
				'gst_perc'=>$consent_payment_value_gst_in_perc,

				'gst_value'=>$totalGSTValue,
				'collection_fee'=>$collectionFee,
				'total_collection_value'=>$totalCollectionValue
			]);
			$membershipPayment1 =  MembershipPayment::where('due_id', $tempDuePayment->due_id)->first();
			if(!empty($membershipPayment1)){
        		$membershipPayment1->delete();
			}
			$membershipPayment = MembershipPayment::create($valuesForMembershipPayment);
			DB::commit();

		}catch(\Exception $e){
			// DB::rollback();
			return redirect()->back()->with(['message' => "can not create payment process. Please try again.", 'alert-type' => 'error']);
		}

		$userDataToPaytm = User::findOrFail(Auth::user()->id);
		$userDataToPaytm_name = preg_replace('/\s+/', '_', $userDataToPaytm->name);

		$duePayment->pg_type = setting('admin.payment_gateway_type');
	    $duePayment->update();

		if(setting('admin.payment_gateway_type')=='paytm'){
			$payment = PaytmWallet::with('receive');
			$payment->prepare([
				'order'=> $duePayment->order_id,
				'user'=> $userDataToPaytm_name,
				'mobile_number'=> $userDataToPaytm->mobile_number,
				'email'=> $userDataToPaytm->email,
				'amount'=> $totalCollectionValue,
				'callback_url'=> route('business.business-due-payment-callback')
			]);

			return $payment->view('admin.payment-submit')->receive();
		} else {

			$postData = [
				'amount' => $totalCollectionValue,
				'txnid' => $duePayment->order_id,
				'firstname' => preg_replace('/\s+/', '', $userDataToPaytm->name),
				'email' => $userDataToPaytm->email,
				'phone' => $userDataToPaytm->mobile_number,
				'surl' => route('business.business-due-payment-callback'),
			];

			$payuForm = General::generatePayuForm($postData);

			return view('admin.payment-submit',compact('payuForm'));
		}
	}

	public function duePaymentCallback(Request $request){
		if(setting('admin.payment_gateway_type')=='paytm'){

			$transaction = PaytmWallet::with('receive');
				try{
					$response = $transaction->response();
				}catch(\Exception $e){
					//add to db log
					return redirect()->route('business.my-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
				}
		}else{
			try{
				$response = General::verifyPayuPayment($request->all());
				if(!$response){
					return redirect()->route('business.my-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
				}
			}catch(\Exception $e){
				return redirect()->route('business.my-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		}
		//dd($response);
		$duePayment = DuePayment::where('order_id','=',$response['ORDERID'])
			->where('added_by',Auth::id())
			->first();
		if(empty($duePayment)){
			return redirect()->route('business.my-records')->with(['message' => "Invalid due payment", 'alert-type' => 'error']);
		}

		$tempDuePayment = TempDuePayment::where('order_id','=',$response['ORDERID'])
			->where('added_by',Auth::id())
			->first();
		if(empty($tempDuePayment)){
			return redirect()->route('business.my-records')->with(['message' => "Invalid due payment", 'alert-type' => 'error']);
		}
		$redirectQueryString = $tempDuePayment->redirect_query_string;

		$message ='';
		$alertType='info';

		if(setting('admin.payment_gateway_type')=='paytm'){
	      	if($transaction->isSuccessful()){
	      			$paymentStatus = 'success';
      		}else if($transaction->isFailed()) {
      			$paymentStatus = 'failed';
      		}else {
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
        }else if($paymentStatus=='failed'){
         	$duePayment->status = 5;
         	$alertType = 'error';
          	$message='Payment failed.';
        }else {
          	$duePayment->status = 2;
          	$alertType = 'info';
          	$message='Payment is in progress.';
        }

        $duePayment->raw_response = json_encode($response);
      	$duePayment->updated_at = Carbon::now();
        DB::beginTransaction();
      	try{
			$customBusinessId = isset($tempDuePayment->external_business_id) ? $tempDuePayment->external_business_id : NULL;
        	if($duePayment->status==4){// successful payment

        		$get_business_due_data = BusinessDueFees::where('id', $duePayment->due_id)->first();
	        	$valuesForStudentPayFees = [
	        		'business_id' => $tempDuePayment->customer_id,
					'due_id' => $tempDuePayment->due_id,
					'paid_date' =>$tempDuePayment->payment_date,
					'paid_amount' => $tempDuePayment->payment_value,
			    	'paid_note' => $tempDuePayment->payment_note,
			    	'created_at' => Carbon::now(),
			    	'added_by'=>Auth::id(),
			    	'external_business_id' => $customBusinessId
			    ];

				$businessFee = BusinessPaidFees::create($valuesForStudentPayFees);
				$duePayment->paid_id = $businessFee->id;

				General::storeAdminNotificationForPayment('Business',$businessFee->id);

				$membershipPayment =  MembershipPayment::where('due_id', $tempDuePayment->due_id)->first();

				if(!empty($membershipPayment)){
            		$response=app('App\Http\Controllers\HomeController')->postpaid_invoice_sendmail($membershipPayment->id);
				}
				if($tempDuePayment->send_sms_email) {
	     	   $mobile_number= $duePayment->businessProfile->concerned_person_phone;
		         $name= $duePayment->businessProfile->concerned_person_name;
		         $business_name = Auth::user()->business_name;
		         $email = $duePayment->businessProfile->email;
		         $amount = $tempDuePayment->payment_value;

		         $response='We thank you for the payment of INR ' .$amount .' made on '.Carbon::now()->format('d-M-Y') . ' to '.$business_name .'. To view your updated record, click here ' . route('your.reported.dues');
		         $smsService = new SmsService();

	     	     $smsResponse = $smsService->sendSms($mobile_number,$response);

         		if($smsResponse['fail_to_send']){
             	return response()->json(['error'=>true,'message'=>'server not responding'], 500);
              }
	              /*if(isset($email)){
		              try{
		                 SendMail::send('front.emails.send-otp-to-email', [
		                     'otpMessage' => $response
		                 ], function($response) use ($email) {
		                     $response->to($email)
		                     ->subject("Your Payment to Recordent");
		                 });

		             }catch(JWTException $exception){
		                 $this->serverstatuscode = "0";
		                 $this->serverstatusdes = $exception->getMessage();
		             }
	            }*/

				}

			}

			$duePayment->update();
			if($duePayment->status==4 || $duePayment->status==5){
				$tempDuePayment->delete();
			}

			DB::commit();
    	} catch(\Exception $e){
    		Log::debug("message = ".$e->getMessage());
    		// DB::rollback();
    		return redirect('admin/business/my-records'.$redirectQueryString)->with(['message' => 'can not store due payment.', 'alert-type' => 'error']);
    	}


        return redirect('admin/business/my-records'.$redirectQueryString)->with(['message' => $message, 'alert-type' => $alertType]);
	}

	public function paymentHistory(Request $request)
	{
		$dueId = $request->input('due_id');
		$profileId = $request->input('profileId');
		$custom_id = $request->input('custom_id');
		$settled_records = $request->input('settled_records');
		$businessId = $request->input('businessId');
		if(isset($dueId)){
		 if(empty($dueId)){
			return Response::json(['error' => true,'message'=>'Due id can not be null'], 300);
		 }
		}
		$paymentHistory =BusinessPaidFees::select('id','paid_date','paid_amount','paid_note','deleted_at','payment_options_drop_down')->where('added_by',Auth::id())->whereNull('deleted_at')->orderBy('id','DESC');
		if(isset($profileId)){
           $paymentHistory = $paymentHistory->where('business_id', $profileId)->where('external_business_id',$custom_id)->get();
		}else {

			$paymentHistory1 = $paymentHistory->where('due_id', $dueId)->get();
			$exisitng_due_ids =[];
			$paid_history = BusinessDueFees::where('added_by',Auth::id())->where('external_business_id', $custom_id)->whereNull('deleted_at')->where('id', $dueId);
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
				$paymentHistory2 = BusinessPaidFees::select('id', 'paid_date', 'paid_amount', 'paid_note', 'deleted_at','payment_options_drop_down')->where('added_by',Auth::id())->whereNull('deleted_at')->orderBy('id', 'DESC');
				  $paymentHistory2 = $paymentHistory2->where('business_id', $businessId)->where('due_id',0)->where('external_business_id',$custom_id)->whereNotIn('id',$exisitng_due_ids)->get();
		       }
		}
        if(isset($paymentHistory2)){
        $paymentHistory=$paymentHistory2->merge($paymentHistory1);
        } else {
        	$paymentHistory=$paymentHistory1;
        }
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
			$withHtml = View('admin/business/my-records/payment-history', compact('paymentHistory'))->render();
			return Response::json(['success' => true,'noData'=>false,'paymentHistoryData'=>$withHtml], 200);
		}else{
			return Response::json(['success' => true,'message'=>'','noData'=>true], 200);
		}

		//return Response::json(['success' => true,'message'=>'','paymentHistory'=>$paymentHistory], 200);

	}

	public function paymentHistoryDelete(Request $request)
	{

		$validator = Validator::make($request->all(), [
           'payment_id' => 'required',
           'delete_note' => 'required',
           'agree_terms' => 'required',
   		]);
   		if($validator->fails()) {
           return redirect()->back()->withErrors($validator);
        }
		$authId = Auth::id();
		//return Response::json(['error' => true,'message'=>'Record not found. Please try again'], 300);
		$paymentId = $request->input('payment_id');

		$paymentHistory = BusinessPaidFees::where('id',$paymentId)->whereNull('deleted_at')->where('added_by',$authId)->first();

		if(!empty($paymentHistory)){

			$paymentHistory->deleted_at = Carbon::now();
			$paymentHistory->delete_note = $request->input('delete_note');
			$paymentHistory->update();
			return redirect()->back()->withMessage('successfully deleted payment history record');
		}else{
			return redirect()->back()->withErrors(['can not find payment history record']);
		}


	}

	public function deleteDue(Request $request)
	{
			$authId = Auth::id();
			$validator = Validator::make($request->all(), [
	           'due_id' => 'required',
	          // 'delete_note' => 'required',
	           //'agree_terms' => 'required',
       		]);
       		if($validator->fails()) {
	           return redirect()->back()->withErrors($validator);
	       }
			$dueId = $request->input('due_id');
			//$deleteNote = $request->input('delete_note');
			//$agreeTerms = $request->input('agree_terms');

			/*if(empty($dueId)){
				return redirect()->back()->withErrors(['Due id can not be null']);
			}

			if(empty($deleteNote)){
				return redirect()->back()->withErrors(['Delete-note can not be empty']);
			}*/

			$businessDue = BusinessDueFees::where('id',$dueId)->whereNull('deleted_at')->where('added_by',$authId)->first();
			if(empty($businessDue)){
				return redirect()->back()->withErrors(['can not find due record']);
			}
			if(!empty($businessDue->proof_of_due)){
				Storage::disk('public')->delete($businessDue->proof_of_due);
			}

			$businessDue->deleted_at = Carbon::now();
			//$businessDue->delete_note = $deleteNote;
			$businessDue->update();

			//mark as deleted for paid entries of this due
			BusinessPaidFees::whereNull('deleted_at')->where('added_by',$authId)->where('due_id',$dueId)->update([
				'deleted_at'=>Carbon::now(),
				//'delete_note'=>$deleteNote
			]);
			return redirect()->back()->withMessage('successfully deleted');


	}


	public function deleteProofOfDue(Request $request)
	{
			$authId = Auth::id();
			$validator = Validator::make($request->all(), [
	           'due_id' => 'required',
			   'file_name'=>'required'
	    	]);
       		if($validator->fails()) {
	           return Response::json(['error' => true,'message'=>'Due id can not be null'], 300);
	        }

			$dueId = $request->input('due_id');
			$file_name = $request->input('file_name');
			$divId = $request->input('div_id');
			$businessDue = BusinessDueFees::where('id',$dueId)->whereNull('deleted_at')->where('added_by',$authId)->first();
			if(empty($businessDue)){
				return redirect()->back()->withErrors(['can not find due record']);
			}

			$imglist=str_replace("business/proof_of_due/","",$businessDue->proof_of_due);
			$total_file_list=explode(",",$imglist);
			if(count($total_file_list)>1)
			{
				$update_proof=str_replace($file_name,null,$imglist);
				$final_updateProof=trim($update_proof,",");
				$businessDue->proof_of_due = "business/proof_of_due/".$final_updateProof;
				//Storage::disk('public')->delete("business/proof_of_due/".$file_name);
			}else{

				/*if(!empty($businessDue->proof_of_due)){
					Storage::disk('public')->delete($businessDue->proof_of_due);
				}*/
				$businessDue->proof_of_due = '';

			}


			$businessDue->update();
			return Response::json(['success' => true,'message'=>'','div_id'=>$divId], 200);


	}


	public function editBusiness($id){
		$states = State::where('country_id',101)->get();
	    $stateIds = [];
	    foreach ($states as $state){
	        $stateIds[] =$state->id;
	    }
	    $cities = City::whereIn('state_id',$stateIds)->get();
	    $sectors = Sector::where('status',1)->whereNull('deleted_at')->orderBy('id','ASC')->get();

		$data = Businesses::where('id',$id)->where('added_by',Auth::id())->whereNull('deleted_at')->first();
		if (!$data) {
			$get_customer_from_mapping = MemberCustomerMapping::where('member_id', Auth::id())
						->where('customer_id', $id)
						->where('customer_type', 2)
						->first();

			if ($get_customer_from_mapping) {
				$data = Businesses::where('id',$id)->whereNull('deleted_at')->first();
			}
		}
		$userTypes = UserType::where('status',1)->orderBy('name','ASC')->get();
		return view('admin/business/my-records/edit-record',compact('data','states','cities','sectors','userTypes'));
	}

	public function updateBusiness(Request $request){

		$id = $request->input('id');
		$customerKeys = array("company_name"=>"Business Name", "email"=>"Email", "concerned_person_phone"=>"Mobile", "concerned_person_name"=>"Name", "unique_identification_number"=>"GSTIN/Business Pan");
		$request->all_values = (array)$request->all_values;
		$requestData = json_decode($request->all_values[0]);
		$requestData=(array)$requestData;
		unset($requestData['added_by'],$requestData['created_at'],$requestData['updated_at'],$requestData['unique_identification_type'],$requestData['sector_id'],$requestData['concerned_person_designation'],$requestData['concerned_person_alternate_phone'],$requestData['state_id'],$requestData['city_id'],$requestData['pincode'],$requestData['address'],$requestData['uniqe_url_business'],$requestData['custom_business_id'],$requestData['user_type'],$requestData['type_of_business'],$requestData['type_of_sector'],$requestData['proof_of_due']);
		$request_data=$request->all();
		$result = array_diff($requestData, $request_data);
		 if(!empty($result)){
			 $keys=array_keys($result);
			  foreach ($keys as $key => $value) {
			 	 $names[] = $customerKeys[$value];
			}
		    General::storeAdminNotificationForBusinessProfile($names,$requestData);
        }
		$name_max_character= General::maxlength('name');
		$validator = Validator::make($request->all(), [
				'id'=>'required',
			    'company_name' => 'required|string|max:'.$name_max_character,
			    'user_type' => 'required|numeric',
	            'unique_identification_number' => 'required|string|max:191',
			   'concerned_person_name' => 'required|regex:/^[\pL\s]+$/u|max:'.$name_max_character,
		   		'concerned_person_designation' => 'required|regex:/^[\pL\s\-]+$/u|max:191',
			   'concerned_person_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:13',
			   'concerned_person_alternate_phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:13',
			   'state' => 'required|numeric',
			   'city' => 'required|numeric',
			   'pin_code'=> 'nullable|numeric',
			   'address'=> 'nullable|string',

	       ],
	       [
	       	'unique_identification_number.required'=> 'The '.General::getLabelName('unique_identification_number').' field is required.',
	       	'unique_identification_number.string'=> 'The '.General::getLabelName('unique_identification_number').' field must be a valid string.',
	       	'unique_identification_number.max'=>'The '.General::getLabelName('unique_identification_number').' may not be greater than :max characters.',
	       	'unique_identification_number.unique'=> 'The '.General::getLabelName('unique_identification_number').' is already exists.',
	       	'concerned_person_name.regex'=>'The :attribute may only contain letters and space.',
       		'concerned_person_designation.regex'=>'The :attribute may only contain letters, dash and space.',
	       ]

   		);

        if($validator->fails()) {
           return redirect()->back()->withErrors($validator)->withInput();
       }

    	$company_name = $request->company_name;

		$sector_id = $request->sector_id;
		$unique_identification_number = $request->unique_identification_number;
		$concerned_person_name = $request->concerned_person_name;
		$concerned_person_designation = $request->concerned_person_designation;
		$concerned_person_phone = $request->concerned_person_phone;
		$concerned_person_alternate_phone = $request->concerned_person_alternate_phone;
		$state_id = $request->state;
		$city_id = $request->city;
		$pincode = $request->pin_code;
		$email = $request->email;
		$address = $request->address;
		$user_type = $request->user_type;
		$type_of_business = NULL;
        if ($request->has('type_of_business')) {
            $type_of_business = General::encrypt($request->type_of_business);
        }
        $type_of_sector = NULL;
        if ($request->has('type_of_sector')) {
            $type_of_sector = General::encrypt($request->type_of_sector);
        }

		$data = Businesses::where('id',$id)->where('added_by',Auth::id())->whereNull('deleted_at')->first();

		if (!$data) {
			$get_customer_from_mapping = MemberCustomerMapping::where('member_id', Auth::id())
						->where('customer_id', $id)
						->where('customer_type', 2)
						->first();

			if ($get_customer_from_mapping) {
				$data = Businesses::where('id', $id)->whereNull('deleted_at')->first();
			}
		}

		if(empty($data)){
			return redirect()->back();
		}

		$alreadyExists = Businesses::where('id','!=',$id)->where('unique_identification_number','=',General::encrypt($unique_identification_number))->whereNull('deleted_at')->first();
		if(!empty($alreadyExists)){
			return redirect()->back()->withErrors(['Record with this '.General::getLabelName('unique_identification_number').' is already exists']);
		}

		$data->company_name = $company_name;
		$data->unique_identification_number = $unique_identification_number;
		$data->sector_id = $sector_id;
		$data->concerned_person_name = $concerned_person_name;
		$data->concerned_person_designation = $concerned_person_designation;
		$data->concerned_person_phone = $concerned_person_phone;
		$data->concerned_person_alternate_phone = $concerned_person_alternate_phone;
		$data->state_id = $state_id;
		$data->city_id = $city_id;
		$data->pincode = $pincode;
		$data->email = $email;
		$data->address = $address;
		$data->user_type = $user_type;
		$data->type_of_business = $type_of_business;
		$data->type_of_sector = $type_of_sector;
		$data->updated_at = Carbon::now();
		$data->update();

		return redirect('admin/business/my-records'.$request->input('redirectQueryString'))->with('message','Successfully updated');

	}


	  public function recordsForSms(Request $request){

	    $User = Auth::user();
		if(!is_null($request->getQueryString()) && ($User->email_verified_at == NULL  || $User->email_sent_at == NULL))
		{
			//return redirect('admin/auth/verify');
		}
    	$authId = Auth::id();
    	$currentDate =Carbon::now();

    	$records = BusinessDueFees::with(['profile'])->whereHas('profile',function($q) use($request){
    		$q->where('businesses.concerned_person_phone' , '!=' , '');
		})
    		->where('added_by',$authId)
    		->whereNull('deleted_at');

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
					'paid AS totalPaid' => function ($query) use($authId){
	            		$query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at')->where('added_by',$authId);
	        		}
	    		]);

			//$records = $records->groupBy('due_date');
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
			$authUser = Auth::user();
			$smsTemplates = \Config::get('sms_templates');
			return view('admin.business.my-records.send-sms',compact('records','smsTemplates'));
	  }


	  public function recordsSendSms(Request $request){

		$AuthId = Auth::id();

		$validator = Validator::make($request->all(), [
		   'ids' => 'required',
		   'template_id' => 'required',
		   'within_date'=>'nullable|date',
		   ],
		   [
		   	'ids.required'=>'Select records to send sms',
		   	'template_id.required'=>'Template is required',
		   ]
		);

	    if($validator->fails()) {
           return redirect()->back()
                       ->withErrors($validator)
                       ->withInput();
       }

       $ids = explode(",",$request->ids);

	   $checkMySmsLimit = General::checkSmsDailyLimit($AuthId);

		$limitMessage = '';
		if(!$checkMySmsLimit['daily_available']){
			$limitMessage = 'can not send sms, your daily sms Limit is over';
		}elseif(!$checkMySmsLimit['weekly_available']){
			$limitMessage = 'can not send sms, your weekly sms Limit is over';
		}elseif(!$checkMySmsLimit['monthly_available']){
			$limitMessage = 'can not send sms, your monthly sms Limit is over';
		}
		if(!empty($limitMessage)){
			return redirect()->back()->with(['message' => $limitMessage, 'alert-type' => 'error']);
		}
	   $businessList = BusinessDueFees::with(['profile'])->whereHas('profile',function($q){
	   		$q->whereNotNull('concerned_person_phone')->where('concerned_person_phone','!=','');
	   })->whereIn('id',$ids)->where('added_by',$AuthId)->get();

       if(!$businessList->count()){
       	return redirect()->back()->with(['message' => "can not send sms ", 'alrert-type' => 'error']);
       }

        $template_id = $request->template_id;
        $message = \Config::get('sms_templates.'.$template_id.'.text');
		if(empty($message)){
			return redirect()->back()->with(['message' => 'can not find template', 'alert-type' => 'error']);
		}
		$authUser = Auth::user();

		$withinDate = $request->within_date;

       $sent = true;
       $smsService = new SmsService();
       foreach ($businessList as $data) {
       		$checkMySmsLimit = General::checkSmsDailyLimit($AuthId);

			$limitMessage = '';
			if(!$checkMySmsLimit['daily_available']){
				$limitMessage = 'only some sms are sent, your daily sms Limit is over';
			}elseif(!$checkMySmsLimit['weekly_available']){
				$limitMessage = 'only some sms are sent, your weekly sms Limit is over';
			}elseif(!$checkMySmsLimit['monthly_available']){
				$limitMessage = 'only some sms are sent, your monthly sms Limit is over';
			}
			if(!empty($limitMessage)){
				return redirect()->back()->with(['message' => $limitMessage, 'alert-type' => 'error']);
				break;
			}
			$message = General::replaceTextInSmsTemplate($template_id,'BUSINESS',$authUser,$withinDate,'',$data);
			$message =strip_tags($message);
	        /*$smsResponse = $smsService->sendSms($data->profile->concerned_person_phone,$message);
	   		if($smsResponse['fail_to_send']){
	   			$sent = false;
	   		}*/
	        $insert = [
	        		'contact_phone'=>$data->profile->concerned_person_phone,
	        		'customer_id'=>$data->profile->id,
	        		'due_id'=>$data->id,
	        		'customer_type'=>'Business',
	        		'created_at'=>Carbon::now(),
	        		'added_by'=>$AuthId,
	        		'message'=>$message,
	        	];
	        $insert['status'] = 0;
	        /*if($smsResponse['sent']==1){
	        	$insert['status'] = 1;
	        }else{
	        	$insert['status'] = 2;
	        }*/
	        DuesSmsLog::create($insert);
       }

       if(!$sent){
       		return redirect()->back()->withInput()->with(['message' => "can not send sms to some phones. Server unavailable.", 'alert-type' => 'error']);
       }
       return redirect()->back()->with(['message' => "SMS sent for admin approval.", 'alert-type' => 'success']);


	}


	public function recordsSentSms(Request $request){
		$AuthId = Auth::id();
		$records = DuesSmsLog::with('business')->where('customer_type','=','Business')->where('added_by',$AuthId)->orderBy('created_at','DESC')->paginate(50);

		return view('admin.business.my-records.sent-sms',compact('records'));

	}

	public function report(Request $request){
		// dd($request->cp_id);
		$dataList = Collection::make();
		$reportForYear = setting('admin.generate_report_from_consent_for_last_year') ? (int)setting('admin.generate_report_from_consent_for_last_year') : 0 ;
		$consent_payment_successful_valid_for_in_year = setting('admin.consent_payment_successful_valid_for_in_year') ? (int)setting('admin.consent_payment_successful_valid_for_in_year') : 7;

		$currentTime = Carbon::now();
        $beforeDateTime = Carbon::now()->subYear($consent_payment_successful_valid_for_in_year);

		if(!empty($request->c_id)){
			$dataList = ConsentRequest::with('detail')->where('id',$request->c_id)
					->where('added_by',Auth::id())
					->where('status',3)
					->where('customer_type','=','BUSINESS')
					->get();
			$consentPayment = new ConsentPayment;
			$consentPayment->consent_id = $request->c_id;
		} else {
			if(!empty($request->cp_id)){
				$consentPayment = ConsentPayment::where('id',$request->cp_id)
					->where('status',4)
					->where('customer_type','=','BUSINESS')
					->where('added_by', Auth::id())
					->where('updated_at','>=',$beforeDateTime)
					->first();

				if(empty($consentPayment)){
					return redirect()->back()->with(['message' => "something went wrong.", 'alert-type' => 'error']);
				}

				$dataList = ConsentRequest::with('detail')->where('id',$consentPayment->consent_id)
					->where('added_by',Auth::id())
					->where('status',3)
					->where('customer_type','=','BUSINESS')
					->get();

			} else {

				if($reportForYear>0){
					$previousYears = Carbon::now()->subYear($reportForYear);
					$dataList = ConsentRequest::with('detail')
						->where('added_by',Auth::id())
						->where('status',3)
						->where('created_at','>=',$previousYears)
						->where('customer_type','=','BUSINESS')
						->get();
				}
			}
		}

		$records = Collection::make();

		if($dataList->count()){
			$businessIds = [];
			foreach($dataList as $data) {
				$business = Businesses::with('dues')->whereHas('dues',function($q){
						$q->whereNull('deleted_at');
					})->where('concerned_person_phone', General::encrypt($data->concerned_person_phone));

				if(!empty($data->unique_identification_number)){
					$business = $business->where('unique_identification_number', General::encrypt(strtoupper($data->unique_identification_number)));
				}

				$business = $business->whereNull('deleted_at')->get();
				if($business->count()){
					foreach ($business as $s) {
						if(!in_array($s->id,$businessIds)){
							$businessIds[] = $s->id;
						}
					}
				}
			}

			// get business detail from ids
			// $records = Businesses::whereIn('id',$businessIds)->get();
			$records = Businesses::with(['dues', 'dues.paid', 'dues.dispute'])->whereIn('id', $businessIds)->get();

			//dd($records);
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

				//1 to 89 days
				$overDueStatusCount = BusinessDueFees::whereRaw("datediff(CURDATE(),due_date) < 90")->where('business_id',$record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus0To89Days = $overDueStatusCount;

				//90 to 179 days
				$overDueStatusCount = BusinessDueFees::whereRaw(" datediff(CURDATE(),due_date) <=179 AND datediff(CURDATE(),due_date) >=90 ")->where('business_id',$record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus90To179Days = $overDueStatusCount;

				//180plus
				$overDueStatusCount = BusinessDueFees::whereRaw("datediff(CURDATE(),due_date) >=180 ")->where('business_id',$record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus180PlusDays = $overDueStatusCount;


				/* account detail */
				$accountDetails = BusinessDueFees::with(['addedBy','profile'])->whereHas('addedBy')->whereHas('profile')->where('business_id',$record->id)->whereNull('deleted_at')->get();

				$record->accountDetails = $accountDetails;
			}
		}

		$dateTime = Carbon::now()->format('d-m-Y H:i');
		$cp_id = $request->cp_id;
		$c_id = $request->c_id;
		$identityType = [
			// 'AADHAR' => 'M',
			1 => 'T',
			3 => 'P',
			2 => 'V',
			4 => 'D',
			5 => 'R',
			// 'RationCard' => 'R',
		];

		$consentRequest = $dataList->toArray();
        $states = State::where('id', $consentRequest[0]['state'])->first();
		$user['name'] = isset($consentRequest[0]) ? $consentRequest[0]['business_name'] : '';
		$user['unique_identification_number']= isset($consentRequest[0]) ? $consentRequest[0]['unique_identification_number'] : '';
		$user['email'] = isset($consentRequest[0]) ? $consentRequest[0]['directors_email'] : '';
		$user['address'] = isset($consentRequest[0]) ? $consentRequest[0]['address'] : '';
		$user['city'] = isset($consentRequest[0]) ? $consentRequest[0]['city'] : '';
		$user['state'] = isset($states->short_code) ? $states->short_code : '';
		$user['pincode'] = isset($consentRequest[0]) && $consentRequest[0] != NULL ? General::decrypt($consentRequest[0]['pincode']) : '';
		$user['authorized_name'] = isset($consentRequest[0]) ? $consentRequest[0]['authorized_signatory_name'] : '';
		$user['authorized_dob'] = isset($consentRequest[0]) ? $consentRequest[0]['authorized_signatory_dob'] : '';
		$user['company_id'] = isset($consentRequest[0]) ? $consentRequest[0]['company_id'] : '';
		$user['number'] = isset($consentRequest[0]) ? $consentRequest[0]['concerned_person_phone'] : '';
		$user['link_contact_phone'] = isset($consentRequest[0]) ? $consentRequest[0]['link_contact_phone'] : '';
		$user['id_value'] = isset($consentRequest[0]) && $consentRequest[0] != NULL ? General::decrypt($consentRequest[0]['idvalue']) : '';
		$user['id_type'] = isset($consentRequest[0]) && $consentRequest[0] != NULL ? $consentRequest[0]['idtype'] : '';
		if($user['id_type']==1){
			$user['pan'] = $user['id_value'];
			$user['voter_id'] = '';
			$user['passport'] = '';
			$user['driving_license'] = '';
			$user['ration_card'] = '';
			$user['aadhar'] = '';
		} else if($user['id_type']==2){
			$user['pan'] = '';
			$user['voter_id'] = $user['id_value'];
			$user['passport'] = '';
			$user['driving_license'] = '';
			$user['ration_card'] = '';
			$user['aadhar'] = '';
		} else if($user['id_type']==3){
			$user['pan'] = '';
			$user['voter_id'] = '';
			$user['passport'] = $user['id_value'];
			$user['driving_license'] = '';
			$user['ration_card'] = '';
			$user['aadhar'] = '';
		} else if($user['id_type']==4){
			$user['pan'] = '';
			$user['voter_id'] = '';
			$user['passport'] = '';
			$user['driving_license'] = $user['id_value'];
			$user['ration_card'] = '';
			$user['aadhar'] = '';
		} else if($user['id_type']==5){
			$user['pan'] = '';
			$user['voter_id'] = '';
			$user['passport'] = '';
			$user['driving_license'] = '';
			$user['ration_card'] = $user['id_value'];
			$user['aadhar'] = '';
		} else if($user['id_type']==6){
			$user['pan'] = '';
			$user['voter_id'] = '';
			$user['passport'] = '';
			$user['driving_license'] = '';
			$user['ration_card'] = '';
			$user['aadhar'] = $user['id_value'];
		}

		$user['recordent'] = [
			'total_members' => count($records),
			'total_dues_unpaid' => 0,
			'total_dues_paid' => 0,
			'total_dues' => 0,
			'summary_overDueStatus0To89Days' => 0,
			'summary_overDueStatus90To179Days' => 0,
			'summary_overDueStatus180PlusDays' => 0
		];

	    if($records) {
			foreach ($records->toArray() as $r_key => $r_value) {

	  			$user['recordent']['summary_overDueStatus0To89Days'] += $r_value['summary_overDueStatus0To89Days'];
	  			$user['recordent']['summary_overDueStatus90To179Days'] += $r_value['summary_overDueStatus90To179Days'];
	  			$user['recordent']['summary_overDueStatus180PlusDays'] += $r_value['summary_overDueStatus180PlusDays'];
	  			$user['recordent']['total_dues'] += count($r_value['dues']);

	  			foreach ($r_value['dues'] as $r_due_key => $r_due_value) {
	  				$user['recordent']['total_dues_unpaid'] += $r_due_value['due_amount'];
	  				foreach ($r_due_value['paid'] as $r_due_paid_key => $r_due_paid_value) {
	  					$user['recordent']['total_dues_paid'] += $r_due_paid_value['paid_amount'];
	  				}
	  			}
	  		}
	  	}

		$user['recordent']['total_dues_unpaid'] = $user['recordent']['total_dues_unpaid'] - $user['recordent']['total_dues_paid'];

		if($consentRequest[0]['report'] != 3){

			$businessRecord = Businesses::where('unique_identification_number', General::encrypt($user['unique_identification_number']))->first();
			if(isset($businessRecord)){
				$businessRecord = $businessRecord->toArray();

				$business_name_rec=$businessRecord['company_name'];
				$user['business_name_rec'] = $business_name_rec;
				$business_type_rec=$businessRecord['user_type'];
				$user_type = UserType::where('id',$business_type_rec)->first();
				$user['business_type_rec'] = isset($user_type->name) ? $user_type->name : 0;
				$business_sector_rec=$businessRecord['sector_id'];
				$sector_type = Sector::where('id',$business_sector_rec)->first();
				$user['business_sector_rec'] = isset($sector_type->name) ? $sector_type->name : 0;

				$business_concerned_name_rec=$businessRecord['concerned_person_name'];
				$user['business_concerned_name_rec'] = $business_concerned_name_rec;
				$business_email_rec=$businessRecord['email'];
				$user['business_email_rec'] = $business_email_rec;
				$business_designation_rec=$businessRecord['concerned_person_designation'];
				$user['business_designation_rec'] = $business_designation_rec;
           }

        }

		if (isset($consentRequest[0]) && $consentRequest[0]['report'] == 3) {
			$api = ConsentAPIResponse::where('consent_request_id', $consentPayment->consent_id)->first();

			if (empty($api)) {
				$result = $this->getDataFromConsentApi($user);
			} else {
				$result = json_decode(General::decrypt($api->response), true);
			}

            $membership_payments = MembershipPayment::where('consent_id', $consentPayment->consent_id)->first();

			if (isset($result['Error']) || isset($result['CCRResponse']) && isset($result['CCRResponse']['CommercialBureauResponse']['Error']) || $result['CCRResponse']['CommercialBureauResponse']['hit_as_borrower'] == '00') {
				if (!empty($result)) {
					$customer_type = "Business";

					if (empty($api)) {

						$api = new ConsentAPIResponse();
						$api->consent_request_id = $consentPayment->consent_id;
						$api->response           = General::encrypt(json_encode($result));
						$api->request_data 		 = General::encrypt(json_encode($this->getRequestParams($user)));
						$api->ip_address 		 = request()->ip();
						$api->created_at         = Carbon::now();
						$api->status = 3;
						$api->save();
					}

					$consent_payment_record = ConsentPayment::where('consent_id', $consentPayment->consent_id)
											->where('added_by', Auth::id())
											->where('customer_type', '=', 'BUSINESS')
											->where('status', 4)
											->first();

					if (!empty($consent_payment_record)){

						if (Auth::user()->reports_business == 1) {
							$consent_payment_record->status = 5;
							$consent_payment_record->save();
						} else {
							$payu_raw_response = json_decode($consent_payment_record->raw_response);

							if (!empty($payu_raw_response)) {

								$get_refund_response = General::payu_refund_api($consent_payment_record->payment_value, $payu_raw_response->mihpayid, $payu_raw_response->bank_ref_num);
								$array_refund_rep = json_decode($get_refund_response, true);

								$refund_request = array(
														"key" => config('app.payu_merchant_key'),
														"salt" => config('app.payu_merchant_salt'),
														"command" => "cancel_refund_transaction",
														"refund_amt" => $consentPayment->payment_value,
														"mihpayid" => $payu_raw_response->mihpayid,
														"bank_ref_num" => $payu_raw_response->bank_ref_num,
														"api_endpoint" => config('app.payu_refund_url'),
													);
								$array_refund_status = $array_refund_rep['status']??0;

								$update_refund_resp  = General::update_refund_amount_status($payu_raw_response->ORDERID, json_encode($refund_request, true), $get_refund_response, $array_refund_status);
							}
						}
					}

					return view('admin.business.equifax-b2b.india-b2bnohitresponse.index', compact('membership_payments','user','customer_type'));
				}
		    }

			$business_details =$result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialPersonalInfo'];
			$business_details = (object)$business_details;
			// dd($business_details);
			$pan_details =$result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['PANId'][0];
			$pan_details = (object)$pan_details;
			if(isset($result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['CIN'])){
				$cin_details =$result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['CIN'][0];
				$cin_details = (object)$cin_details;
			} else {
				$cin_details= 0;
			}
			if(isset($result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['TIN'])){
				$tin_details =$result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['TIN'][0];
				$tin_details = (object)$tin_details;
		    } else {
		    	$tin_details=0;
		    }
		    if(isset($result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['ServiceTax'])){
				$service_tax_details =$result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['ServiceTax'][0];
				$service_tax_details = (object)$business_registration_no;
			} else {
				$service_tax_details=0;
			}
			if(isset($result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['BusinessRegistration'])){
				$business_registration_no =$result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['BusinessRegistration'][0];
				$business_registration_no = (object)$business_registration_no;
			} else {
				$business_registration_no = 0;
			}
			if(isset($result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['RelationshipDetails'])){
				$RelationshipDetails = $result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['RelationshipDetails'];
			}


			$contact_details =$result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialPhoneInfo'];
			$overallcreditsummary_borrower = $result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['CommercialCIRSummary']['OverallCreditSummary']['AsBorrower'];

			if(isset($overallcreditsummary_borrower)){
				krsort($overallcreditsummary_borrower);
				$overallcreditsummary_keys= array_keys($overallcreditsummary_borrower);

				// dd($overallcreditsummary_borrower);
				$overallcreditsummary_borrower_keys = array('a','b','c');
				$overallcreditsummary_borrower = array_combine($overallcreditsummary_borrower_keys, $overallcreditsummary_borrower);
				$overallcreditsummary_borrower = (object)$overallcreditsummary_borrower;
				 // dd($overallcreditsummary_borrower);
				$overallcreditsummary_borrower->a = (object)$overallcreditsummary_borrower->a;
				$overallcreditsummary_borrower->b = (object)$overallcreditsummary_borrower->b;
				$overallcreditsummary_borrower->c = (object)$overallcreditsummary_borrower->c;
            }

			$credit_facility =$result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['CreditFacilityDetails'];


			// dd($credit_facility);
            $credit_usage = 0;
            $count_of_enter=0;
		    foreach ($credit_facility as  $value) {
		    	$count_of_enter++;
              	if($value['sanctioned_amount_notional_amountofcontract']!='0'){
                   $credit_usage += ($value['current_balance_limit_utilized_marktomarket']/$value['sanctioned_amount_notional_amountofcontract'])*100;
                } else {
                  if(isset($value['high_credit'])){
                     $credit_usage += ($value['current_balance_limit_utilized_marktomarket']/$value['high_credit'])*100;
                   } else {
                   	$credit_usage = 0;
                   }
                }
            }

            $credit_usage = $credit_usage/$count_of_enter;

		    $total_enquiries = $result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['EnquirySummary']['Total'];
		    $payment_score = $result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['CreditFacilityDetails'];
		    $totalPayments=0;
		    $totalSuccessPayment=0;
		    $statusArray = ['000', '*', 'STD', 'NEW', 'CLSD', 'OPEN', 'RES'];
		    $total_account =0;
		    $card_age_years = '';
		    $credit_age = [];

		    foreach ($payment_score as $key => $value) {
		    	$total_account++;
		        if (isset($value['History48Months'])) {
					foreach ($value['History48Months'] as $key_history => $value_history) {
						$totalPayments++;
						if (in_array($value_history['assetclassification_dayspastdue'], $statusArray)) {
							$totalSuccessPayment++;
						}
					}
				}

				if (isset($value['sanctiondate_loanactivation'])) {
                    $date1 = strtotime($value['sanctiondate_loanactivation']);
                    $date2 = strtotime(date('Y-m-d'));
                    $diff = abs($date2 - $date1);
                    $card_age_years = floor($diff / (365 * 60 * 60 * 24));
                    $credit_age[] = $card_age_years;
                }
		    }

		    $credit_age = max($credit_age);
		    // dd($credit_age);
		    $payment_score = ($totalSuccessPayment/$totalPayments)*100;
		    $report_date = $result['CCRResponse']['CommercialBureauResponse']['InquiryResponseHeader']['Date'];
		    $report_no = $result['CCRResponse']['CommercialBureauResponse']['InquiryResponseHeader']['ReportOrderNO'];

		   if(isset($result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['CommercialCIRSummary']['EquifaxRank_ScoresCommercial']['CommercialRank_ScoreDetailsLst'][0]['Rank_ScoreValue'])){
		    	$score_value = $result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['CommercialCIRSummary']['EquifaxRank_ScoresCommercial']['CommercialRank_ScoreDetailsLst'][0]['Rank_ScoreValue'];
		    }else if(isset($result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['CommercialCIRSummary']['EquifaxScoresCommercial']['CommercialScoreDetailsLst'][0]['ScoreValue'])){
		    	$score_value = $result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['CommercialCIRSummary']['EquifaxScoresCommercial']['CommercialScoreDetailsLst'][0]['ScoreValue'];
		    } else {
		    	$score_value = '0';
		    }

		    // dd($result['CCRResponse']);
		    if(isset($result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['RecentEnquiries'])){
		    	$recent_enquiries = $result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['RecentEnquiries'];
		    } else{
		    	$recent_enquiries =0;
		    }
		} else {
 			$result = [];
		}

		//start
		if (isset($result['Error']) || isset($result['CCRResponse']) && isset($result['CCRResponse']['CommercialBureauResponse']['Error'])) {
					if (!empty($result)) {
						$msg = isset($result['CCRResponse']['CommercialBureauResponse']['Error']['ErrorDesc']) ? $result['CCRResponse']['CommercialBureauResponse']['Error']['ErrorDesc'] : '';
						Session::flash('message', $msg);
						Session::flash('alert-class', 'alert-danger');
					}

					$api = ConsentAPIResponse::where('consent_request_id', $consentPayment->consent_id)->first();

					if (empty($api)) {

						$api = new ConsentAPIResponse();
						$api->consent_request_id = $consentPayment->consent_id;
						$api->response = General::encrypt(json_encode($result));
						$api->request_data = General::encrypt(json_encode($user));
						$api->ip_address = request()->ip();
						$api->status = 0;
						$api->request_type = "BUSINESS";
						$api->save();
					} else {
						$api->response = General::encrypt(json_encode($result));
						$api->request_data = General::encrypt(json_encode($user));
						$api->ip_address = request()->ip();
						$api->status = 0;
						$api->request_type = "BUSINESS";
						$api->save();
					}
					if($cp_id != null){
             			 return view('admin.business.equifax-b2b.index',compact('records','dateTime','cp_id', 'c_id','business_details','pan_details','contact_details','overallcreditsummary_borrower','overallcreditsummary_keys','credit_usage','total_enquiries','payment_score','total_account','credit_age','credit_facility','report_date','totalSuccessPayment','totalPayments','score_value','report_no','recent_enquiries','cin_details','tin_details','service_tax_details','business_registration_no','user','RelationshipDetails'));
		} else {
       		  return view('admin.business.my-records.report.index',compact('records','dateTime','cp_id', 'c_id','user'));
	    }

					// return view('admin.business.my-records.report.index',compact('records','dateTime','cp_id', 'c_id'));
		} else {
					$api = ConsentAPIResponse::where('consent_request_id', $consentPayment->consent_id)->first();
					if (empty($api)) {
						$api = new ConsentAPIResponse();
						$api->consent_request_id = $consentPayment->consent_id;
						$api->response = General::encrypt(json_encode($result));
						$api->request_data = General::encrypt(json_encode($user));
						$api->ip_address = request()->ip();
						$api->status = 1;
						$api->request_type = "BUSINESS";
						$api->save();
					} else {
						$api->response = General::encrypt(json_encode($result));
						$api->request_data = General::encrypt(json_encode($user));
						$api->ip_address = request()->ip();
						$api->status = 1;
						$api->request_type = "BUSINESS";
						$api->save();
					}
		}

		// $api = ConsentAPIResponse::where('id', 1000)->first();
		// $response = json_decode($api->response, true);

		$api = ConsentAPIResponse::where('consent_request_id', $consentPayment->consent_id)->first();
		// dd(General::decrypt($api->response));
		$response = json_decode(General::decrypt($api->response), true);
		// dd($response);

		// dd(User::where('user_type','INDIVIDUAL')->first()->toArray());

		// PAYMENT HISTORY COUNT LOGIC START
		// $totalCreditLimit = 0;
		// $totalCreditCardBalance = 0;
		// $limit = 0;
		// $totalPayments = 0;
		// $totalSuccessPayment = 0;
		// $statusArray = ['000', '*', 'STD', 'NEW', 'CLSD', 'OPEN', 'RES']; // status to be checked
		// $openClosedAccountsArr = [
		// 	'loan_accounts' => ['open' => 0, 'closed' => 0],
		// 	'credit_card_accounts' => ['open' => 0, 'closed' => 0]
		// ];

		// dd($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails']);
		// dd($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['Enquiries']);

		// if (isset($response['CCRResponse']) && isset($response['CCRResponse']['CIRReportDataLst']) && isset($response['CCRResponse']['CIRReportDataLst'][0])) {
		// 	$dataOpened = date('Y-m-d');
		// 	foreach ($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'] as $key => $value) {
		// 		if (isset($value['AccountType'])) {
		// 			if ($value['AccountType'] == 'Credit Card') {
		// 				if (isset($value['Open']) && ($value['Open'] == 'Yes' || $value['Open'] == 'yes')) {
		// 					$openClosedAccountsArr['credit_card_accounts']['open']++;

		// 					if (isset($value['CreditLimit'])) {
		// 						$totalCreditLimit += (float) $value['CreditLimit'];
		// 					} else if (isset($value['HighCredit'])) {
		// 						$totalCreditLimit += (float) $value['HighCredit'];
		// 					}

		// 					if (isset($value['Balance'])) {
		// 						$totalCreditCardBalance += (float) $value['Balance'];
		// 					}
		// 				} else {
		// 					$openClosedAccountsArr['credit_card_accounts']['closed']++;
		// 				}
		// 			} else {
		// 				if (isset($value['Open']) && ($value['Open'] == 'Yes' || $value['Open'] == 'yes')) {
		// 					$openClosedAccountsArr['loan_accounts']['open']++;
		// 				} else {
		// 					$openClosedAccountsArr['loan_accounts']['closed']++;
		// 				}
		// 			}
		// 		} else {
		// 			$response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'][$key]['AccountType'] = 'Other';
		// 		}

		// 		if (isset($value['DateOpened'])) {
		// 			$date_now = new \DateTime($dataOpened);
		// 			$date2    = new \DateTime($value['DateOpened']);
		// 			if ($date_now > $date2) {
		// 				$dataOpened = $value['DateOpened'];
		// 			}
		// 		}
		// 		if (isset($value['History48Months'])) {
		// 			foreach ($value['History48Months'] as $key_history => $value_history) {
		// 				$totalPayments++;
		// 				if (in_array($value_history['PaymentStatus'], $statusArray) && in_array($value_history['AssetClassificationStatus'], $statusArray)) {
		// 					$totalSuccessPayment++;
		// 				}
		// 			}
		// 		}
		// 	}
		// }
		// // PAYMENT HISTORY COUNT LOGIC END
		// if (isset($dataOpened)) {
		// 	$date1 = strtotime($dataOpened);
		// 	$date2 = strtotime(date('Y-m-d'));
		// 	$diff = abs($date2 - $date1);
		// } else {
		// 	$diff = '';
		// }

		// // dd($totalCreditCardBalance, $totalCreditLimit);

		// if ($totalCreditLimit > 0) {
		// 	$limit = round(number_format((($totalCreditLimit - $totalCreditCardBalance) * 100) / $totalCreditLimit, 2));
		// } else {
		// 	$limit = 100;
		// }

		// // start sorting of account
		// if (isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'])) {
		// 	$RetailAccountDetails = $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'];
		// 	uasort($RetailAccountDetails, function ($a, $b) {
		// 		$a['DateOpened'] = isset($a['DateOpened']) ? $a['DateOpened'] : date('Y-m-d');
		// 		$b['DateOpened'] = isset($b['DateOpened']) ? $b['DateOpened'] : date('Y-m-d');
		// 		return strcmp($a['DateOpened'], $b['DateOpened']);
		// 	});
		// } else {
		// 	$RetailAccountDetails = array();
		// }
		// // end sorting of account

		// // start sorting of account
		// if (isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['AddressInfo'])) {
		// 	$AddressInfo = $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['AddressInfo'];
		// 	usort($AddressInfo, function ($a, $b) {
		// 		$a['ReportedDate'] = isset($b['ReportedDate']) ? $b['ReportedDate'] : date('Y-m-d');
		// 		$b['ReportedDate'] = isset($a['ReportedDate']) ? $a['ReportedDate'] : date('Y-m-d');
		// 		return strcmp($a['ReportedDate'], $b['ReportedDate']);
		// 	});
		// } else {
		// 	$AddressInfo = array();
		// }
		// // end sorting of account

		// //start get mobile and home number
		// $number = array();
		// $number['mobile'] = '';
		// $number['home'] = '';
		// if (isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PhoneInfo']) && !empty($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PhoneInfo'])) {
		// 	foreach ($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PhoneInfo'] as $p_key => $p_value) {
		// 		if ($p_value['typeCode'] == "H" && empty($number['home'])) {
		// 			$number['home'] = $p_value['Number'];
		// 		}
		// 		if ((isset($user['number']) && $user['number'] == $p_value['Number']) || ($p_value['typeCode'] == "M" && empty($number['mobile']))) {
		// 			$number['mobile'] = $p_value['Number'];
		// 		}
		// 		if ($p_value['typeCode'] == "T" && empty($number['workphone'])) {
		// 			$number['workphone'] = $p_value['Number'];
		// 		}
		// 	}
		// }
		//end

		if($cp_id != null){
			$c_id = $consentPayment->consent_id;
            
            return view('admin.business.equifax-b2b.index',compact('records','dateTime','cp_id', 'c_id','business_details','pan_details','contact_details','overallcreditsummary_borrower','overallcreditsummary_keys','credit_usage','total_enquiries','payment_score','total_account','credit_age','credit_facility','report_date','totalSuccessPayment','totalPayments','score_value','report_no','recent_enquiries','cin_details','tin_details','service_tax_details','business_registration_no','user','RelationshipDetails', 'response'));
		} else {
       		return view('admin.business.my-records.report.index',compact('records','dateTime','cp_id', 'c_id','user'));
	    }
	}

	public function downloadReport(Request $request){

		if(!empty($request->cr_id)){
			if(empty($request->c_id) || empty($request->r_n)) {
				return redirect()->back()->with(['message' => "something went wrong.", 'alert-type' => 'error']);
			}
		}
		// else{
		// 	if(empty($request->cp_id) || empty($request->c_id) || empty($request->r_n)) {
		// 		return redirect()->back()->with(['message' => "something went wrong".$request->cp_id.".", 'alert-type' => 'error']);
		// 	}
		// }

		$dataList = Collection::make();
		$reportForYear = setting('admin.generate_report_from_consent_for_last_year') ? (int)setting('admin.generate_report_from_consent_for_last_year') : 0 ;
		$consent_payment_successful_valid_for_in_year = setting('admin.consent_payment_successful_valid_for_in_year') ? (int)setting('admin.consent_payment_successful_valid_for_in_year') : 7 ;
		$currentTime = Carbon::now();
        $beforeDateTime = Carbon::now()->subYear($consent_payment_successful_valid_for_in_year);

		if(!empty($request->cr_id)){
			$dataList = ConsentRequest::with('detail')->where('id',$request->cr_id)
					->where('added_by',Auth::id())
					->where('status',3)
					->where('customer_type','=','BUSINESS')
					->get();
		}else{
			if(!empty($request->cp_id)){
				$consentPayment = ConsentPayment::where('id',$request->cp_id)
					->where('status',4)
					->where('customer_type','=','BUSINESS')
					->where('added_by',Auth::id())
					->where('updated_at','>=',$beforeDateTime)
					->first();
				if(empty($consentPayment)){
					return redirect()->back()->with(['message' => "something went wrong.", 'alert-type' => 'error']);
				}
				$dataList = ConsentRequest::with('detail')->where('id', $consentPayment->consent_id)
					->where('added_by',Auth::id())
					->where('status',3)
					->where('customer_type','=','BUSINESS')
					->get();

			}else{
				// return redirect()->back()->with(['message' => "something went wrong.", 'alert-type' => 'error']);
				if($reportForYear>0){
					$previousYears = Carbon::now()->subYear($reportForYear);
					$dataList = ConsentRequest::with('detail')
						->where('added_by',Auth::id())
						->where('status',3)
						->where('created_at','>=',$previousYears)
						->where('customer_type','=','BUSINESS')
						->get();
				}
			}
		}
		$records = Collection::make();
		if($dataList->count()){
			//dd($dataList);
			$businessIds = [];
			foreach($dataList as $data) {
				$business = Businesses::with('dues')->whereHas('dues',function($q){
					$q->whereNull('deleted_at');
				})->where('concerned_person_phone',General::encrypt($data->concerned_person_phone));

				if(!empty($data->unique_identification_number)){
					$business = $business->where('unique_identification_number',General::encrypt($data->unique_identification_number));
				}
				$business =  $business->where('id',$request->c_id);
				$business = $business->whereNull('deleted_at')->get();
				if($business->count()){
					foreach ($business as $s) {
						if(!in_array($s->id,$businessIds)){
							$businessIds[] = $s->id;
						}
					}
				}

			}

			// get business detail from ids

			$records = Businesses::whereIn('id',$businessIds)->get();
			//dd($records);
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
		$pdf = PDF::loadView('admin.business.my-records.report.table', ['records'=>$records,'dateTime'=>$dateTime,'reportNumber'=>$request->r_n]);
        //$pdf = PDF::loadView('admin.students.report.download', ['records'=>$records,'dateTime'=>$dateTime]);
        $fileName = $request->r_n.'.pdf';
        return $pdf->download('Recordent-'.$fileName);
	}

	public function getDataFromConsentApi($user)
	{
		// dd($user);
		$data = $this->getRequestParams($user);
		$curl = curl_init();

		curl_setopt_array($curl, array(
			// CURLOPT_URL => 'https://eportuat.equifax.co.in/cir360Report/cir360Report',
			CURLOPT_URL => config('app.equifax_url'),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30000,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => array(
				// Set here requred headers
				"accept: */*",
				"accept-language: en-US,en;q=0.8",
				"content-type: application/json",
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			// echo "cURL Error #:" . $err;
			return false;
		} else {

			// dd(json_decode($response, true));
			$result = json_decode($response, true);
			return $result;
		}
	}

	private function getRequestParams($user)
	{
		if (isset($user['id_value'])) {
			$idDetails = [
				"seq" => "1",
				"IDValue" => General::decrypt($user['id_value']),
				"IDType" => $user['id_type'],
				"Source" => "Inquiry"
			];
		} else {
			$idDetails = [];
		}
		// dd(env("EQUIFAX_CUSTOMER_ID"));
		$data =
		[
               'RequestHeader' => [
         				"CustomerId" => config('app.customer_id'),
         				"UserId" => config('app.user_id_b2b'),
         				"Password" => config('app.equifax_password'),
         				"MemberNumber" => config('app.member_number_b2b'),
         				"SecurityCode" => config('app.security_code_b2b'),
         				"ProductCode" => [
         					config('app.product_code_b2b')
         				],
                "CustRefField"=> "REF16271"
         				// "CustomerId" => env("EQUIFAX_CUSTOMER_ID"),
         				// "UserId" => env("EQUIFAX_USER_ID"),
         				// "Password" => env("EQUIFAX_PASSWORD"),
         				// "MemberNumber" => env("EQUIFAX_MEMBER_NUMBER"),
         				// "SecurityCode" => env("EQUIFAX_SECURITY_CODE"),
         				// "ProductCode" => [
         				// 	env("EQUIFAX_PRODUCT_CODE")
         				// ]
         			],
		  "RequestBodyCommercial"=> [
		        "InquiryPurpose"=> "0200", //Hardcode
		        "TransactionAmount"=> "100", //Hardcode
		        "BusinessName"=> $user['name'], //Name of the Business derived from the GSTIN search
		        "InquiryAddresses"=> [
		         [
		           "seq"=> "1",
		           "AddressType"=> [
		           "O"
                  ],
                  "AddressLine1"=> $user['address'], //Entered, Prefilled on the Popup
                  "AddressLine2"=> "", //Entered, Prefilled on the Popup
                  "Locality"=> "",
                  "City"=> $user['city'], //Entered, Prefilled on the Popup
                  "State"=> $user['state'], //Entered, Prefilled on the Popup
                  "Postal"=> $user['pincode'] //Entered, Prefilled on the Popup
                 ]
                ],
                "InquiryPhones"=> [
                 [
                   "seq"=> "1",
                   "PhoneType"=> [
                   "M"
                  ],
                  "Number"=> $user['number'] //Entered during Search  $user['number']
                 ],
                 [
                   "seq"=> "2",
                   "PhoneType"=> [
                   "M"
                  ],
                  "Number"=> ""
                 ]
                ],
                "EmailAddresses"=> [
                 [
                    "seq"=> "1",
                    "EmailType"=> [
                    ""
                   ],
                   "Email"=> $user['email']
                 ],
                 [
                    "seq"=> "2",
                    "EmailType"=> [
                    ""
                   ],
                   "Email"=> ""
                 ]
                ],
	            "CustomFields"=> [
	              [
			        "key"=> "Product_Category",
			        "value"=> "OnlyCBR" //Hard Code
			      ],
			      [
			        "key"=> "Relationship_Type",
			        "value"=> "Proprietor" //Hard Code
			      ],
			      [
			        "key"=> "DIR_FULL_NAME",
			        "value"=> $user['authorized_name'] //Entered in the link
			      ],
			      [
			        "key"=> "DIR_DOB",
			        "value"=> $user['authorized_dob'] //Entered in the link
			      ],
			      [
			        "key"=> "DIR_MOBILE",
			        "value"=> $user['link_contact_phone'] //Entered in the link
			      ],
			      [
			         "key" => "DIR_AADHAR",
			         "value" => $user['aadhar'] //Entered in the link
			      ],
			      [
			        "key"=> "DIR_PAN",
			        "value"=> $user['pan'] //Entered in the link
			      ],
			      [
			        "key"=> "DIR_Voter_ID",
			        "value"=> $user['voter_id'] //Entered in the link
			      ],
			      [
			        "key"=> "DIR_Driv_License",
			        "value"=> $user['driving_license'] //Entered in the link
			      ],
			      [
			        "key"=> "DIR_Passport",
			        "value"=> $user['passport'] //Entered in the link
			      ],
			      [
			        "key"=> "DIR_Ration_Card",
			        "value"=> $user['ration_card'] //Entered in the link
			      ]
	             ],
			     "IDDetails"=> [
			       [
			         "seq"=> "1",
			         "IDType"=> "T",
			         "IDValue"=> $user['company_id'], //Entered in the link
			         "Source"=> "Inquiry"
			       ],
			       [
			         "seq"=> "2",
			         "IDType"=> "",
			         "IDValue"=> "",
			         "Source"=> "Inquiry"
			       ]
			      ]
      ],
      "Score"=> [
       [
        "Type"=> "ERS",
        "Version"=> "3.0"
       ]
      ]
    ];

		return $data;
	}


	public static function getTotalDueForBusinessByCustomId(Request $request)
	{
	     $businessID = $request->businessID;
	     $dueId = $request->dueId;
	     $added_by = $request->added_by;
	     $custom_id = $request->custom_id;
	     if(!isset($custom_id)){
                  $custom_id = NULL;
	     }

	     $result=General::getTotalDueForBusinessByCustomId($businessID,$added_by,$dueId,$custom_id) - General::getTotalPaidForBusinessByCustomId($businessID,$added_by,$dueId,$custom_id);
	     return $result;

	}

	public static function getBusinessDuesCustomerLevel(Request $request)
	{

	     $businessID = $request->input('businessID');
	     $dueId = $request->input('dueId');
	     $custom_id = $request->input('custom_id');
	 //     $getCustomId = BusinessDueFees::select('external_business_id')->where('business_id','=',$businessID)->where('id','=',$dueId);
	 //    $getCustomId = $getCustomId->first();
		// $checkCustomId = isset($getCustomId->external_business_id) ? $getCustomId->external_business_id : NULL;
	   //   $getCustomId = BusinessDueFees::select('external_business_id')->where('business_id','=',$businessID);
	  	// 		$getCustomId = $getCustomId->first();
				// $checkCustomId = $getCustomId->external_business_id;
	   	 $dues = BusinessDueFees::where('business_id',$businessID)->where('added_by',Auth::id())->where('external_business_id', $custom_id)->whereNull('deleted_at');
	    	$dues = $dues->withCount([
			'paid AS totalPaid' => function ($query)  {
				$query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at');
			}
		]);
	    	$dues = $dues->get();
            return $dues;


	 }


	public function storePayAmountCustomerLevel(Request $request){

	 	$businessID = $request->business_id;
	    $dueId = $request->business_due_id;
	    $due_amount = $request->due_amount;
	    $payment_amount = $request->payment_amount;
	    $payment_options = $request->payment_options;
	    $skipandupdatepayment = $request->skipandupdatepayment;
	    $paid_date = Carbon::createFromFormat('d/m/Y', $request->payment_date)->toDateString();
	    $paid_note = $request->payment_note;
	    $checkbox = $request->checkbox;
	    $orderArr = $request->orderArr;
	 	$orderArr = json_decode($orderArr);
	 	if(!isset($paid_note)){
         $paid_note = 0;
	 	}
	     if(isset($orderArr)){
	    	$orderArr= json_decode($request->orderArr);
		    $orderArr=implode(",",$orderArr);
		    $payment_options = 0;
	    }  else if(isset($payment_options)) {
	    	 $orderArr = 0;
	    	  if($payment_options == "Other"){
              $payment_options = $request->type_of_payment;

	       }
	       $request->payment_amount = $due_amount;
	   	}  else {

	    	$orderArr=0;
	    	$payment_options=0;
	   	}

	   $consent_payment_value_gst_in_perc = setting('admin.consent_payment_value_gst_in_perc') ? (int)setting('admin.consent_payment_value_gst_in_perc') : 0;
		$collectionFee1 = 0;
		$collectionFee = 0;
		$totalGSTValue = 0;
		$totalCollectionValue = 0;

		$collectionFeePerc = HomeHelper::getMyRecordsCollectionFeePercent();
		$temp = ($request->payment_amount * $collectionFeePerc) / 100;

		$collectionFee1 = $collectionFee1 + $temp;

		if ($collectionFee1 > 50) {
			$collectionFee = bcdiv($collectionFee1, 1, 2);
		} else {
			$collectionFee = 50;
		}

		if ($consent_payment_value_gst_in_perc > 0) {
			$temp = ($collectionFee * $consent_payment_value_gst_in_perc) / 100;
			$totalGSTValue = $totalGSTValue + $temp;
			$totalGSTValue = bcdiv($totalGSTValue, 1, 2);
		}

		$totalCollectionValue = $collectionFee + $totalGSTValue;
		if ($totalCollectionValue < 1) {
			$totalCollectionValue = 1;
		}

		$invoice_no = MembershipPayment::where('created_at', '>=', date('Y-m-d 00:00:00'))->where('status', 4)->count();
		$invoice_no = $invoice_no + 1;
		$valuesForMembershipPayment = [
			'customer_id' => Auth::user()->id,
			'invoice_id' => date('dmY') . sprintf('%07d', $invoice_no),
			'pricing_plan_id' => 0,
			'customer_type' => "INDIVIDUAL",
			'payment_value' => $collectionFee,
			'gst_perc' => $consent_payment_value_gst_in_perc,
			'gst_value' => $totalGSTValue,
			'total_collection_value' => $totalCollectionValue,
			'particular' => "Collection Fee",
			'due_id' => $dueId,
			'postpaid' => Auth::user()->collection_fee_business == 1 ? 1 : 0,
			'status' => 4,
			'invoice_type_id' => 6
		];
		if (Auth::user()->collection_fee_business == 1) {


			$membershipPayment = MembershipPayment::create($valuesForMembershipPayment);
			General::UpdatePaymentsCustomerLevelBusiness($businessID,$dueId,$paid_note,$orderArr,$skipandupdatepayment,$payment_options,$paid_date,$payment_amount);

			// $response = app('App\Http\Controllers\HomeController')->postpaid_invoice_sendmail($membershipPayment->id);
		    if(array_key_exists('send_updatepayment_sms',$request->all())) {
		    	$business= Businesses::where('id',$businessID)->first();
		    	if(isset($business)){
					$mobile_number= $business->concerned_person_phone;
				   $name= $business->concerned_person_name;
				   $business_name = Auth::user()->business_name;
				   $email = $business->email;
				   $amount = $request->payment_amount;
					// $message = $name. ' we thank you for the payment of INR ' .$amount .' made on '.Carbon::now()->format('d-M-Y') . ' to '.$business_name .'.'. ' To view your updated record, click here ' . route('your.reported.dues') ;
					$message='We thank you for the payment of INR ' .$amount .' made on '.Carbon::now()->format('d-M-Y') . ' to '.$business_name .'. To view your updated record, click here ' . route('your.reported.dues');
					$smsService = new SmsService();

					$smsResponse = $smsService->sendSms($mobile_number,$message);

					if($smsResponse['fail_to_send']){
					return response()->json(['error'=>true,'message'=>'server not responding'], 500);
					}
					/*if(isset($email)){
						try{
							SendMail::send('front.emails.send-otp-to-email', [
							'otpMessage' => $message
							], function($message) use ($email) {
							$message->to($email)
							->subject("Your Payment to Recordent");
							});
						}catch(JWTException $exception){
							$this->serverstatuscode = "0";
							$this->serverstatusdes = $exception->getMessage();
						}
					}*/


				}
			}

			$duesRecord = BusinessDueFees::where('id',$dueId)->where('business_id', $businessID)->where('added_by',Auth::id())->whereNull('deleted_at')->first();

			if($duesRecord->balance_due !=0)
			{
				General::Update_Balance_Due($duesRecord->balance_due,$request->payment_amount,"Business",$dueId,$businessID);
			}
			return redirect()->back()->with(['message' => 'Payment updated successfully.', 'alert-type' => 'success']);
		}

		DB::beginTransaction();
		try {
			$send_sms_email = 0;
			if(array_key_exists('send_updatepayment_sms',$request->all())) { $send_sms_email = 1; }

			$tempDuePayment = TempDuePayment::create([
				'order_id' => Str::random(40),
				'customer_type' => 'BUSINESS',
				'customer_id' => $businessID,
				'due_id' => $dueId,
				'payment_value' => $payment_amount,
				'created_at' => Carbon::now(),
				'added_by' => Auth::id(),
				'payment_note' => $paid_note,
				'payment_date' => $payment_amount,
				'send_sms_email' => $send_sms_email,
				'external_business_id' => ""
			]);

			$duePayment = DuePayment::create([
				'order_id' => $tempDuePayment->order_id,
				'customer_type' => $tempDuePayment->customer_type,
				'customer_id' => $tempDuePayment->customer_id,
				'due_id' => $tempDuePayment->due_id,
				'payment_value' => $tempDuePayment->payment_value,

				'status' => 1, //initiated
				'created_at' => Carbon::now(),
				'added_by' => Auth::id(),
				'payment_done_by' => 'ADMIN_MEMBER',

				'collection_fee_perc' => 1,
				'gst_perc' => $consent_payment_value_gst_in_perc,

				'gst_value' => $totalGSTValue,
				'collection_fee' => $collectionFee,
				'total_collection_value' => $totalCollectionValue
			]);
			$membershipPayment1 =  MembershipPayment::where('due_id', $tempDuePayment->due_id)->first();
			if (!empty($membershipPayment1)) {
				$membershipPayment1->delete();
			}
			$membershipPayment = MembershipPayment::create($valuesForMembershipPayment);
			DB::commit();
		} catch (\Exception $e) {
			// DB::rollback();
			// dd($e);
			return redirect()->back()->with(['message' => "can not create payment process. Please try again.", 'alert-type' => 'error']);
		}
		$userDataToPaytm = User::findOrFail(Auth::user()->id);
		$userDataToPaytm_name = preg_replace('/\s+/', '_', $userDataToPaytm->name);

		$duePayment->pg_type = setting('admin.payment_gateway_type');
		$duePayment->update();

		if (setting('admin.payment_gateway_type') == 'paytm') {

			$payment = PaytmWallet::with('receive');
			$payment->prepare([
				'order' => $duePayment->order_id,
				'user' => $userDataToPaytm_name,
				'mobile_number' => $userDataToPaytm->mobile_number,
				'email' => $userDataToPaytm->email,
				'amount' => $totalCollectionValue,
				'callback_url' => route('business.business-due-payment-callback-customer-level',[$paid_date,$paid_note,$orderArr,$payment_options,$skipandupdatepayment])
			]);
			return $payment->view('admin.payment-submit')->receive();
		} else {

			$postData = [
				'amount' => $duePayment->total_collection_value,
				'txnid' => $duePayment->order_id,
				'phone' => $userDataToPaytm->mobile_number,
				'email' => $userDataToPaytm->email,
				'firstname' => preg_replace('/\s+/', '', $userDataToPaytm->name),
				'surl' => route('business.business-due-payment-callback-customer-level',[$paid_date,$paid_note,$orderArr,$payment_options,$skipandupdatepayment])
			];

			$payuForm = General::generatePayuForm($postData);

			return view('admin.payment-submit', compact('payuForm'));



	 }
	}

	public function duePaymentCallbackCustomerLevel(Request $request, $paid_date=null, $paid_note=null,$orderArr=null,$payment_options=null,$skipandupdatepayment=null)
	{

		if (setting('admin.payment_gateway_type') == 'paytm') {
			$transaction = PaytmWallet::with('receive');
			try {
				$response = $transaction->response();
			} catch (\Exception $e) {
				return redirect()->route('my-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		} else {
			try {
				$response = General::verifyPayuPayment($request->all());
				if (!$response) {
					return redirect()->route('my-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
				}
			} catch (\Exception $e) {
				return redirect()->route('my-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		}

		$duePayment = DuePayment::where('order_id', '=', $response['ORDERID'])
			->where('added_by', Auth::id())
			->first();
		if (empty($duePayment)) {
			return redirect()->route('my-records')->with(['message' => "Invalid due payment", 'alert-type' => 'error']);
		}

		$tempDuePayment = TempDuePayment::where('order_id', '=', $response['ORDERID'])
			->where('added_by', Auth::id())
			->first();
		if (empty($tempDuePayment)) {
			return redirect()->route('my-records')->with(['message' => "Invalid due payment", 'alert-type' => 'error']);
		}
		$redirectQueryString = $tempDuePayment->redirect_query_string;

		$message = '';
		$alertType = 'info';
		if (setting('admin.payment_gateway_type') == 'paytm') {
			if ($transaction->isSuccessful()) {
				$paymentStatus = 'success';
			} else if ($transaction->isFailed()) {
				$paymentStatus = 'failed';
			} else {
				$paymentStatus = 'open';
			}
		} else {
			$paymentStatus = $response['paymentStatus'] == 'success' ? 'success' : ($response['paymentStatus'] == 'failure' ? 'failed' : 'open');
		}

		Log::debug('paymentStatus = '.print_r($paymentStatus, true));

		$duePayment->transaction_id = $response['TXNID'] ?? $response['mihpayid'] ?? '';
		$duePayment->payment_mode = $response['PAYMENTMODE'] ?? $response['mode'] ?? '';

		if ($paymentStatus == 'success') {
			$duePayment->status = 4;
			$alertType = 'success';
			$message = 'Payment successful.';
		} else if ($paymentStatus == 'failed') {
			$duePayment->status = 5;
			$alertType = 'error';
			$message = 'Payment failed.';
		} else {
			$duePayment->status = 2;
			$alertType = 'info';
			$message = 'Payment is in progress.';
		}

		$duePayment->raw_response = json_encode($response);
		$duePayment->updated_at = Carbon::now();

		Log::debug('duePayment status = '.$duePayment->status);
		DB::beginTransaction();
		try {
			$duePayment->update();
			$customBusinessId = NULL;
			if ($duePayment->status == 4) { // successful payment
				if($tempDuePayment->external_business_id!="") {
					$customBusinessId = $tempDuePayment->external_business_id;
				}
				General::UpdatePaymentsCustomerLevelBusiness($duePayment->customer_id,$duePayment->due_id,$paid_note,$orderArr,$skipandupdatepayment,$payment_options,$paid_date,$duePayment->payment_value);

				$membershipPayment =  MembershipPayment::where('due_id', $tempDuePayment->due_id)->first();
				if (!empty($membershipPayment)) {
					// $response = app('App\Http\Controllers\HomeController')->postpaid_invoice_sendmail($membershipPayment->id);
				}
				if($tempDuePayment->send_sms_email) {
					$mobile_number= $duePayment->businessProfile->concerned_person_phone;
		         	$name= $duePayment->businessProfile->concerned_person_name;
		         	$business_name = Auth::user()->business_name;
		        	 $email = $duePayment->businessProfile->email;
		        	 $amount = $tempDuePayment->payment_value;
					$response ='We thank you for the payment of INR ' .$amount .' made on '.Carbon::now()->format('d-M-Y') . ' to '.$business_name .'. To view your updated record, click here ' . route('your.reported.dues');

					$smsService = new SmsService();

					$smsResponse = $smsService->sendSms($mobile_number,$response);

					if($smsResponse['fail_to_send']){
					return response()->json(['error'=>true,'message'=>'server not responding'], 500);
				   }
				   /*if(isset($email)){
					try{
					   SendMail::send('front.emails.send-otp-to-email', [
						   'otpMessage' => $response
					   ], function($response) use ($email) {
						   $response->to($email)
						   ->subject("Your Payment to Recordent");
					   });

				   }catch(JWTException $exception){
					   $this->serverstatuscode = "0";
					   $this->serverstatusdes = $exception->getMessage();
				   }
				   }*/
			}
			}

			$duePayment->update();
			if ($duePayment->status == 4 || $duePayment->status == 5) {
				$tempDuePayment->delete();
			}

			DB::commit();
		} catch (\Exception $e) {
			// DB::rollback();
			// dd($e);
			return redirect('admin/member-users-business-records' . $redirectQueryString)->with(['message' => 'can not store due payment.', 'alert-type' => 'error']);
		}

		if($payment_options == 0){
			if($paymentStatus == 'success'){
		
				$duesRecord = BusinessDueFees::where('id',$duePayment->due_id)->where('business_id', $duePayment->customer_id)->where('added_by',Auth::id())->whereNull('deleted_at')->first();
				if($duesRecord->balance_due !=0)
				{
					General::Update_Balance_Due($duesRecord->balance_due,$duePayment->payment_value,"Business",$duePayment->due_id,$duePayment->customer_id);
				}
				}
		}

		return redirect('admin/member-users-business-records' . $redirectQueryString)->with(['message' => $message, 'alert-type' => $alertType]);
}
	public function getProofOfDueList(Request $request){

        $businessid=$request->studentid;
        $custom_id=$request->cust_id;
        $due_id=$request->due_id;
        $businessdata =  DB::table('businesses')
						->select('*')
						->where('id', $businessid)
						->where('added_by', Auth::id())
						->get();
        $businessdata_dues =  DB::table('business_due_fees')
						->select('*')
						->where('business_id', $businessid)
						->where('external_business_id',$custom_id)
						->where('added_by', Auth::id())
						->get();

        $business=array();
        foreach($businessdata as $rec)
        {
			if($rec->proof_of_due !=null){
				$dues_list_img=str_replace("business/proof_of_due/",'',$rec->proof_of_due);
				$dues_list_img=trim($dues_list_img,",");
				$proofList=explode(",",$dues_list_img);
				$proof_of_due="";
				foreach($proofList as $img)
				{
					$file_name=storage_path('app/public/business/proof_of_due/'.$img);
					if (file_exists($file_name)){

						$proof_of_due .=$img.",";
					}else{
						$proof_of_due .='';
					}
				}
				$proof_of_due=trim($proof_of_due,",");
				if($proof_of_due != "")
			{
				$rec->proof_of_due='business/proof_of_due/'.$proof_of_due;
			}else{
				$rec->proof_of_due=null;
			}


			}
            $rec->company_name=strtoupper(General::decrypt($rec->company_name));
            $rec->flag=1;
            $business[]=$rec;
        }

        $business_due=array();
        foreach($businessdata_dues as $rec)
        {
            $paidAmount=BusinessPaidFees::where('business_id', '=', $businessid)
                                        ->where('due_id', '=', $rec->id)->select('paid_amount')
                                        ->groupBy('business_id')->sum('paid_amount');

            if($paidAmount>0)
            {
                $remaing_balance=($rec->due_amount) - ($paidAmount);
            }else{
                $remaing_balance=$rec->due_amount;
            }
            $rec->remaing_balance=$remaing_balance;

            $business_due[]=$rec;
        }

        $result=array_merge($business,$business_due);
        $records=array();
        foreach($result as $rec)
        {
            $records[]=$rec;

        }

        return Response::json(['success' => true,"message"=>'', 'data' => $records], 200);
    }


	public function BusinessIsAssigneProofdDue(Request $request)
	{
		$proofof_due_file=$request->proofof_due_file;
		$business_id=$request->studen_id;

		$data=BusinessDueFees::where('business_id', $business_id)
								->where('proof_of_due','!=' , null)
								->where('added_by', Auth::id())
								->get();

		$exsitingFiles=array();
		foreach($data as $rec)
		{
			$proofOfDue_file=$rec->proof_of_due;
			$proofOfDue=str_replace("business/proof_of_due/","",$proofOfDue_file);
			if (strpos($proofOfDue, $proofof_due_file) !== false) {
				$exsitingFiles[]=$rec->id;
			}

		}
		return Response::json(['success' => true,"message"=>'', 'data' => $exsitingFiles], 200);

	}

	public function indiaB2BPDFReport(Request $request){
		ini_set('max_execution_time', 0);

		$c_id = $request->c_id;

		$dataList = Collection::make();
		$reportForYear = setting('admin.generate_report_from_consent_for_last_year') ? (int)setting('admin.generate_report_from_consent_for_last_year') : 0 ;
		$consent_payment_successful_valid_for_in_year = setting('admin.consent_payment_successful_valid_for_in_year') ? (int)setting('admin.consent_payment_successful_valid_for_in_year') : 7;
		
		$currentTime = Carbon::now();
        $beforeDateTime = Carbon::now()->subYear($consent_payment_successful_valid_for_in_year);
		
		if(!empty($request->c_id)){
			$dataList = ConsentRequest::with('detail')->where('id', $c_id)
					->where('added_by',Auth::id())
					->where('status',3)
					->where('customer_type','=','BUSINESS')
					->get();
		}

		$records = Collection::make();
		
		if($dataList->count()){
			$businessIds = [];
			foreach($dataList as $data) {
				$business = Businesses::with('dues')->whereHas('dues',function($q){
						$q->whereNull('deleted_at');
					})->where('concerned_person_phone', General::encrypt($data->concerned_person_phone));

				if(!empty($data->unique_identification_number)){
					$business = $business->where('unique_identification_number', General::encrypt(strtoupper($data->unique_identification_number)));
				}

				$business = $business->whereNull('deleted_at')->get();
				if($business->count()){
					foreach ($business as $s) {
						if(!in_array($s->id,$businessIds)){
							$businessIds[] = $s->id;
						}
					}
				}
			}

			// get business detail from ids
			// $records = Businesses::whereIn('id',$businessIds)->get();
			$records = Businesses::with(['dues', 'dues.paid', 'dues.dispute'])->whereIn('id', $businessIds)->get();

			// dd($records);
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

				//1 to 89 days
				$overDueStatusCount = BusinessDueFees::whereRaw("datediff(CURDATE(),due_date) < 90")->where('business_id',$record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus0To89Days = $overDueStatusCount;

				//90 to 179 days
				$overDueStatusCount = BusinessDueFees::whereRaw(" datediff(CURDATE(),due_date) <=179 AND datediff(CURDATE(),due_date) >=90 ")->where('business_id',$record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus90To179Days = $overDueStatusCount;

				//180plus
				$overDueStatusCount = BusinessDueFees::whereRaw("datediff(CURDATE(),due_date) >=180 ")->where('business_id',$record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus180PlusDays = $overDueStatusCount;


				/* account detail */
				$accountDetails = BusinessDueFees::with(['addedBy','profile'])->whereHas('addedBy')->whereHas('profile')->where('business_id',$record->id)->whereNull('deleted_at')->get();

				$record->accountDetails = $accountDetails;
			}
		}

		$dateTime = Carbon::now()->format('d-m-Y H:i');
		$c_id = $request->c_id;
		$identityType = [
			// 'AADHAR' => 'M',
			1 => 'T',
			3 => 'P',
			2 => 'V',
			4 => 'D',
			5 => 'R',
			// 'RationCard' => 'R',
		];

		$consentRequest = $dataList->toArray();
        $states = State::where('id', $consentRequest[0]['state'])->first();
		$user['name'] = isset($consentRequest[0]) ? $consentRequest[0]['business_name'] : '';
		$user['unique_identification_number']= isset($consentRequest[0]) ? $consentRequest[0]['unique_identification_number'] : '';
		$user['email'] = isset($consentRequest[0]) ? $consentRequest[0]['directors_email'] : '';
		$user['address'] = isset($consentRequest[0]) ? $consentRequest[0]['address'] : '';
		$user['city'] = isset($consentRequest[0]) ? $consentRequest[0]['city'] : '';
		$user['state'] = isset($states->short_code) ? $states->short_code : '';
		$user['pincode'] = isset($consentRequest[0]) && $consentRequest[0] != NULL ? General::decrypt($consentRequest[0]['pincode']) : '';
		$user['authorized_name'] = isset($consentRequest[0]) ? $consentRequest[0]['authorized_signatory_name'] : '';
		$user['authorized_dob'] = isset($consentRequest[0]) ? $consentRequest[0]['authorized_signatory_dob'] : '';
		$user['company_id'] = isset($consentRequest[0]) ? $consentRequest[0]['company_id'] : '';
		$user['number'] = isset($consentRequest[0]) ? $consentRequest[0]['concerned_person_phone'] : '';
		$user['link_contact_phone'] = isset($consentRequest[0]) ? $consentRequest[0]['link_contact_phone'] : '';
		$user['id_value'] = isset($consentRequest[0]) && $consentRequest[0] != NULL ? General::decrypt($consentRequest[0]['idvalue']) : '';
		$user['id_type'] = isset($consentRequest[0]) && $consentRequest[0] != NULL ? $consentRequest[0]['idtype'] : '';

		if($user['id_type']==1){
			$user['pan'] = $user['id_value'];
			$user['voter_id'] = '';
			$user['passport'] = '';
			$user['driving_license'] = '';
			$user['ration_card'] = '';
			$user['aadhar'] = '';
		} else if($user['id_type']==2){
			$user['pan'] = '';
			$user['voter_id'] = $user['id_value'];
			$user['passport'] = '';
			$user['driving_license'] = '';
			$user['ration_card'] = '';
			$user['aadhar'] = '';
		} else if($user['id_type']==3){
			$user['pan'] = '';
			$user['voter_id'] = '';
			$user['passport'] = $user['id_value'];
			$user['driving_license'] = '';
			$user['ration_card'] = '';
			$user['aadhar'] = '';
		} else if($user['id_type']==4){
			$user['pan'] = '';
			$user['voter_id'] = '';
			$user['passport'] = '';
			$user['driving_license'] = $user['id_value'];
			$user['ration_card'] = '';
			$user['aadhar'] = '';
		} else if($user['id_type']==5){
			$user['pan'] = '';
			$user['voter_id'] = '';
			$user['passport'] = '';
			$user['driving_license'] = '';
			$user['ration_card'] = $user['id_value'];
			$user['aadhar'] = '';
		} else if($user['id_type']==6){
			$user['pan'] = '';
			$user['voter_id'] = '';
			$user['passport'] = '';
			$user['driving_license'] = '';
			$user['ration_card'] = '';
			$user['aadhar'] = $user['id_value'];
		}

		$user['recordent'] = [
			'total_members' => count($records),
			'total_dues_unpaid' => 0,
			'total_dues_paid' => 0,
			'total_dues' => 0,
			'summary_overDueStatus0To89Days' => 0,
			'summary_overDueStatus90To179Days' => 0,
			'summary_overDueStatus180PlusDays' => 0
		];

	    if($records) {
			foreach ($records->toArray() as $r_key => $r_value) {

	  			$user['recordent']['summary_overDueStatus0To89Days'] += $r_value['summary_overDueStatus0To89Days'];
	  			$user['recordent']['summary_overDueStatus90To179Days'] += $r_value['summary_overDueStatus90To179Days'];
	  			$user['recordent']['summary_overDueStatus180PlusDays'] += $r_value['summary_overDueStatus180PlusDays'];
	  			$user['recordent']['total_dues'] += count($r_value['dues']);

	  			foreach ($r_value['dues'] as $r_due_key => $r_due_value) {
	  				$user['recordent']['total_dues_unpaid'] += $r_due_value['due_amount'];
	  				foreach ($r_due_value['paid'] as $r_due_paid_key => $r_due_paid_value) {
	  					$user['recordent']['total_dues_paid'] += $r_due_paid_value['paid_amount'];
	  				}
	  			}
	  		}

	  		$businessRecord = Businesses::where('unique_identification_number', General::encrypt($user['unique_identification_number']))->first();
			if(isset($businessRecord)){
				$businessRecord = $businessRecord->toArray();

				$business_name_rec=$businessRecord['company_name'];
				$user['business_name_rec'] = $business_name_rec;
				$business_type_rec=$businessRecord['user_type'];
				$user_type = UserType::where('id',$business_type_rec)->first();
				$user['business_type_rec'] = isset($user_type->name) ? $user_type->name : 0;
				$business_sector_rec=$businessRecord['sector_id'];
				$sector_type = Sector::where('id',$business_sector_rec)->first();
				$user['business_sector_rec'] = isset($sector_type->name) ? $sector_type->name : 0;

				$business_concerned_name_rec=$businessRecord['concerned_person_name'];
				$user['business_concerned_name_rec'] = $business_concerned_name_rec;
				$business_email_rec=$businessRecord['email'];
				$user['business_email_rec'] = $business_email_rec;
				$business_designation_rec=$businessRecord['concerned_person_designation'];
				$user['business_designation_rec'] = $business_designation_rec;
           }
	  	}

		$user['recordent']['total_dues_unpaid'] = $user['recordent']['total_dues_unpaid'] - $user['recordent']['total_dues_paid'];


		if (isset($consentRequest[0]) && $consentRequest[0]['report'] == 3) {
			$api = ConsentAPIResponse::where('consent_request_id', $c_id)->first();

			if (!empty($api)) {
				$result = json_decode(General::decrypt($api->response), true);	
			}

			if (isset($result['Error']) || isset($result['CCRResponse']) && isset($result['CCRResponse']['CommercialBureauResponse']['Error']) || $result['CCRResponse']['CommercialBureauResponse']['hit_as_borrower'] == '00') {
				
		    }

			$business_details =$result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialPersonalInfo'];
			$business_details = (object)$business_details;
			// dd($business_details);
			$pan_details =$result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['PANId'][0];
			$pan_details = (object)$pan_details;
			if(isset($result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['CIN'])){
				$cin_details =$result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['CIN'][0];
				$cin_details = (object)$cin_details;
			} else {
				$cin_details= 0;
			}
			if(isset($result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['TIN'])){
				$tin_details =$result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['TIN'][0];
				$tin_details = (object)$tin_details;
		    } else {
		    	$tin_details=0;
		    }
		    if(isset($result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['ServiceTax'])){
				$service_tax_details =$result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['ServiceTax'][0];
				$service_tax_details = (object)$business_registration_no;
			} else {
				$service_tax_details=0;
			}
			if(isset($result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['BusinessRegistration'])){
				$business_registration_no =$result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialIdentityInfo']['BusinessRegistration'][0];
				$business_registration_no = (object)$business_registration_no;
			} else {
				$business_registration_no = 0;
			}
			if(isset($result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['RelationshipDetails'])){
				$RelationshipDetails = $result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['RelationshipDetails'];
			}


			$contact_details =$result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['IDAndContactInfo']['CommercialPhoneInfo'];
			$overallcreditsummary_borrower = $result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['CommercialCIRSummary']['OverallCreditSummary']['AsBorrower'];

			if(isset($overallcreditsummary_borrower)){
				krsort($overallcreditsummary_borrower);
				$overallcreditsummary_keys= array_keys($overallcreditsummary_borrower);

				// dd($overallcreditsummary_borrower);
				$overallcreditsummary_borrower_keys = array('a','b','c');
				$overallcreditsummary_borrower = array_combine($overallcreditsummary_borrower_keys, $overallcreditsummary_borrower);
				$overallcreditsummary_borrower = (object)$overallcreditsummary_borrower;
				 // dd($overallcreditsummary_borrower);
				$overallcreditsummary_borrower->a = (object)$overallcreditsummary_borrower->a;
				$overallcreditsummary_borrower->b = (object)$overallcreditsummary_borrower->b;
				$overallcreditsummary_borrower->c = (object)$overallcreditsummary_borrower->c;
            }

			$credit_facility =$result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['CreditFacilityDetails'];


			// dd($credit_facility);
            $credit_usage = 0;
            $count_of_enter=0;
		    foreach ($credit_facility as  $value) {
		    	$count_of_enter++;
              	if($value['sanctioned_amount_notional_amountofcontract']!='0'){
                   $credit_usage += ($value['current_balance_limit_utilized_marktomarket']/$value['sanctioned_amount_notional_amountofcontract'])*100;
                } else {
                  if(isset($value['high_credit'])){
                     $credit_usage += ($value['current_balance_limit_utilized_marktomarket']/$value['high_credit'])*100;
                   } else {
                   	$credit_usage = 0;
                   }
                }
            }

            $credit_usage = $credit_usage/$count_of_enter;

		    $total_enquiries = $result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['EnquirySummary']['Total'];
		    $payment_score = $result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['CreditFacilityDetails'];
		    $totalPayments=0;
		    $totalSuccessPayment=0;
		    $statusArray = ['000', '*', 'STD', 'NEW', 'CLSD', 'OPEN', 'RES'];
		    $total_account =0;
		    $card_age_years = '';
		    $credit_age = [];

		    foreach ($payment_score as $key => $value) {
		    	$total_account++;
		        if (isset($value['History48Months'])) {
					foreach ($value['History48Months'] as $key_history => $value_history) {
						$totalPayments++;
						if (in_array($value_history['assetclassification_dayspastdue'], $statusArray)) {
							$totalSuccessPayment++;
						}
					}
				}

				if (isset($value['sanctiondate_loanactivation'])) {
                    $date1 = strtotime($value['sanctiondate_loanactivation']);
                    $date2 = strtotime(date('Y-m-d'));
                    $diff = abs($date2 - $date1);
                    $card_age_years = floor($diff / (365 * 60 * 60 * 24));
                    $credit_age[] = $card_age_years;
                }
		    }

		    $credit_age = max($credit_age);
		    $payment_score = ($totalSuccessPayment/$totalPayments)*100;
		    $report_date = $result['CCRResponse']['CommercialBureauResponse']['InquiryResponseHeader']['Date'];
		    $report_no = $result['CCRResponse']['CommercialBureauResponse']['InquiryResponseHeader']['ReportOrderNO'];

		   if(isset($result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['CommercialCIRSummary']['EquifaxRank_ScoresCommercial']['CommercialRank_ScoreDetailsLst'][0]['Rank_ScoreValue'])){
		    	$score_value = $result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['CommercialCIRSummary']['EquifaxRank_ScoresCommercial']['CommercialRank_ScoreDetailsLst'][0]['Rank_ScoreValue'];
		    }else if(isset($result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['CommercialCIRSummary']['EquifaxScoresCommercial']['CommercialScoreDetailsLst'][0]['ScoreValue'])){
		    	$score_value = $result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['CommercialCIRSummary']['EquifaxScoresCommercial']['CommercialScoreDetailsLst'][0]['ScoreValue'];
		    } else {
		    	$score_value = '0';
		    }

		    // dd($result['CCRResponse']);
		    if(isset($result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['RecentEnquiries'])){
		    	$recent_enquiries = $result['CCRResponse']['CommercialBureauResponse']['CommercialBureauResponseDetails']['RecentEnquiries'];
		    } else{
		    	$recent_enquiries =0;
		    }
		} else {
 			$result = [];
		}

		$response = $result;

		$api = ConsentAPIResponse::where('consent_request_id', $c_id)->first();
		$response = json_decode(General::decrypt($api->response), true);

		$score_percentage = ($score_value/10)*100;
		if($score_percentage>=1 && $score_percentage<=20 ){
		    $scoreText = 'Excellent';
		    $needle_color = '#82e360';
		} elseif($score_percentage>=30 && $score_percentage<=40 ){
		    $scoreText = 'Good';
		    $needle_color = '#f5d13d'; 
		}elseif($score_percentage>=50 && $score_percentage<=70 ){
		    $scoreText = 'Fair';
		    $needle_color = '#ffb36c'; 
		}elseif($score_percentage>=71 && $score_percentage<=100 ) {
		    $scoreText = 'Needs Improvement';
		    $needle_color = '#ff6c6c'; 
		}else{
		    $scoreText = 'Not Available';
		    $needle_color = '#ff6c6c';
		}

		$pdf = PDF::loadView('b2b_pdf', compact('records','dateTime', 'c_id','business_details','pan_details','contact_details','overallcreditsummary_borrower','overallcreditsummary_keys','credit_usage','total_enquiries','payment_score','total_account','credit_age','credit_facility','report_date','totalSuccessPayment','totalPayments','score_value','report_no','recent_enquiries','cin_details','tin_details','service_tax_details','business_registration_no','user','RelationshipDetails', 'response','scoreText', 'needle_color', 'score_percentage'));

			$fileName = $request->r_n . '.pdf';

		return $pdf->download('Recordent India B2B Business Report' . $fileName);
	}	

}
