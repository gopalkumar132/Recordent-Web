<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail as SendMail;

use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use App\Imports\StudentsDuePaymentImport;
use App\Imports\StudentsUpdateProfileImport;
use App\MembershipPayment;
use App\Students;
use App\StudentDueFees;
use App\StudentPaidFees;
use App\IndividualBulkUploadIssues;
use App\DuesSmsLog;
use App\ConsentRequest;
use App\ConsentPayment;
use App\Dispute;
use App\DuePayment;
use App\TempDuePayment;
use App\BulkDuePaymentUploadIssues;
use App\ConsentAPIResponse;
use App\ReportRequestResponseLog;
use App\UsersOfferCodes;
use App\Services\SmsService;
use App\SkippedDuesRecord;
use App\User;
use Carbon\Carbon;
use Validator;
use Response;
use DB;
use Auth;
use Storage;
use General;
use PDF;
use PaytmWallet;
use Session;
use HomeHelper;
use App\IndividualAdminReports;
use CustomerHelper;
use App\MemberCustomerMapping;

class StudentController extends Controller
{
	public function importExcelView()
	{
		$UploadFile = false;
		if (General::checkMemberEligibleToUploadPaymentMasterFile()) {
			$UploadFile = true;
		}
		$showUploadFile = array("isShow" => $UploadFile);
		return view('admin.import-excel')->with($showUploadFile);
	}

	public function importExcel(Request $request)
	{
		ini_set('max_execution_time', 0);

		$import = new StudentsImport;
		$import->uniqueUrlCode = Str::random(10);

		try {
			$fileToArray = (new StudentsImport)->toArray(request()->file('file'));
		} catch (\Exception $e) {
			return redirect()->back()->with(['message' => 'Something is wrong', 'alert-type' => 'error']);
		}

		$columnNames = array_keys($fileToArray[0][0]);
		if (
			!in_array('aadhar_number', $columnNames) ||
			!in_array('contact_phone_number', $columnNames) ||
			!in_array('person_name', $columnNames) ||
			!in_array('dob_ddmmyyyy', $columnNames) ||
			!in_array('father_name', $columnNames) ||
			!in_array('mother_name', $columnNames) ||
			!in_array('duedate_ddmmyyyy', $columnNames) ||
			!in_array('dueamount', $columnNames) ||
			!in_array('duenote', $columnNames) ||
			!in_array('email', $columnNames) ||
			!in_array('grace_period', $columnNames) ||
			!in_array('invoice_no', $columnNames)

		) {
			Session::flash('message', 'Error: Some fields are missing in the sheet');
			return redirect()->back();
		}

		if (
			$columnNames[0] != 'aadhar_number' ||
			$columnNames[1] != 'contact_phone_number' ||
			$columnNames[2] != 'person_name' ||
			$columnNames[3] != 'dob_ddmmyyyy' ||
			$columnNames[4] != 'father_name' ||
			$columnNames[5] != 'mother_name' ||
			$columnNames[6] != 'duedate_ddmmyyyy' ||
			$columnNames[7] != 'dueamount' ||
			$columnNames[8] != 'duenote' ||
			$columnNames[9] != 'email' ||
			$columnNames[10] != 'grace_period' ||
			$columnNames[11] != 'invoice_no'

		) {
			Session::flash('message', 'Error: Format not same as Master Template. Recheck and upload');
			return redirect()->back();
		}

		try {

			$remainingRecords = array();
			$recordsToAllowCount = 0;
			$totalSkippedRecordCount = 0;
			// $remainingCustomer = General::getFreeCustomersDuesLimit(Auth::id());
			$remainingCustomer = CustomerHelper::getRemainingFreeCustomersDuesLimit(Auth::id());

			if ($remainingCustomer >= count($fileToArray[0])) {
				$recordsToAllowCount = count($fileToArray[0]);
			} else {
				$recordsToAllowCount = $remainingCustomer;
			}

			$totalSkippedRecordCount = count($fileToArray[0]) - $recordsToAllowCount;

			Log::debug('remainingCustomer = '.$remainingCustomer);
			Log::debug('totalSkippedRecordCount = '.$totalSkippedRecordCount);

			$skipAll = true;
			$new_student_customers_data = array();
			$existing_customers_data = array();

			$existing_student_customers_count = 0;
			$new_student_customers_count = 0;

			$is_validation_error = false;
			foreach ($fileToArray[0] as $tempKey => $tempValue) {
				$row = (array)$tempValue;

				if(isset($row['email'])){
					$mail=trim($row['email']);
					$row['email'] = str_replace(' ', '', $mail);
				}
				if(isset($row['contact_phone_number'])){
					$contact_phone_number=trim($row['contact_phone_number']);
					$row['contact_phone_number'] = str_replace(' ', '', $contact_phone_number);
				}

				$status = General::validateIndividualBulkUploadData($row, $import->uniqueUrlCode);

				if($status){
					$is_validation_error = true;
				}

				$records = \App\Students::where('person_name', 'LIKE', General::encrypt(strtolower($tempValue['person_name'])))
					->where('contact_phone', '=', General::encrypt($tempValue['contact_phone_number']))->whereNull('deleted_at');
				// if (!Auth::user()->hasRole('admin')) {
				// 	$records = $records->where('added_by', Auth::id());
				// }

				$records = $records->first();

				$person_name = strtolower($tempValue['person_name']);
				$person_pno = $tempValue['contact_phone_number'];

				if (!empty($records) && CustomerHelper::isAlreadyExistingCustomer(Auth::id(), $records->id, 1)) {
					$skipAll = false;
					$fileToArray[0][$tempKey]['skip'] = false;
					// $existing_customers_data[$person_name][$person_pno] = $tempValue['contact_phone_number'];
				} else {
					$new_student_customers_data[$person_name][$person_pno] = $tempValue['contact_phone_number'];

				}
			}

			if ($is_validation_error) {
				return redirect()->route('import-excel.issues', [$import->uniqueUrlCode])->with(['message' => 'No Records are imported due to format errors', 'alert-type' => 'error']);
			}

			// $new_student_customers_count = count($new_student_customers_data);
			$new_student_customers_count = array_sum(array_map("count", $new_student_customers_data));
			Log::debug('new_student_customers_count = '.$new_student_customers_count);

			if ($new_student_customers_count != 0 && $new_student_customers_count > $remainingCustomer) {
				$skipAll = true;
			} else {
				$skipAll = false;
			}

			if ($skipAll) {
				$remainingRecordss = $fileToArray[0];
				$totalSkippedRecordCount = 0;

				$temp = [];
				foreach($remainingRecordss as $temp_key => $temp_value){

					$checkNullArray = array_filter($temp_value);
					if(count($checkNullArray) > 0) {

						$remainingRecords[] = $temp_value;
						if(!in_array($temp_value['contact_phone_number'], $temp)){
							$temp[] = $temp_value['contact_phone_number'];
							$totalSkippedRecordCount++;
						}
					}
				}

				Log::debug('totalSkippedRecordCount loop ='.$totalSkippedRecordCount);

				if ($remainingCustomer <=0) {
					$totalSkippedRecordCount = $new_student_customers_count;
				}

				if ($remainingCustomer > 0) {
					$totalSkippedRecordCount = abs($remainingCustomer - $new_student_customers_count);
				}

				Log::debug('totalSkippedRecordCount'.$totalSkippedRecordCount);

				$SkippedDuesRecord = new SkippedDuesRecord();
				$SkippedDuesRecord->user_id = Auth::user()->id;
				$SkippedDuesRecord->request_data = json_encode($fileToArray[0]);
				$SkippedDuesRecord->total_skipped_record_count = $totalSkippedRecordCount;
				$SkippedDuesRecord->save();

				return view('admin.add-record.skipped-popup-import', compact('SkippedDuesRecord', 'remainingRecords', 'totalSkippedRecordCount'));
			}

			Excel::import($import, request()->file('file'));
			$totalRows = $import->getRowCount();
			$getRemainingRecords = $import->getRemainingRecords();

			/*One code hit transaction Api Call*/
			$duesCheck = StudentDueFees::where('added_by', Auth::id())->where('due_amount', '>=', 500)->get();
			$checkOfferData = [];
			$TotalDueAmt = 0;
			foreach ($duesCheck as $dcKey => $dcVal) {
				$checkOfferData[] = $dcVal;
				$TotalDueAmt += $dcVal->due_amount;
			}
			//echo "total amount---->".$TotalDueAmt;
			//echo "<pre>"; print_r($checkOfferData); die;

			$offerDataCheck = UsersOfferCodes::where('user_id', Auth::id())
				->where('offer_code_status', 1)->where('offer_code_used', 0)
				->first();

			if (!empty($offerDataCheck)) {
				if (count($checkOfferData) >= 2 && $TotalDueAmt >= 3000) {
					$transactionPostData = array("code" => $offerDataCheck->offer_code, "amount" => 0, "category" => "Basic", "transactionId" => Auth::id());
					$response = General::offer_codes_curl($transactionPostData, 'transaction');
					UsersOfferCodes::where('user_id', Auth::id())->update(array('offer_code_used' => 1, "response" => $response));
				}
				/*One code hit transaction Api Call ends here*/
			}

			if ($import->atLeastIssue === true) {
				return redirect()->route('import-excel.issues', [$import->uniqueUrlCode])->with(['message' => $totalRows['Updated'] . ' Record imported and because of format error ' . $totalRows['Skipped'] . ' record skipped', 'alert-type' => 'success']);
			} else {
				return redirect()->back()->with(['message' => $totalRows['Updated'] . ' Record imported and because of format error ' . $totalRows['Skipped'] . ' record skipped', 'alert-type' => 'success']);
			}
			// }
		} catch (\Exception $e) {
			//echo $e->getMessage(); die;
			$errorMsg = date('Y-m-d H:i:s') . "----individual----" . $e->getMessage();
			error_log($errorMsg, 3, storage_path() . '/logs/bulkuploads.log');

			return redirect()->back()->with(['message' => 'Something is wrong', 'alert-type' => 'error']);
		}
	}

	// public function importExcelIssues($uniqueUrlCode)
	// {
	// 	$records = IndividualBulkUploadIssues::where('unique_url_code', General::encrypt($uniqueUrlCode))
	// 		->where('status', 0)
	// 		->where('added_by', Auth::id())
	// 		->orderBy('id', 'ASC')
	// 		->get();
	// 	IndividualBulkUploadIssues::where('unique_url_code', General::encrypt($uniqueUrlCode))
	// 		->where('added_by', Auth::id())
	// 		->where('status', 0)
	// 		->update(['status' => 1]);
	// 	return view('admin.import-excel-issues', compact('records'));
	// }

	public function importExcelIssues($uniqueUrlCode, $userId = "")
	{
		//echo $userId;die();
		//echo $uniqueUrlCode; die;
		$authId = $userId != "" ? ($userId) : (Auth::id());
		$records = IndividualBulkUploadIssues::where('unique_url_code', General::encrypt(strtolower($uniqueUrlCode)))
			->where('status', 0)
			->where('added_by', $authId)
			->orderBy('id', 'ASC')
			->get();
		IndividualBulkUploadIssues::where('unique_url_code', General::encrypt(strtolower($uniqueUrlCode)))
			->where('added_by', $authId)
			->where('status', 0)
			->update(['status' => 1]);
		return view('admin.import-excel-issues', compact('records', 'userId'));
	}

	public function export(Request $request)
	{

		$fromdate = $request->input('fromdate') ?? '0';
		$todate = $request->input('todate') ?? '0';
		$dropDownType = $request->input('dropDownType') ?? '';


		if(($fromdate !=0) && ($todate == 0))
		{
				$todate=Carbon::today()->toDateString();
		}
		$todate=date('Y-m-d',strtotime('+1 day', strtotime($todate)));
		//$date = $request->input('date') ?? '0';
		$loginId = Auth::user()->role_id;
		return Excel::download(new StudentsExport($loginId,'0','studentExport',$fromdate,$todate,$dropDownType,""), 'IndividualCustomers-' . Carbon::now() . '.xlsx');
	}

	public function export_updatePayments(Request $request)
	{
		$fromdate = $request->input('paymentfromdate') ?? '0';
		$todate = $request->input('paymenttodate') ?? '0';
		$dropDownType = $request->input('paymentdropDownType') ?? '';
		if(($fromdate !=0) && ($todate == 0))
		{
				$todate=Carbon::today()->toDateString();
		}
		$file_name='IndividualInvoicePaymentCustomers-';
		$is_customerPayments="";
		if(isset($request->customerpayments))
		{
			$is_customerPayments="2";
			$file_name='IndividualCustomerPayments-';
		}
		$todate=date('Y-m-d',strtotime('+1 day', strtotime($todate)));
		$loginId = Auth::user()->role_id;
		return Excel::download(new StudentsExport($loginId,"1",'studentExport',$fromdate,$todate,$dropDownType,$is_customerPayments), $file_name. Carbon::now() . '.xlsx');
	}


	public function myStudentRecords(Request $request, $studentId, $dueId)
	{ //echo $dueId; die;
		$getCustomId = StudentDueFees::select('external_student_id')->where('student_id','=',$studentId)->where('id','=',$dueId);
	    $getCustomId = $getCustomId->first();
		$checkCustomId = $getCustomId->external_student_id;
		//var_dump(is_null($checkCustomId)); die;

		$User = Auth::user();
		if (!is_null($request->getQueryString()) && ($User->email_verified_at == NULL  || $User->email_sent_at == NULL)) {
			//return redirect('admin/auth/verify');
		}
		$authId = Auth::id();
		$currentDate = Carbon::now();
		$paid_records = StudentPaidFees::where('student_id','=',$studentId)->where('external_student_id', $checkCustomId)->where('due_id',0)->where('added_by',Auth::id())->whereNotNull('payment_options_drop_down')->get();
		// dd($paid_records);
		$settled_records = 0;
		foreach ($paid_records as $key => $value) {
		    	$settled_records = $value->payment_options_drop_down;
		        // $settled_records = explode(',', $settled_records);
		    // dd($settled_records);
		}
		$records = StudentDueFees::with(['profile'])->whereHas('profile', function ($q) use ($request) {
			if (!empty($request->input('student_first_name'))) {

				$q->where('students.person_name', 'LIKE', General::encrypt(strtolower($request->input('student_first_name'))));
			}

			if (!empty($request->input('student_dob'))) {
				$dob = Carbon::createFromFormat('Y-m-d', $request->input('student_dob'));
				$dob =  $dob->format('Y-m-d');
				$dob = General::encrypt($dob);
				$q->where('dob', 'LIKE', $dob);
			}

			if (!empty($request->input('father_first_name'))) {
				$q->where('father_name', '=', General::encrypt(strtolower($request->input('father_first_name'))));
			}
			if (!empty($request->input('mother_first_name'))) {
				$q->where('mother_name', '=', General::encrypt(strtolower($request->input('mother_first_name'))));
			}
			if (!empty($request->input('aadhar_number'))) {
				$q->where('aadhar_number', '=', General::encrypt(str_replace('-', '', $request->input('aadhar_number'))));
			}
			if (!empty($request->input('contact_phone'))) {

				$q->where('students.contact_phone', '=', General::encrypt($request->input('contact_phone')));
			}
		})
			->where('added_by', $authId)
			->where('external_student_id', $checkCustomId)
			->where('student_id',$studentId)
			->whereNull('deleted_at');

		if (!empty($request->input('due_date_period'))) {

			$dueDatePeriod = $request->input('due_date_period');
			if ($dueDatePeriod == 'less than 30days') {
				$records = $records->whereRaw("datediff(CURDATE(),due_date) < 30");
			} elseif ($dueDatePeriod == '30days to 90days') {
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=90 AND datediff(CURDATE(),due_date) >=30 ");
			} elseif ($dueDatePeriod == '91days to 180days') {
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=180 AND datediff(CURDATE(),due_date) >=91 ");
			} elseif ($dueDatePeriod == '181days to 1year') {
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=365 AND datediff(CURDATE(),due_date) >=181 ");
			} elseif ($dueDatePeriod == 'more than 1year') {
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) >365 ");
			}
		}

		$records = $records->withCount([
			'paid AS totalPaid' => function ($query) use ($authId) {
				$query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at')->where('added_by', $authId);
			}
		]);
		//$records = $records->groupBy('due_date');
		$records = $records->orderBy('id', 'DESC')->paginate(50);
		//$records = $records->get();
		if (!empty($request->input('due_amount'))) {
			$records = $records->filter(function ($key, $value) use ($request) {

				$dueAmount = $request->input('due_amount');

				if (is_null($key->totalPaid)) {
					if ($dueAmount == 'less than 1000') {
						return $key->due_amount > 0 && $key->due_amount < 1000;
					} elseif ($dueAmount == '1000 to 5000') {
						return $key->due_amount >= 1000 && $key->due_amount <= 5000;
					} elseif ($dueAmount == '5001 to 10000') {
						return $key->due_amount >= 5001 && $key->due_amount <= 10000;
					} elseif ($dueAmount == '10001 to 25000') {
						return $key->due_amount >= 10001 && $key->due_amount <= 25000;
					} elseif ($dueAmount == '25001 to 50000') {
						return $key->due_amount >= 25001 && $key->due_amount <= 50000;
					} elseif ($dueAmount == 'more than 50000') {
						return $key->due_amount > 50000;
					} else {
						return true;
					}
				} else {
					$remain = $key->due_amount - $key->totalPaid;
					if ($dueAmount == 'less than 1000') {
						return $remain > 0 && $remain < 1000;
					} elseif ($dueAmount == '1000 to 5000') {
						return $remain >= 1000 && $remain <= 5000;
					} elseif ($dueAmount == '5001 to 10000') {
						return $remain >= 5001 && $remain <= 10000;
					} elseif ($dueAmount == '10001 to 25000') {
						return $remain >= 10001 && $remain <= 25000;
					} elseif ($dueAmount == '25001 to 50000') {
						return $remain >= 25001 && $remain <= 50000;
					} elseif ($dueAmount == 'more than 50000') {
						return $remain > 50000;
					} else {
						return true;
					}
				}
			});
		}
		$editDueAmount = 'readonly';
		if (General::checkMemberEligibleToEditDueAmount()) {
				$editDueAmount = "";
			}
		return view('admin.students.students-customer-dues-level', compact('records','editDueAmount','settled_records'));
	}

	public function studentRecords(Request $request)
	{
		$User = Auth::user();
		if (!is_null($request->getQueryString()) && ($User->email_verified_at == NULL  || $User->email_sent_at == NULL)) {
			//return redirect('admin/auth/verify');
		}
		$authId = Auth::id();
		$currentDate = Carbon::now();
		$records = StudentDueFees::with(['profile'])->whereHas('profile', function ($q) use ($request) {
			if (!empty($request->input('student_first_name'))) {

				$q->where('students.person_name', 'LIKE', General::encrypt(strtolower($request->input('student_first_name'))));
			}

			if (!empty($request->input('student_dob'))) {
				$dob = Carbon::createFromFormat('Y-m-d', $request->input('student_dob'));
				$dob =  $dob->format('Y-m-d');
				$dob = General::encrypt($dob);
				$q->where('dob', 'LIKE', $dob);
			}

			if (!empty($request->input('father_first_name'))) {
				$q->where('father_name', '=', General::encrypt(strtolower($request->input('father_first_name'))));
			}
			if (!empty($request->input('mother_first_name'))) {
				$q->where('mother_name', '=', General::encrypt(strtolower($request->input('mother_first_name'))));
			}
			if (!empty($request->input('aadhar_number'))) {
				$q->where('aadhar_number', '=', General::encrypt(str_replace('-', '', $request->input('aadhar_number'))));
			}
			if (!empty($request->input('contact_phone'))) {

				$q->where('students.contact_phone', '=', General::encrypt($request->input('contact_phone')));
			}
		})
			->where('added_by', $authId)
			->whereNull('deleted_at');

		if (!empty($request->input('due_date_period'))) {

			$dueDatePeriod = $request->input('due_date_period');
			if ($dueDatePeriod == 'less than 30days') {
				$records = $records->whereRaw("datediff(CURDATE(),due_date) < 30");
			} elseif ($dueDatePeriod == '30days to 90days') {
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=90 AND datediff(CURDATE(),due_date) >=30 ");
			} elseif ($dueDatePeriod == '91days to 180days') {
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=180 AND datediff(CURDATE(),due_date) >=91 ");
			} elseif ($dueDatePeriod == '181days to 1year') {
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=365 AND datediff(CURDATE(),due_date) >=181 ");
			} elseif ($dueDatePeriod == 'more than 1year') {
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) >365 ");
			}
		}

		$records = $records->withCount([
			'paid AS totalPaid' => function ($query) use ($authId) {
				$query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at')->where('added_by', $authId);
			}
		]);
		//$records = $records->groupBy('due_date');
		$records = $records->orderBy('id', 'DESC')->paginate(50);
		//$records = $records->get();
		if (!empty($request->input('due_amount'))) {
			$records = $records->filter(function ($key, $value) use ($request) {

				$dueAmount = $request->input('due_amount');

				if (is_null($key->totalPaid)) {
					if ($dueAmount == 'less than 1000') {
						return $key->due_amount > 0 && $key->due_amount < 1000;
					} elseif ($dueAmount == '1000 to 5000') {
						return $key->due_amount >= 1000 && $key->due_amount <= 5000;
					} elseif ($dueAmount == '5001 to 10000') {
						return $key->due_amount >= 5001 && $key->due_amount <= 10000;
					} elseif ($dueAmount == '10001 to 25000') {
						return $key->due_amount >= 10001 && $key->due_amount <= 25000;
					} elseif ($dueAmount == '25001 to 50000') {
						return $key->due_amount >= 25001 && $key->due_amount <= 50000;
					} elseif ($dueAmount == 'more than 50000') {
						return $key->due_amount > 50000;
					} else {
						return true;
					}
				} else {
					$remain = $key->due_amount - $key->totalPaid;
					if ($dueAmount == 'less than 1000') {
						return $remain > 0 && $remain < 1000;
					} elseif ($dueAmount == '1000 to 5000') {
						return $remain >= 1000 && $remain <= 5000;
					} elseif ($dueAmount == '5001 to 10000') {
						return $remain >= 5001 && $remain <= 10000;
					} elseif ($dueAmount == '10001 to 25000') {
						return $remain >= 10001 && $remain <= 25000;
					} elseif ($dueAmount == '25001 to 50000') {
						return $remain >= 25001 && $remain <= 50000;
					} elseif ($dueAmount == 'more than 50000') {
						return $remain > 50000;
					} else {
						return true;
					}
				}
			});
		}
		$editDueAmount = 'readonly';
		if (General::checkMemberEligibleToEditDueAmount()) {
				$editDueAmount = "";
			}
		return view('admin.students.my-records', compact('records','editDueAmount'));
	}




	public function studentData($studentId)
	{
		$authId = Auth::id();
		$studentDueData = StudentDueFees::select('student_due_fees.id As dueId', 'student_due_fees.student_id', 'due_amount', 'due_date', 'student_due_fees.created_at As ReportedAt', 'paid_amount', 'paid_date', 'due_note', 'customer_no', 'invoice_no', 'student_due_fees.proof_of_due', 'student_due_fees.grace_period', 'student_due_fees.collection_date')
			->leftJoin('student_paid_fees', 'student_due_fees.student_id', '=', 'student_paid_fees.student_id')

			->where('student_due_fees.student_id', '=', $studentId)
			->whereNull('student_due_fees.deleted_at')
			->where('student_due_fees.added_by', $authId)
			->groupBy('due_date')->get(); //dd($studentDueData);
		//dd($studentDueData);
		$student = Students::where('id', '=', $studentId)->first();
		return view('admin.students.student-data', compact('studentDueData', 'student', 'studentId'));
	}

	public function storeDueAmount($studentId, Request $request)
	{
		$authId = Auth::id();
		$authUserType = Auth::user()->user_type;
		$grace_period = $request->grace_period_hidden;
		$validator = Validator::make($request->all(), [
			//'contact_phone' => 'required|digits:10,10',
			'student_id' => 'required',
			'due_date' => 'required',
			'due_amount' => 'required|numeric|gte:500|lte:100000000',
			//'proof_of_due' => 'mimes:jpeg,jpg,bmp,xls,xlsx,png,pdf,doc,docx,txt',
			'due_note' => 'nullable|string|max:300',
			'collection_date' => 'required',
		]);
		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

		if ($studentId == '') {

			return redirect()->back()->withError('Error: Student id is not given');
		}
		$studentId = $request->student_id;
		$student = Students::where('id', '=', $studentId)->first();

		if (empty($student)) {

			return redirect()->back()->withError('Error: Student record not exists');
		}

		$valuesForStudent = [
			'contact_phone' => $request->input('contact_phone'),
			'updated_at' => Carbon::now(),
		];

		//$student->update($valuesForStudent);
	/*	$proofOfDue = '';
		if (!empty($request->file('proof_of_due'))) {
			$proofOfDue = Storage::disk('public')->put('proof_of_due', $request->file('proof_of_due'));
		}*/

		$proofOfDue = '';
			if (!empty($request->file('proof_of_due'))) {
				$ImgListNames=$request->file('proof_of_due');
				$encrptImgname="";
						foreach($ImgListNames as $Imgname)
						{
							$proofOfDue = Storage::disk('public')->put('proof_of_due', $Imgname);
							$encrptImgname.=$proofOfDue.",";
						}


					$update_proof=str_replace("proof_of_due/","",$encrptImgname);
					$final_updateProof=trim($update_proof,",");
					$proofOfDue="proof_of_due/".$final_updateProof;
			}

		$due_date_formated = Carbon::createFromFormat('d/m/Y', $request->due_date)->toDateTimeString();
		$collection_date_formated = Carbon::createFromFormat('d/m/Y', $request->collection_date)->toDateTimeString();
		// $external_student_id = $request->input('external_student_id');
		$external_student_id = $request->input('custom_id');
		$customStudentId = NULL;
		if(isset($external_student_id)) {
			$customStudentId = $external_student_id;
		}
		$valuesForStudentDueFees = [
			'student_id' => $studentId,
			'due_date' => $due_date_formated,
			'due_amount' => $request->input('due_amount'),
			'due_note' => $request->input('due_note'),
			'created_at' => Carbon::now(),
			'added_by' => $authId,
			'proof_of_due' => $proofOfDue,
			'collection_date' => $collection_date_formated,
			'grace_period' => $grace_period,
			'external_student_id' => $customStudentId
		];

		$studentFee = StudentDueFees::create($valuesForStudentDueFees);
		// echo $studentFee;exit();
		if ($studentFee->id == '') {

			return redirect()->back()->withError('Error: Paid Amount not stored');
		}

		// return redirect()->back()->withMessage('Success: Outstanding Amount stored');
		return redirect()->back()->withMessage('Success: Record Added');
	}

	public function dueDataByDueID(Request $request)
	{	//dd($request->wantsJson());
		$dueId = $request->input('due_id');
		if (empty($dueId)) {
			return Response::json(['error' => true, 'message' => 'Due id can not be null'], 300);
		}

		//return $dueData;
		if ($request->with_html == 'yes') {
			$data = StudentDueFees::with('addedBy', 'profile')->where('id', '=', $dueId)->first();
			$withHtml = View('admin/students/partials/due-data-popup', compact('data'))->render();
			return Response::json(['success' => true, 'data' => $withHtml], 200);
		} else if ($request->with_html == 'total') {
			 $getCustomId = StudentDueFees::select('external_student_id')->where('student_id','=',$studentID)->where('id','=',$dueId);
	   $getCustomId = $getCustomId->first();
		$checkCustomId = isset($getCustomId->external_student_id) ? $getCustomId->external_student_id : NULL;
        $dueAmount = StudentDueFees::select(DB::raw('sum(due_amount) As DueAmount'))->where('student_id','=',$studentID);
		if($added_by){
            $dueAmount = $dueAmount->where('added_by',$added_by);
        }
		if(!empty($checkCustomId)) {
			$dueAmount =  $dueAmount->where('external_student_id','=',$checkCustomId);
		}


         $dueAmount =  $dueAmount->whereNull('deleted_at');
        $dueAmount = $dueAmount->groupBy('external_student_id')->first();
        if(empty($dueAmount)){
            return 0;
        }
       return $dueAmount->DueAmount;
		} else {
			$dueData = StudentDueFees::where('id', '=', $dueId)->first();
			$student_id=$dueData['student_id'];
			$dueDataStudent = Students::where('id', '=', $student_id)->first();
			$paidAmount=StudentPaidFees::where('student_id', '=', $student_id)->where('due_id', '=', $dueId)->select('paid_amount')
            ->groupBy('student_id')->sum('paid_amount');;
			if(empty($paidAmount))
			{
				$paidAmount=0;
			}
			if (!empty($dueData)) {
				$dueDate = date('Y-m-d', strtotime($dueData->due_date));
				return Response::json(['success' => true, 'data' => $dueData, 'due_date' => $dueDate,'personal_data'=>$dueDataStudent,'paid_amount'=>$paidAmount], 200);
			} else {
				return Response::json(['success' => false, 'message' => ''], 200);
			}
		}
	}



	public function editDueAmount($studentId, Request $request)
	{

		$dueId = $request->input('outstanding');
		$studentId = $request->student_id;
		if ($dueId) {
			$validator = Validator::make($request->all(), [ //'contact_phone' => 'required|digits:10,10',
				'due_date' => 'required|date',
				'due_amount' => 'required|numeric|gte:500|lte:100000000',
				//'proof_of_due' => 'mimes:jpeg,jpg,bmp,xls,xlsx,png,pdf,doc,docx,txt',
				'due_note' => 'nullable|string|max:300',
			]);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

			$studentData = Students::where('id', '=', $studentId)->first();
			if (empty($studentData)) {
				return redirect()->back()->withError('Error: Record Not Found');
			}
			/*$studentData->update(['contact_phone'=>$request->input('contact_phone'),
							  'updated_at'=>Carbon::now()]);
			*/

			$dueData = StudentDueFees::where('id', '=', $dueId)->whereNull('deleted_at')->first();
			if (empty($dueData)) {
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

			$proofOfDue = '';
			if (!empty($request->file('proof_of_due'))) {
				$ImgListNames=$request->file('proof_of_due');
				$encrptImgname="";
						foreach($ImgListNames as $Imgname)
						{
							$proofOfDue = Storage::disk('public')->put('proof_of_due', $Imgname);
							$encrptImgname.=$proofOfDue.",";
						}


					$update_proof=str_replace("proof_of_due/","",$encrptImgname);
					$final_updateProof=trim($update_proof,",");
					$proofOfDue=$dueData->proof_of_due.",".$final_updateProof;
				//$proofOfDue = Storage::disk('public')->put('proof_of_due', $request->file('proof_of_due'));
				/*$total_file_list=explode(",",$imglist);
				if(count($total_file_list)>1)
				{
					$update_proof=str_replace($file_name,null,$imglist);
					$final_updateProof=trim($update_proof,",");
					$studentDue->proof_of_due = "proof_of_due/".$final_updateProof;
					Storage::disk('public')->delete("proof_of_due/".$file_name);
				}

				if (!empty($dueData->proof_of_due)) {

					Storage::disk('public')->delete($dueData->proof_of_due);
				}*/
			}
			/*$external_student_id=$request->input('external_student_id');
			if(empty($external_student_id))
			{
				$external_student_id = NULL;
			}*/


			$invoice_no=$request->input('invoice_no');
			if(empty($invoice_no))
			{
				$invoice_no = NULL;
			}
			if (!empty($proofOfDue)) {
				$dueData->update([
					'due_date' => $request->input('due_date'),
					'due_note' => $request->input('due_note'),
					'updated_at' => Carbon::now(),
					'due_amount'=> $request->input('due_amount'),
					'proof_of_due' => $proofOfDue,
					'invoice_no'=>$invoice_no,
					// 'external_student_id'=>$external_student_id,
				]);


			} else {
				$dueData->update([
					'due_date' => $request->input('due_date'),
					'due_note' => $request->input('due_note'),
					'due_amount'=> $request->input('due_amount'),
					'updated_at' => Carbon::now(),
					'invoice_no'=>$invoice_no,
					// 'external_student_id'=>$external_student_id,
				]);
			}

			$studentData ->update([
				'person_name' => $request->input('person_name'),
				'contact_phone' => $request->input('contact_phone'),
				'email'=> $request->input('email'),
				'updated_at' => Carbon::now()
			]);


			return redirect()->back()->withMessage('Success: Record Updated');
		} else {
			return redirect()->back()->withError('Error: Record Not Found');
		}
	}

	public function storePayAmount($studentId, Request $request)
	{
           Log::debug(1);
		$authId = Auth::id();
		$validator = Validator::make($request->all(), [
			'student_id' => 'required',
			'due_amount' => 'numeric',
			'outstanding' => 'required', // due id
			'payment_date' => 'required|date_multi_format:d/m/Y',
			'payment_amount' => 'required|numeric|lte:due_amount|min:1',
		], ['payment_date.date_multi_format' => 'The payment date is not a valid date.']);
		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

		$studentId = $request->student_id;
		try {
			$payment_date = Carbon::createFromFormat('d/m/Y', $request->payment_date)->toDateString();
		} catch (\Exception $e) {
			return redirect()->back()->with(['message' => "Invalid payment date", 'alert-type' => 'error']);
		}
		$student = Students::where('id', '=', $studentId)->first();

		if (empty($student)) {
			return redirect()->back()->with(['message' => "Record not found.", 'alert-type' => 'error']);
		}

		$duesRecord = StudentDueFees::where('id', $request->outstanding)->where('student_id', $studentId)->where('added_by', Auth::id())->whereNull('deleted_at')->first();
		if (empty($duesRecord)) {
			return redirect()->back()->with(['message' => "Due data not found.", 'alert-type' => 'error']);
		}


		$skipCollectionRequest = $request->skipcollectionpayment == 0 ? false : true;
		$skipCollection = $skipCollectionRequest;
		if ($request->skip_payment) {
			if (General::checkMemberEligibleToSkipCollectionPayment()) {
				$skipCollection = true;
			}
		}
		//$duesRecord->collection_date = Carbon::parse($duesRecord->collection_date)->addDays(45);
		if (!$skipCollection) {
			if (!empty($duesRecord->collection_date)) {
				if ($payment_date <= $duesRecord->collection_date) {
					$skipCollection = $skipCollectionRequest;
				}
			} elseif ($payment_date <= Carbon::createFromFormat('Y-m-d H:i:s', $duesRecord->due_date)->toDateString()) {
				$skipCollection = $skipCollectionRequest;
			}
		}
		$customStudentId = NULL;
		if($duesRecord->external_student_id!="") {
			$customStudentId = $duesRecord->external_student_id;
		}
		if ($skipCollection) {
			$valuesForStudentPayFees = [
				'student_id' => $studentId,
				'due_id' => $request->outstanding,
				'paid_date' => $payment_date,
				'paid_amount' => $request->payment_amount,
				'paid_note' => $request->payment_note,
				'created_at' => Carbon::now(),
				'added_by' => $authId,
				'external_student_id' => $customStudentId,
			];

			$studentFee = StudentPaidFees::create($valuesForStudentPayFees);
			if(array_key_exists('send_updatepayment_sms',$request->all())) {
				if(!empty($studentFee)){
					$mobile_number= $student->contact_phone;
					$name= $student->person_name;
					$business_name = Auth::user()->business_name;
					$email = $student->email;
					$amount = $request->payment_amount;
					$message ='We thank you for the payment of INR ' .$amount .' made on '.Carbon::now()->format('d-M-Y') . ' to '.$business_name .'. To view your updated record, click here ' . route('your.reported.dues');
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

			if ($studentFee->id == '') {
				return redirect()->back()->with(['message' => "can not update payment. Please try again.", 'alert-type' => 'error']);
			}

			General::storeAdminNotificationForPayment('Individual', $studentFee->id);
			
			if($duesRecord->balance_due !=0)
			{
				General::Update_Balance_Due($duesRecord->balance_due,$request->payment_amount,"Student",$request->outstanding,$studentId);
			}
			
			
			return redirect()->back()->with(['message' => 'Payment updated successfully', 'alert-type' => 'success']);
		}

		$consent_payment_value_gst_in_perc = setting('admin.consent_payment_value_gst_in_perc') ? (int)setting('admin.consent_payment_value_gst_in_perc') : 0;
		$collectionFee1 = 0;
		$collectionFee = 0;
		$totalGSTValue = 0;
		$totalCollectionValue = 0;

		$collectionFeePerc = HomeHelper::getMyRecordsCollectionFeePercent();

		//1% collection fee
		// $temp = ($tempDuePayment->payment_value * 1)/100;

		//collection fee percentage based on pricing plan
		$temp = ($request->payment_amount * $collectionFeePerc) / 100;

		$collectionFee1 = $collectionFee1 + $temp;
		// $collectionFee = bcdiv($collectionFee,1,2);
		if ($collectionFee1 > 50) {
			$collectionFee = bcdiv($collectionFee1, 1, 2);
		} else {
			$collectionFee = 50;
		}

		//GST
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
			'due_id' => $request->outstanding,
			'postpaid' => Auth::user()->collection_fee_individual == 1 ? 1 : 0,
			'status' => 4,
			'invoice_type_id' => 6
		];

		if($duesRecord->external_student_id!="") {
			$customStudentId = $duesRecord->external_student_id;
		}
		if (Auth::user()->collection_fee_individual == 1) {
			$valuesForStudentPayFees = [
				'student_id' => $studentId,
				'due_id' => $request->outstanding,
				'paid_date' => $payment_date,
				'paid_amount' => $request->payment_amount,
				'paid_note' => $request->payment_note,
				'created_at' => Carbon::now(),
				'added_by' => $authId,
				'external_student_id' => $customStudentId,
			];
			$studentFee = StudentPaidFees::create($valuesForStudentPayFees);
			if ($studentFee->id == '') {
				return redirect()->back()->with(['message' => "can not update payment... Please try again.", 'alert-type' => 'error']);
			}


			$membershipPayment = MembershipPayment::create($valuesForMembershipPayment);

			// $response = app('App\Http\Controllers\HomeController')->postpaid_invoice_sendmail($membershipPayment->id);

			General::storeAdminNotificationForPayment('Individual', $studentFee->id);

			 if(array_key_exists('send_updatepayment_sms',$request->all())) {
						$mobile_number= $student->contact_phone;
						$name= $student->person_name;
						$business_name = Auth::user()->business_name;
						$email = $student->email;
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

				if($duesRecord->balance_due !=0){
					General::Update_Balance_Due($duesRecord->balance_due,$request->payment_amount,"Student",$request->outstanding,$studentId);
					}

					return redirect()->back()->with(['message' => 'Payment updated successfully', 'alert-type' => 'success']);
		}
		DB::beginTransaction();
		try {
			$send_sms_email = 0;
			if(array_key_exists('send_updatepayment_sms',$request->all())) { $send_sms_email = 1; }
			$tempDuePayment = TempDuePayment::create([
				'order_id' => Str::random(40),
				'customer_type' => 'INDIVIDUAL',
				'customer_id' => $duesRecord->student_id,
				'due_id' => $duesRecord->id,
				'payment_value' => $request->payment_amount,
				'created_at' => Carbon::now(),
				'added_by' => Auth::id(),
				'payment_note' => $request->payment_note,
				'payment_date' => $payment_date,
				'send_sms_email' => $send_sms_email,
				'external_student_id' => $customStudentId,
			]);

			// $consent_payment_value_gst_in_perc = setting('admin.consent_payment_value_gst_in_perc') ? (int)setting('admin.consent_payment_value_gst_in_perc') : 0;
			// $collectionFee = 0;
			// $totalGSTValue = 0;
			// $totalCollectionValue = 0;

			// //1% collection fee
			// $temp = ($tempDuePayment->payment_value * 1) / 100;
			// $collectionFee = $collectionFee + $temp;
			// $collectionFee = bcdiv($collectionFee, 1, 2);

			// //GST
			// if ($consent_payment_value_gst_in_perc > 0) {
			// 	$temp = ($collectionFee * $consent_payment_value_gst_in_perc) / 100;
			// 	$totalGSTValue = $totalGSTValue + $temp;
			// 	$totalGSTValue = bcdiv($totalGSTValue, 1, 2);
			// }

			// $totalCollectionValue = $collectionFee + $totalGSTValue;
			// if ($totalCollectionValue < 1) {
			// 	$totalCollectionValue = 1;
			// }
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
			Log::debug($duePayment);
			$membershipPayment1 =  MembershipPayment::where('due_id', $tempDuePayment->due_id)->first();
			if (!empty($membershipPayment1)) {
				$membershipPayment1->delete();
			}
			$membershipPayment = MembershipPayment::create($valuesForMembershipPayment);
			DB::commit();
		} catch (\Exception $e) {
			// DB::rollback();
			return redirect()->back()->with(['message' => "can not create payment process. Please try again.", 'alert-type' => 'error']);
		}

		$userDataToPaytm = User::findOrFail(Auth::user()->id);
		$userDataToPaytm_name = preg_replace('/\s+/', '_', $userDataToPaytm->name);

		$duePayment->pg_type = setting('admin.payment_gateway_type');
		$duePayment->update();
		Log::debug($duePayment);

		if (setting('admin.payment_gateway_type') == 'paytm') {
			Log::debug("paytm");

			$payment = PaytmWallet::with('receive');
			$payment->prepare([
				'order' => $duePayment->order_id,
				'user' => $userDataToPaytm_name,
				'mobile_number' => $userDataToPaytm->mobile_number,
				'email' => $userDataToPaytm->email,
				'amount' => $totalCollectionValue,
				'callback_url' => route('student-due-payment-callback')
			]);
			return $payment->view('admin.payment-submit')->receive();
		} else {
			Log::debug("payu");
			$postData = [
				'amount' => $totalCollectionValue,
				'txnid' => $duePayment->order_id,
				'phone' => $userDataToPaytm->mobile_number,
				'email' => $userDataToPaytm->email,
				'firstname' => preg_replace('/\s+/', '', $userDataToPaytm->name),
				'surl' => route('student-due-payment-callback'),
				'address2' => "my-individual-records/$tempDuePayment->customer_id/$tempDuePayment->due_id"
			];
			//dd($postData);
			Log::debug($postData);
			$payuForm = General::generatePayuForm($postData);

			return view('admin.payment-submit', compact('payuForm'));
		}
	}
	public function duePaymentCallback(Request $request)
	{
		if (setting('admin.payment_gateway_type') == 'paytm') {
			$transaction = PaytmWallet::with('receive');
			try {
				$response = $transaction->response();
			} catch (\Exception $e) {
				return redirect()->route($request['address2'])->with(['message' => "Something went wrong", 'alert-type' => 'error']);
				//return redirect()->route('my-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		} else {
			try {
				$response = General::verifyPayuPayment($request->all());
				if (!$response) {
					return redirect()->route($request['address2'])->with(['message' => "Something went wrong", 'alert-type' => 'error']);
					//return redirect()->route('my-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
				}
			} catch (\Exception $e) {
				return redirect()->route($request['address2'])->with(['message' => "Something went wrong", 'alert-type' => 'error']);
				//return redirect()->route('my-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		}

		//dd($response);
		$duePayment = DuePayment::where('order_id', '=', $response['ORDERID'])
			->where('added_by', Auth::id())
			->first();
		if (empty($duePayment)) {
			return redirect()->route($request['address2'])->with(['message' => "Invalid due payment", 'alert-type' => 'error']);
			//return redirect()->route('my-records')->with(['message' => "Invalid due payment", 'alert-type' => 'error']);
		}

		$tempDuePayment = TempDuePayment::where('order_id', '=', $response['ORDERID'])
			->where('added_by', Auth::id())
			->first();
		if (empty($tempDuePayment)) {
			//return redirect()->route('my-records')->with(['message' => "Invalid due payment", 'alert-type' => 'error']);
			return redirect()->route($request['address2'])->with(['message' => "Invalid due payment", 'alert-type' => 'error']);
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
			$customStudentId = NULL;
			if ($duePayment->status == 4) { // successful payment
				if($tempDuePayment->external_student_id!="") {
					$customStudentId = $tempDuePayment->external_student_id;
				}
				$get_student_due_data = StudentDueFees::where('id', $duePayment->due_id)->first();
				$valuesForStudentPayFees = [
					'student_id' => $tempDuePayment->customer_id,
					'due_id' => $tempDuePayment->due_id,
					'paid_date' => $tempDuePayment->payment_date,
					'paid_amount' => $tempDuePayment->payment_value,
					'paid_note' => $tempDuePayment->payment_note,
					'created_at' => Carbon::now(),
					'added_by' => Auth::id(),
					'external_student_id' => $customStudentId
				];

				$studentFee = StudentPaidFees::create($valuesForStudentPayFees);
				$duePayment->paid_id = $studentFee->id;
				General::storeAdminNotificationForPayment('Individual', $studentFee->id);

				$membershipPayment =  MembershipPayment::where('due_id', $tempDuePayment->due_id)->first();
				if (!empty($membershipPayment)) {
					$response = app('App\Http\Controllers\HomeController')->postpaid_invoice_sendmail($membershipPayment->id);
				}
				if($tempDuePayment->send_sms_email) {
					$mobile_number= $duePayment->individualProfile->contact_phone;
					$name= $duePayment->individualProfile->person_name;
					$business_name = Auth::user()->business_name;
					$email = $duePayment->individualProfile->email;
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
			//return redirect('admin/my-records' . $redirectQueryString)->with(['message' => 'can not store due payment.', 'alert-type' => 'error']);
			return redirect('admin/'.$request['address2'] . $redirectQueryString)->with(['message' => 'can not store due payment.', 'alert-type' => 'error']);
		}

		//return redirect('admin/my-records' . $redirectQueryString)->with(['message' => $message, 'alert-type' => $alertType]);
		return redirect('admin/'.$request['address2'] . $redirectQueryString)->with(['message' => $message, 'alert-type' => $alertType]);
	}

	public function paymentHistory(Request $request)
	{
		$dueId = $request->input('due_id');
		$profileId = $request->input('profileId');
		$custom_id = $request->input('custom_id');
		$settled_records = $request->input('settled_records');
		$studentId = $request->input('studentId');
		if(isset($dueId)){
		  if (empty($dueId)) {
			return Response::json(['error' => true, 'message' => 'Due id can not be null'], 300);
		  }
	    }
	    $paymentHistory = StudentPaidFees::select('id', 'paid_date', 'paid_amount', 'paid_note', 'deleted_at','payment_options_drop_down')->where('added_by',Auth::id())->whereNull('deleted_at')->orderBy('id', 'DESC');
		if(isset($profileId)){
           $paymentHistory = $paymentHistory->where('student_id', $profileId)->where('external_student_id',$custom_id)->get();
		}else {

		$paymentHistory1 = $paymentHistory->where('due_id', $dueId)->get();
		$exisitng_due_ids =[];

		$paid_history = StudentDueFees::where('added_by',Auth::id())->where('external_student_id', $custom_id)->whereNull('deleted_at')->where('id', $dueId);
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
				$paymentHistory2 = StudentPaidFees::select('id', 'paid_date', 'paid_amount', 'paid_note', 'deleted_at','payment_options_drop_down')->where('added_by',Auth::id())->whereNull('deleted_at')->orderBy('id', 'DESC');
				  $paymentHistory2 = $paymentHistory2->where('student_id', $studentId)->where('due_id',0)->where('external_student_id',$custom_id)->whereNotIn('id',$exisitng_due_ids)->get();
			}
		}

        if(isset($paymentHistory2)){
        $paymentHistory=$paymentHistory2->merge($paymentHistory1);

        } else {
        	$paymentHistory=$paymentHistory1;
        }
	  }

		$paymentHistoryData = [];
		if ($paymentHistory->count()) {
			// Log::debug($paymentHistory);
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
			return Response::json(['success' => true, 'noData' => false, 'paymentHistoryData' => $withHtml], 200);
		} else {
			return Response::json(['success' => true, 'message' => '', 'noData' => true], 200);
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
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator);
		}
		$authId = Auth::id();
		//return Response::json(['error' => true,'message'=>'Record not found. Please try again'], 300);
		$paymentId = $request->input('payment_id');

		$paymentHistory = StudentPaidFees::where('id', $paymentId)->whereNull('deleted_at')->where('added_by', $authId)->first();

		if (!empty($paymentHistory)) {

			$paymentHistory->deleted_at = Carbon::now();
			$paymentHistory->delete_note = $request->input('delete_note');
			$paymentHistory->update();
			return redirect()->back()->withMessage('successfully deleted payment history record');
		} else {
			return redirect()->back()->withErrors(['can not find payment history record']);
		}
	}

	public function deleteDue(Request $request)
	{
		$authId = Auth::id();
		$validator = Validator::make($request->all(), [
			'due_id' => 'required',
			//'delete_note' => 'required',
			//'agree_terms' => 'required',
		]);
		if ($validator->fails()) {
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

		$studentDue = StudentDueFees::where('id', $dueId)->whereNull('deleted_at')->where('added_by', $authId)->first();
		if (empty($studentDue)) {
			return redirect()->back()->withErrors(['can not find due record']);
		}
		if (!empty($studentDue->proof_of_due)) {
			Storage::disk('public')->delete($studentDue->proof_of_due);
		}

		$studentDue->deleted_at = Carbon::now();
		$studentDue->delete_note = $deleteNote;
		$studentDue->update();

		//mark as deleted for paid entries of this due
		StudentPaidFees::whereNull('deleted_at')->where('added_by', $authId)->where('due_id', $dueId)->update([
			'deleted_at' => Carbon::now(),
			'delete_note' => $deleteNote
		]);
		return redirect()->back()->withMessage('successfully deleted');
	}
	public function deleteProofOfDue(Request $request)
	{
		$authId = Auth::id();
		$validator = Validator::make($request->all(), [
			'due_id' => 'required',
		]);
		if ($validator->fails()) {
			return Response::json(['error' => true, 'message' => 'Due id can not be null'], 300);
		}

		$dueId = $request->input('due_id');
		$file_name = $request->input('file_name');
		$divId = $request->input('div_id');
		$studentDue = StudentDueFees::where('id', $dueId)->whereNull('deleted_at')->where('added_by', $authId)->first();
		if (empty($studentDue)) {
			return redirect()->back()->withErrors(['can not find due record']);
		}
		$imglist=str_replace("proof_of_due/","",$studentDue->proof_of_due);
		$total_file_list=explode(",",$imglist);
		if(count($total_file_list)>1)
		{
			$update_proof=str_replace($file_name,null,$imglist);
			$final_updateProof=trim($update_proof,",");
			$studentDue->proof_of_due = "proof_of_due/".$final_updateProof;
			//Storage::disk('public')->delete("proof_of_due/".$file_name);
		}else{

			/*if (!empty($studentDue->proof_of_due)) {
					Storage::disk('public')->delete($studentDue->proof_of_due);
				}*/
				$studentDue->proof_of_due = '';
		}
		$studentDue->update();
		return Response::json(['success' => true, 'message' => '','div_id'=>$divId], 200);
	}


	public function editStudent($id)
	{
		$data = Students::where('id', $id)->where('added_by', Auth::id())->whereNull('deleted_at')->first();

		if (!$data) {
			$get_customer_from_mapping = MemberCustomerMapping::where('member_id', Auth::id())
						->where('customer_id', $id)
						->where('customer_type', 1)
						->first();

			if ($get_customer_from_mapping) {
				$data = Students::where('id', $id)->whereNull('deleted_at')->first();
			}
		}
		return view('admin/students/edit-student', compact('data'));
	}

	public function updateStudent(Request $request)
	{
		$customerKeys = array("person_name"=>"Person Name", "email"=>"Email", "contact_phone"=>"Mobile");
		$request->all_values = (array)$request->all_values;
		$requestData = json_decode($request->all_values[0]);
		$requestData=(array)$requestData;
		unset($requestData['added_by'],$requestData['created_at'],$requestData['updated_at'],$requestData['father_name'],$requestData['mother_name'],$requestData['aadhar_number'],$requestData['dob'],$requestData['uniqe_url_individual'],$requestData['custom_student_id'],$requestData['proof_of_due']);
		$request_data=$request->all();
		$result = array_diff($requestData, $request_data);
		 if(!empty($result)){
			 $keys=array_keys($result);
			  foreach ($keys as $key => $value) {
			 	 $names[] = $customerKeys[$value];
			}
		    General::storeAdminNotificationForCustomerProfile($names,$requestData);
        }
		$request->merge(['aadhar_number' => str_replace('-', '', $request->aadhar_number)]);
		$request->merge(['aadhar_number' => str_replace('_', '', $request->aadhar_number)]);
		$name_max_character= General::maxlength('name');
		$rule = [
			'id' => 'required',
			'aadhar_number' => 'required_without:contact_phone,person_name',
			'dob' => 'nullable|date|before_or_equal:today',
			'father_name' => 'nullable|max:'.$name_max_character.'|regex:/^[\pL\s]+$/u',
			'mother_name' => 'nullable|max:'.$name_max_character.'|regex:/^[\pL\s]+$/u',
		];
		if (!empty($request->aadhar_number)) {
			$rule['contact_phone'] = 'nullable|regex:/^([0-9\+\(\)]*)$/|min:10|max:13';
			$rule['person_name'] = 'nullable|max:'.$name_max_character.'|regex:/^[\pL\s]+$/u';
		} else {
			$rule['contact_phone'] = 'required_without:aadhar_number|regex:/^([0-9\+\(\)]*)$/|min:10|max:13';
			$rule['person_name'] = 'required_without:aadhar_number|max:'.$name_max_character.'|regex:/^[\pL\s]+$/u';
		}

		$validator = Validator::make(
			$request->all(),
			$rule,
			[
				'person_name.regex' => 'The :attribute may only contain letters and space.',
				'father_name.regex' => 'The :attribute may only contain letters and space.',
				'mother_name.regex' => 'The :attribute may only contain letters and space.',
			]
		);

		if ($validator->fails()) {
			return redirect('admin/my-records' . $request->input('redirectQueryString'))->withErrors($validator)->withInput();
		}
		$id = $request->input('id');
		$aadhar_number = $request->input('aadhar_number');
		$contact_phone = $request->input('contact_phone');
		$person_name = $request->input('person_name');
		$dob = $request->input('dob');
		$father_name = $request->input('father_name');
		$mother_name = $request->input('mother_name');
		$email = $request->email;


		$data = Students::where('id', $id)->where('added_by', Auth::id())->whereNull('deleted_at')->first();

		if (!$data) {
			$get_customer_from_mapping = MemberCustomerMapping::where('member_id', Auth::id())
						->where('customer_id', $id)
						->where('customer_type', 1)
						->first();

			if ($get_customer_from_mapping) {
				$data = Students::where('id', $id)->whereNull('deleted_at')->first();
			}
		}

		if (empty($data)) {
			return redirect('admin/my-records' . $request->input('redirectQueryString'))->withErrors(['No Record found']);
		}

		if (!empty($aadhar_number)) {
			$alreadyExists = Students::where('id', '!=', $id)->where('aadhar_number', '=', General::encrypt($aadhar_number))->whereNull('deleted_at')->first();
			if (!empty($alreadyExists)) {
				return redirect('admin/my-records' . $request->input('redirectQueryString'))->withErrors(['Record with this Aadhar number is already exists']);
			}
			$data->aadhar_number = $aadhar_number;
			$data->contact_phone = $contact_phone;
			$data->person_name = $person_name;
			$data->father_name = $father_name;
			$data->mother_name = $mother_name;
			$data->dob = $dob;
			$data->email = $email;
			$data->updated_at = Carbon::now();
			$data->update();
		} else {
			$alreadyExists = Students::where('id', '!=', $id)->where('contact_phone', '=', General::encrypt($contact_phone))->where('person_name', '=', General::encrypt($person_name))->whereNull('deleted_at')->first();
			if (!empty($alreadyExists)) {
				return redirect('admin/my-records' . $request->input('redirectQueryString'))->withErrors(['Record with this contact phone number and person name is already exists']);
			}
			$data->aadhar_number = $aadhar_number;
			$data->contact_phone = $contact_phone;
			$data->person_name = $person_name;
			$data->father_name = $father_name;
			$data->mother_name = $mother_name;
			$data->dob = $dob;
			$data->email = $email;
			$data->updated_at = Carbon::now();
			$data->update();
		}
		return redirect('admin/my-records' . $request->input('redirectQueryString'))->with('message', 'Successfully updated');
	}

	public function studentRecordsForSms(Request $request)
	{
		$User = Auth::user();
		if (!is_null($request->getQueryString()) && ($User->email_verified_at == NULL  || $User->email_sent_at == NULL)) {
			//return redirect('admin/auth/verify');
		}
		$authId = Auth::id();
		$currentDate = Carbon::now();

		$records = StudentDueFees::with(['profile'])->whereHas('profile', function ($q) use ($request) {
			$q->where('contact_phone', '!=', '');
		})
			->where('added_by', $authId)
			->whereNull('deleted_at');

		if (!empty($request->input('due_date_period'))) {

			$dueDatePeriod = $request->input('due_date_period');
			if ($dueDatePeriod == 'less than 30days') {
				$records = $records->whereRaw("datediff(CURDATE(),due_date) < 30");
			} elseif ($dueDatePeriod == '30days to 90days') {
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=90 AND datediff(CURDATE(),due_date) >=30 ");
			} elseif ($dueDatePeriod == '91days to 180days') {
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=180 AND datediff(CURDATE(),due_date) >=91 ");
			} elseif ($dueDatePeriod == '181days to 1year') {
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=365 AND datediff(CURDATE(),due_date) >=181 ");
			} elseif ($dueDatePeriod == 'more than 1year') {
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) >365 ");
			}
		}

		$records = $records->withCount([
			'paid AS totalPaid' => function ($query) use ($authId) {
				$query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at')->where('added_by', $authId);
			}
		]);
		//$records = $records->groupBy('due_date');
		$records = $records->orderBy('id', 'DESC');
		$records = $records->get();
		if (!empty($request->input('due_amount'))) {
			$records = $records->filter(function ($key, $value) use ($request) {

				$dueAmount = $request->input('due_amount');

				if (is_null($key->totalPaid)) {
					if ($dueAmount == 'less than 1000') {
						return $key->due_amount > 0 && $key->due_amount < 1000;
					} elseif ($dueAmount == '1000 to 5000') {
						return $key->due_amount >= 1000 && $key->due_amount <= 5000;
					} elseif ($dueAmount == '5001 to 10000') {
						return $key->due_amount >= 5001 && $key->due_amount <= 10000;
					} elseif ($dueAmount == '10001 to 25000') {
						return $key->due_amount >= 10001 && $key->due_amount <= 25000;
					} elseif ($dueAmount == '25001 to 50000') {
						return $key->due_amount >= 25001 && $key->due_amount <= 50000;
					} elseif ($dueAmount == 'more than 50000') {
						return $key->due_amount > 50000;
					} else {
						return true;
					}
				} else {
					$remain = $key->due_amount - $key->totalPaid;
					if ($dueAmount == 'less than 1000') {
						return $remain > 0 && $remain < 1000;
					} elseif ($dueAmount == '1000 to 5000') {
						return $remain >= 1000 && $remain <= 5000;
					} elseif ($dueAmount == '5001 to 10000') {
						return $remain >= 5001 && $remain <= 10000;
					} elseif ($dueAmount == '10001 to 25000') {
						return $remain >= 10001 && $remain <= 25000;
					} elseif ($dueAmount == '25001 to 50000') {
						return $remain >= 25001 && $remain <= 50000;
					} elseif ($dueAmount == 'more than 50000') {
						return $remain > 50000;
					} else {
						return true;
					}
				}
			});
		}
		$records = $records->filter(function ($key, $value) {
			if (is_null($key->totalPaid)) {
				return $key->due_amount > 0;
			} else {
				$remain = $key->due_amount - $key->totalPaid;
				return $remain > 0;
			}
		});
		$records = $records->customPaginate(50);
		$records = $records->appends(request()->query());
		$authUser = Auth::user();
		$smsTemplates = \Config::get('sms_templates');
		return view('admin.students.send-sms', compact('records', 'smsTemplates'));
	}


	public function studentRecordsSendSms(Request $request)
	{
		$AuthId = Auth::id();
		$validator = Validator::make(
			$request->all(),
			[
				'ids' => 'required',
				'template_id' => 'required',
				'within_date' => 'nullable|date',
			],
			[
				'ids.required' => 'Select records to send sms',
				'template_id.required' => 'Template is required',
			]
		);

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

		$ids = explode(",", $request->ids);

		$checkMySmsLimit = General::checkSmsDailyLimit($AuthId);

		$limitMessage = '';
		if (!$checkMySmsLimit['daily_available']) {
			$limitMessage = 'can not send sms, your daily sms Limit is over';
		} elseif (!$checkMySmsLimit['weekly_available']) {
			$limitMessage = 'can not send sms, your weekly sms Limit is over';
		} elseif (!$checkMySmsLimit['monthly_available']) {
			$limitMessage = 'can not send sms, your monthly sms Limit is over';
		}
		if (!empty($limitMessage)) {
			return redirect()->back()->with(['message' => $limitMessage, 'alert-type' => 'error']);
		}

		$studentList = StudentDueFees::with(['profile'])->whereHas('profile', function ($q) {
			$q->whereNotNull('contact_phone')->where('contact_phone', '!=', '');
		})->whereIn('id', $ids)->where('added_by', $AuthId)->get();

		if (!$studentList->count()) {
			return redirect()->back()->with(['message' => "can not send sms ", 'alrert-type' => 'erro']);
		}

		$template_id = $request->template_id;
		$message = \Config::get('sms_templates.' . $template_id . '.text');
		if (empty($message)) {
			return redirect()->back()->with(['message' => 'can not find template', 'alert-type' => 'error']);
		}
		$authUser = Auth::user();

		$withinDate = $request->within_date;
		$sent = true;
		$smsService = new SmsService();
		foreach ($studentList as $data) {
			$checkMySmsLimit = General::checkSmsDailyLimit($AuthId);

			$limitMessage = '';
			if (!$checkMySmsLimit['daily_available']) {
				$limitMessage = 'only some sms are sent, your daily sms Limit is over';
			} elseif (!$checkMySmsLimit['weekly_available']) {
				$limitMessage = 'only some sms are sent, your weekly sms Limit is over';
			} elseif (!$checkMySmsLimit['monthly_available']) {
				$limitMessage = 'only some sms are sent, your monthly sms Limit is over';
			}
			if (!empty($limitMessage)) {
				return redirect()->back()->with(['message' => $limitMessage, 'alert-type' => 'error']);
				break;
			}

			$message = General::replaceTextInSmsTemplate($template_id, 'INDIVIDUAL', $authUser, $withinDate, '', $data);
			$message = strip_tags($message);
			/*$smsResponse = $smsService->sendSms($data->profile->contact_phone,$message);
	   		if($smsResponse['fail_to_send']){
	   			$sent = false;
	   		}*/
			$insert = [
				'contact_phone' => $data->profile->contact_phone,
				'customer_id' => $data->profile->id,
				'due_id' => $data->id,
				'customer_type' => 'Individual',
				'created_at' => Carbon::now(),
				'added_by' => $AuthId,
				'message' => $message,
			];
			$insert['status'] = 0;
			/*if($smsResponse['sent']==1){
			   	$insert['status'] = 1;
	        }else{
	        	$insert['status'] = 2;
	        }*/
			DuesSmsLog::create($insert);
		}
		if (!$sent) {
			return redirect()->back()->withInput()->with(['message' => "can not send sms to some phones. Server unavailable.", 'alert-type' => 'error']);
		}
		return redirect()->back()->with(['message' => "SMS sent for admin approval.", 'alert-type' => 'success']);
	}


	public function studentRecordsSentSms(Request $request)
	{
		$AuthId = Auth::id();
		$records = DuesSmsLog::with('customer')->where('customer_type', '=', 'Individual')->where('added_by', $AuthId)->orderBy('created_at', 'DESC')->paginate(50);
		return view('admin.students.sent-sms', compact('records'));
	}

	public function individualReport(Request $request)
	{

		$dataList = Collection::make();
		$reportForYear = setting('admin.generate_report_from_consent_for_last_year') ? (int)setting('admin.generate_report_from_consent_for_last_year') : 0;


		$consent_payment_successful_valid_for_in_year = setting('admin.consent_payment_successful_valid_for_in_year') ? (int)setting('admin.consent_payment_successful_valid_for_in_year') : 7;

		$currentTime = Carbon::now();
		$beforeDateTime = Carbon::now()->subYear($consent_payment_successful_valid_for_in_year);

		if (!empty($request->cp_id)) {
			$consentPayment = ConsentPayment::where('id', $request->cp_id)
				->where('status', 4)
				->where('customer_type', '=', 'INDIVIDUAL')
				->where('added_by', Auth::id())
				->where('updated_at', '>=', $beforeDateTime)
				->first();
			if (empty($consentPayment)) {
				return redirect()->back()->with(['message' => "something went wrong.", 'alert-type' => 'error']);
			}
			$dataList = ConsentRequest::with('detail')->where('id', $consentPayment->consent_id)
				->where('added_by', Auth::id())
				->where('status', 3)
				->where('customer_type', '=', 'INDIVIDUAL')
				->get();
		} else {
			// return redirect()->back()->with(['message' => "something went wrong.", 'alert-type' => 'error']);
			if ($reportForYear > 0) {
				$previousYears = Carbon::now()->subYear($reportForYear);
				$dataList = ConsentRequest::with('detail')
					->where('added_by', Auth::id())
					->where('status', 3)
					->where('id',  $request->c_id)
					// ->where('created_at', '>=', $previousYears)
					// ->where('customer_type', '=', 'INDIVIDUAL')
					->get();
			}

			$consentPayment = new ConsentPayment;
			$consentPayment->consent_id = $request->c_id;
		}
		$records = Collection::make();
		// dd($dataList->toArray());
		if ($dataList->count()) {
			//dd($dataList);
			$individualIds = [];
			foreach ($dataList as $data) {
				/*$studentDueFeedsIds = [];
				foreach ($data->detail as $d) {
					$studentDueFeedsIds[] = $d->due_id;
				}
				$studentIdArray = StudentDueFees::whereIn('id',$studentDueFeedsIds)->get()->pluck('student_id');
				foreach ($studentIdArray as $studentIdAr) {
					if(!in_array($studentIdAr,$individualIds)){
							$individualIds[] = $studentIdAr;
						}
				}*/
				$student = Students::with('dues')->whereHas('dues', function ($q) {
					$q->whereNull('deleted_at');
				})->where('contact_phone', General::encrypt($data->contact_phone));
				if (!empty($data->person_name)) {
					//$student = $student->where('person_name', General::encrypt($data->person_name));
				}
				$student = $student->whereNull('deleted_at')->get();

				if ($student->count()) {
					foreach ($student as $s) {
						if (!in_array($s->id, $individualIds)) {
							$individualIds[] = $s->id;
						}
					}
				}
			}
			// dd($individualIds);
			// get individual detail from ids
			// $records = Students::whereIn('id', $individualIds)->get();
			$records = Students::with(['dues', 'dues.paid', 'dues.dispute'])->whereIn('id', $individualIds)->get();

			// dd($records->toArray());
			foreach ($records as $record) {
				//total due
				// ketli vkht report thyu e account ma aavse...
				$totalMemberReported = StudentDueFees::select('id')->where('student_id', $record->id)->whereNull('deleted_at')->groupBy('added_by')->get();
				$record->summary_totalMemberReported = $totalMemberReported->count();

				//total due reported
				$totalDueReported = StudentDueFees::where('student_id', $record->id)->whereNull('deleted_at')->sum('due_amount');
				$record->summary_totalDueReported = $totalDueReported;

				$totalDisputeCount = Dispute::where('customer_id', $record->id)->where('customer_type', '=', 'INDIVIDUAL')->get();
				$record->totalDispute = $totalDisputeCount->count();
				//
				/*if($dueDatePeriod=='less than 30days'){
					$records = $records->whereRaw("datediff(CURDATE(),due_date) < 30");
				}elseif($dueDatePeriod=='30days to 90days'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=90 AND datediff(CURDATE(),due_date) >=30 ");

				}elseif($dueDatePeriod=='91days to 180days'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=180 AND datediff(CURDATE(),due_date) >=91 ");
				}elseif($dueDatePeriod=='181days to 1year'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=365 AND datediff(CURDATE(),due_date) >=181 ");
				}elseif($dueDatePeriod=='more than 1year'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) >365 ");
				}*/

				//overDueStatus
				//0-29
				$overDueStatusCount = StudentDueFees::whereRaw("datediff(CURDATE(),due_date) < 30")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus0To29Days = $overDueStatusCount;

				//0-89
				$overDueStatusCount = StudentDueFees::whereRaw("datediff(CURDATE(),due_date) < 90")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus0To89Days = $overDueStatusCount;

				//30 to 59 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=59 AND datediff(CURDATE(),due_date) >=30 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus30To59Days = $overDueStatusCount;

				//60 to 89 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=89 AND datediff(CURDATE(),due_date) >=60 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus60To89Days = $overDueStatusCount;

				//90 to 119 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=119 AND datediff(CURDATE(),due_date) >=90 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus90To119Days = $overDueStatusCount;

				//120 to 149 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=149 AND datediff(CURDATE(),due_date) >=120 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus120To149Days = $overDueStatusCount;

				//150 to 179 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=179 AND datediff(CURDATE(),due_date) >=90 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus90To179Days = $overDueStatusCount;


				//150 to 179 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=179 AND datediff(CURDATE(),due_date) >=150 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus150To179Days = $overDueStatusCount;

				//180plus
				$overDueStatusCount = StudentDueFees::whereRaw("datediff(CURDATE(),due_date) >=180 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus180PlusDays = $overDueStatusCount;


				/* account detail */
				$accountDetails = StudentDueFees::with(['addedBy', 'profile'])->whereHas('addedBy')->whereHas('profile')->where('student_id', $record->id)->whereNull('deleted_at')->get();
				//dd($accountDetails);
				$record->accountDetails = $accountDetails;
			}
		}
		$dateTime = Carbon::now()->format('d-m-Y H:i');
		$cp_id = $request->cp_id;
		$c_id = $request->c_id;

		// dd($dataList->toArray());
		// dd($accountDetails->toArray());
		// dd($consentPayment->contact_phone);
		// dd($records->toArray());

		// $studentRecord = Students::where('contact_phone', General::encrypt($consentPayment->contact_phone))->first()->toArray();
		if(!empty($consentPayment->contact_phone))
		{
			$studentRecord = Students::where('contact_phone', General::encrypt($consentPayment->contact_phone))->first()->toArray();
		}else{
			$studentRecord['dob']='';
		}
		// $identityType = [
		// 	'AADHAR' => 'M',
		// 	'PAN' => 'T',
		// 	'PASSPORT' => 'P',
		// 	'VOTER' => 'V',
		// 	'DriverLicense' => 'D',
		// 	'RationCard' => 'R',
		// ];
		$identityType = [
			// 'AADHAR' => 'M',
			1 => 'T',
			3 => 'P',
			2 => 'V',
			4 => 'D',
			// 'RationCard' => 'R',
		];

		$consentRequest = $dataList->toArray();

		// actual record start
		$user['name'] = isset($consentRequest[0]) ? $consentRequest[0]['person_name'] : '';
		$user['number'] = isset($consentRequest[0]) ? $consentRequest[0]['contact_phone'] : '';
		$user['gender'] = '';
		$user['id_value'] = isset($consentRequest[0]) && $consentRequest[0] != NULL ? General::decrypt($consentRequest[0]['idvalue']) : '';
		$user['id_type'] = isset($consentRequest[0]) ? (isset($identityType[$consentRequest[0]['idtype']]) ? $identityType[$consentRequest[0]['idtype']] : 'O') : '';
		if(isset($studentRecord['dob']))
		{
			$dateOfBirth=$studentRecord['dob'];
			$dateOfBirth_new=date('d-m-Y', strtotime($dateOfBirth));
			$user['dob'] = $dateOfBirth_new;
		}
		else
		{
			$user['dob'] = 'Not Reported';
		}
		// actual record end

		$user['recordent'] = [
			'total_members' => count($records),
			'total_dues_unpaid' => 0,
			'total_dues_paid' => 0,
			'total_dues' => 0,
			'summary_overDueStatus0To89Days' => 0,
			'summary_overDueStatus90To179Days' => 0,
			'summary_overDueStatus180PlusDays' => 0
		];

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

		$user['recordent']['total_dues_unpaid'] = $user['recordent']['total_dues_unpaid'] - $user['recordent']['total_dues_paid'];

		if (isset($consentRequest[0]) && $consentRequest[0]['report'] == 2) {
			$api = ConsentAPIResponse::where('consent_request_id', $consentPayment->consent_id)->first();
			if (empty($api)) {
				$result = $this->getDataFromConsentApi($user);
			} else {
				$result = json_decode(General::decrypt($api->response), true);
			}

			Log::channel('consent')->debug('----------------------- START -----------------------------');
			Log::channel('consent')->debug('REQUEST_DATA: ' . json_encode($user));
			Log::channel('consent')->debug('RESPONSE_DATA: ' . json_encode($result));
			Log::channel('consent')->debug('REQUEST_PARAMS: ' . json_encode($request->all()));
			Log::channel('consent')->debug('----------------------- END --------------------------');
		} else {
			$result = [];
		}
		// dd($result, $user, General::encrypt('AJJPS0032N'), $consentRequest[0]);
		// if any error occured from consent api side then this if statement will execute and thow an error.
		$membership_payments =MembershipPayment::where('consent_id',$consentPayment->consent_id)->first();

		if (isset($result['Error']) || isset($result['CCRResponse']) && isset($result['CCRResponse']['CIRReportDataLst']) && isset($result['CCRResponse']['CIRReportDataLst'][0]) && isset($result['CCRResponse']['CIRReportDataLst'][0]['Error'])) {
			if (!empty($result)) {
				$msg = isset($result['CCRResponse']['CIRReportDataLst'][0]['Error']['ErrorDesc']) ? $result['CCRResponse']['CIRReportDataLst'][0]['Error']['ErrorDesc'] : (isset($result['Error']['ErrorDesc']) ? $result['Error']['ErrorDesc'] : '');

				$customer_type = "Retail";

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
											->where('customer_type', '=', 'INDIVIDUAL')
											->where('status', 4)
											->first();

				if (!empty($consent_payment_record)){

					if (Auth::user()->reports_individual == 1) {
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
				} else {
					if (Auth::user()->reports_individual == 1) {
						$consentPayment->status = 5;
						$consentPayment->updated_at = Carbon::now();
						$consentPayment->created_at =
						$consentPayment->save();
					}
				}

				return view('admin.business.equifax-b2b.india-b2bnohitresponse.index',compact('records','membership_payments','user','customer_type'));
			}

			$api = ConsentAPIResponse::where('consent_request_id', $consentPayment->consent_id)->first();
			if (empty($api)) {
				$api = new ConsentAPIResponse();
				$api->consent_request_id = $consentPayment->consent_id;
			}

			$api->response = json_encode($result);
			$api->request_data = General::encrypt(json_encode($user));
			$api->ip_address = request()->ip();
			$api->status = 0;
			$api->request_type = "INDIVIDUAL";
			$api->save();

			return view('admin.students.report.index', compact('consentRequest', 'records', 'dateTime', 'cp_id', 'c_id', 'user'));
		} else {
			$api = ConsentAPIResponse::where('consent_request_id', $consentPayment->consent_id)->first();
			if (empty($api)) {
				$api = new ConsentAPIResponse();
				$api->consent_request_id = $consentPayment->consent_id;
			}

			$api->response = General::encrypt(json_encode($result));
			$api->request_data = General::encrypt(json_encode($user));
			$api->ip_address = request()->ip();
			$api->status = 1;
			$api->request_type = "INDIVIDUAL";
			$api->save();
		}

		// $api = ConsentAPIResponse::where('id', 1000)->first();
		// $response = json_decode($api->response, true);

		$api = ConsentAPIResponse::where('consent_request_id', $consentPayment->consent_id)->first();
		// dd(General::decrypt($api->response));
		$response = json_decode(General::decrypt($api->response), true);
		// dd($response);

		// dd(User::where('user_type','INDIVIDUAL')->first()->toArray());

		// PAYMENT HISTORY COUNT LOGIC START
		$totalCreditLimit = 0;
		$totalCreditCardBalance = 0;
		$limit = 0;
		$totalPayments = 0;
		$totalSuccessPayment = 0;
		$statusArray = ['000', '*', 'STD', 'NEW', 'CLSD', 'OPEN', 'RES']; // status to be checked
		$openClosedAccountsArr = [
			'loan_accounts' => ['open' => 0, 'closed' => 0],
			'credit_card_accounts' => ['open' => 0, 'closed' => 0]
		];

		// dd($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails']);
		// dd($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['Enquiries']);

		if (isset($response['CCRResponse']) && isset($response['CCRResponse']['CIRReportDataLst']) && isset($response['CCRResponse']['CIRReportDataLst'][0])) {
			$dataOpened = date('Y-m-d');
			foreach ($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'] as $key => $value) {
				if (isset($value['AccountType'])) {
					if ($value['AccountType'] == 'Credit Card') {
						if (isset($value['Open']) && ($value['Open'] == 'Yes' || $value['Open'] == 'yes')) {
							$openClosedAccountsArr['credit_card_accounts']['open']++;

							if (isset($value['CreditLimit'])) {
								$totalCreditLimit += (float) $value['CreditLimit'];
							} else if (isset($value['HighCredit'])) {
								$totalCreditLimit += (float) $value['HighCredit'];
							}

							if (isset($value['Balance'])) {
								$totalCreditCardBalance += (float) $value['Balance'];
							}
						} else {
							$openClosedAccountsArr['credit_card_accounts']['closed']++;
						}
					} else {
						if (isset($value['Open']) && ($value['Open'] == 'Yes' || $value['Open'] == 'yes')) {
							$openClosedAccountsArr['loan_accounts']['open']++;
						} else {
							$openClosedAccountsArr['loan_accounts']['closed']++;
						}
					}
				} else {
					$response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'][$key]['AccountType'] = 'Other';
				}

				if (isset($value['DateOpened'])) {
					$date_now = new \DateTime($dataOpened);
					$date2    = new \DateTime($value['DateOpened']);
					if ($date_now > $date2) {
						$dataOpened = $value['DateOpened'];
					}
				}
				if (isset($value['History48Months'])) {
					foreach ($value['History48Months'] as $key_history => $value_history) {
						$totalPayments++;
						if (in_array($value_history['PaymentStatus'], $statusArray) && in_array($value_history['AssetClassificationStatus'], $statusArray)) {
							$totalSuccessPayment++;
						}
					}
				}
			}
		}
		// PAYMENT HISTORY COUNT LOGIC END
		if (isset($dataOpened)) {
			$date1 = strtotime($dataOpened);
			$date2 = strtotime(date('Y-m-d'));
			$diff = abs($date2 - $date1);
		} else {
			$diff = '';
		}

		// dd($totalCreditCardBalance, $totalCreditLimit);

		if ($totalCreditLimit > 0) {
			$limit = round(number_format((($totalCreditLimit - $totalCreditCardBalance) * 100) / $totalCreditLimit, 2));
		} else {
			$limit = 100;
		}

		// start sorting of account
		if (isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'])) {
			$RetailAccountDetails = $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'];
			uasort($RetailAccountDetails, function ($a, $b) {
				$a['DateOpened'] = isset($a['DateOpened']) ? $a['DateOpened'] : date('Y-m-d');
				$b['DateOpened'] = isset($b['DateOpened']) ? $b['DateOpened'] : date('Y-m-d');
				return strcmp($a['DateOpened'], $b['DateOpened']);
			});
		} else {
			$RetailAccountDetails = array();
		}
		// end sorting of account

		// start sorting of account
		if (isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['AddressInfo'])) {
			$AddressInfo = $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['AddressInfo'];
			usort($AddressInfo, function ($a, $b) {
				$a['ReportedDate'] = isset($b['ReportedDate']) ? $b['ReportedDate'] : date('Y-m-d');
				$b['ReportedDate'] = isset($a['ReportedDate']) ? $a['ReportedDate'] : date('Y-m-d');
				return strcmp($a['ReportedDate'], $b['ReportedDate']);
			});
		} else {
			$AddressInfo = array();
		}
		// end sorting of account

		//start get mobile and home number
		$number = array();
		$number['mobile'] = '';
		$number['home'] = '';
		if (isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PhoneInfo']) && !empty($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PhoneInfo'])) {
			foreach ($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PhoneInfo'] as $p_key => $p_value) {
				if ($p_value['typeCode'] == "H" && empty($number['home'])) {
					$number['home'] = $p_value['Number'];
				}
				if ((isset($user['number']) && $user['number'] == $p_value['Number']) || ($p_value['typeCode'] == "M" && empty($number['mobile']))) {
					$number['mobile'] = $p_value['Number'];
				}
				if ($p_value['typeCode'] == "T" && empty($number['workphone'])) {
					$number['workphone'] = $p_value['Number'];
				}
			}
		}
		//end get mobile and home number

		return view('admin.students.report.index', compact('consentRequest', 'openClosedAccountsArr', 'number', 'AddressInfo', 'RetailAccountDetails', 'limit', 'diff', 'totalPayments', 'totalSuccessPayment', 'records', 'dateTime', 'cp_id', 'c_id', 'response', 'user'));
	}

	public function getDataFromConsentApi($user)
	{
		$data = $this->getRequestParams($user);

		$curl = curl_init();

		curl_setopt_array($curl, array(
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
		$data = [
			'RequestHeader' => [
				"CustomerId" => config('app.customer_id'),
				"UserId" => config('app.user_id'),
				"Password" => config('app.equifax_password'),
				"MemberNumber" => config('app.member_number'),
				"SecurityCode" => config('app.security_code'),
				"ProductCode" => [
					config('app.product_code')
				]
				// "CustomerId" => env("EQUIFAX_CUSTOMER_ID"),
				// "UserId" => env("EQUIFAX_USER_ID"),
				// "Password" => env("EQUIFAX_PASSWORD"),
				// "MemberNumber" => env("EQUIFAX_MEMBER_NUMBER"),
				// "SecurityCode" => env("EQUIFAX_SECURITY_CODE"),
				// "ProductCode" => [
				// 	env("EQUIFAX_PRODUCT_CODE")
				// ]
			],
			'RequestBody' => [
				"InquiryPurpose" => "16",
				"TransactionAmount" => "0",
				"FirstName" => $user['name'],
				"MiddleName" => "",
				"LastName" => "",
				"InquiryPhones" => [
					[
						"seq" => "1",
						"Number" => $user['number'],
						"PhoneType" => [
							"M"
						]
					]
				],
				"IDDetails" => [
					$idDetails
				],
				"DOB" => $user['dob'],
				// "Gender" => $user['gender']
			],
			"Score" => [
				[
					"Type" => "ERS",
					"Version" => "3.1"
				]
			]
		];

		return $data;
	}

	public function individualReportDowloadPdf(Request $request)
	{

		$dataList = Collection::make();
		$reportForYear = setting('admin.generate_report_from_consent_for_last_year') ? (int)setting('admin.generate_report_from_consent_for_last_year') : 0;


		$consent_payment_successful_valid_for_in_year = setting('admin.consent_payment_successful_valid_for_in_year') ? (int)setting('admin.consent_payment_successful_valid_for_in_year') : 7;

		$currentTime = Carbon::now();
		$beforeDateTime = Carbon::now()->subYear($consent_payment_successful_valid_for_in_year);

		if (!empty($request->cp_id)) {
			$consentPayment = ConsentPayment::where('id', $request->cp_id)
				->where('status', 4)
				->where('customer_type', '=', 'INDIVIDUAL')
				->where('added_by', Auth::id())
				->where('updated_at', '>=', $beforeDateTime)
				->first();
			if (empty($consentPayment)) {
				return redirect()->back()->with(['message' => "something went wrong.", 'alert-type' => 'error']);
			}
			$dataList = ConsentRequest::with('detail')->where('id', $consentPayment->consent_id)
				->where('added_by', Auth::id())
				->where('status', 3)
				->where('customer_type', '=', 'INDIVIDUAL')
				->get();
		} else {
			// return redirect()->back()->with(['message' => "something went wrong.", 'alert-type' => 'error']);
			if ($reportForYear > 0) {
				$previousYears = Carbon::now()->subYear($reportForYear);
				$dataList = ConsentRequest::with('detail')
					->where('added_by', Auth::id())
					->where('status', 3)
					->where('id', $request->c_id)
					// ->where('created_at', '>=', $previousYears)
					// ->where('customer_type', '=', 'INDIVIDUAL')
					->get();
			}

			$consentPayment = new ConsentPayment;
			$consentPayment->consent_id = $request->c_id;
		}
		$records = Collection::make();
		// dd($dataList->toArray());
		if ($dataList->count()) {
			//dd($dataList);
			$individualIds = [];
			foreach ($dataList as $data) {
				/*$studentDueFeedsIds = [];
				foreach ($data->detail as $d) {
					$studentDueFeedsIds[] = $d->due_id;
				}
				$studentIdArray = StudentDueFees::whereIn('id',$studentDueFeedsIds)->get()->pluck('student_id');
				foreach ($studentIdArray as $studentIdAr) {
					if(!in_array($studentIdAr,$individualIds)){
							$individualIds[] = $studentIdAr;
						}
				}*/
				$student = Students::with('dues')->whereHas('dues', function ($q) {
					$q->whereNull('deleted_at');
				})->where('contact_phone', General::encrypt($data->contact_phone));
				if (!empty($data->person_name)) {
					//$student = $student->where('person_name', General::encrypt($data->person_name));
				}
				$student = $student->whereNull('deleted_at')->get();

				if ($student->count()) {
					foreach ($student as $s) {
						if (!in_array($s->id, $individualIds)) {
							$individualIds[] = $s->id;
						}
					}
				}
			}
			// dd($individualIds);
			// get individual detail from ids
			$records = Students::with(['dues', 'dues.paid'])->whereIn('id', $individualIds)->get();

			// dd($records);
			foreach ($records as $record) {
				//total due
				// ketli vkht report thyu e account ma aavse...
				$totalMemberReported = StudentDueFees::select('id')->where('student_id', $record->id)->whereNull('deleted_at')->groupBy('added_by')->get();
				$record->summary_totalMemberReported = $totalMemberReported->count();

				//total due reported
				$totalDueReported = StudentDueFees::where('student_id', $record->id)->whereNull('deleted_at')->sum('due_amount');
				$record->summary_totalDueReported = $totalDueReported;

				$totalDisputeCount = Dispute::where('customer_id', $record->id)->where('customer_type', '=', 'INDIVIDUAL')->get();
				$record->totalDispute = $totalDisputeCount->count();
				//
				/*if($dueDatePeriod=='less than 30days'){
					$records = $records->whereRaw("datediff(CURDATE(),due_date) < 30");
				}elseif($dueDatePeriod=='30days to 90days'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=90 AND datediff(CURDATE(),due_date) >=30 ");

				}elseif($dueDatePeriod=='91days to 180days'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=180 AND datediff(CURDATE(),due_date) >=91 ");
				}elseif($dueDatePeriod=='181days to 1year'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=365 AND datediff(CURDATE(),due_date) >=181 ");
				}elseif($dueDatePeriod=='more than 1year'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) >365 ");
				}*/

				//overDueStatus
				//0-29
				$overDueStatusCount = StudentDueFees::whereRaw("datediff(CURDATE(),due_date) < 30")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus0To29Days = $overDueStatusCount;

				//0-89
				$overDueStatusCount = StudentDueFees::whereRaw("datediff(CURDATE(),due_date) < 90")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus0To89Days = $overDueStatusCount;

				//30 to 59 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=59 AND datediff(CURDATE(),due_date) >=30 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus30To59Days = $overDueStatusCount;

				//60 to 89 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=89 AND datediff(CURDATE(),due_date) >=60 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus60To89Days = $overDueStatusCount;

				//90 to 119 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=119 AND datediff(CURDATE(),due_date) >=90 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus90To119Days = $overDueStatusCount;

				//120 to 149 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=149 AND datediff(CURDATE(),due_date) >=120 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus120To149Days = $overDueStatusCount;

				//150 to 179 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=179 AND datediff(CURDATE(),due_date) >=90 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus90To179Days = $overDueStatusCount;


				//150 to 179 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=179 AND datediff(CURDATE(),due_date) >=150 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus150To179Days = $overDueStatusCount;

				//180plus
				$overDueStatusCount = StudentDueFees::whereRaw("datediff(CURDATE(),due_date) >=180 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus180PlusDays = $overDueStatusCount;


				/* account detail */
				$accountDetails = StudentDueFees::with(['addedBy', 'profile'])->whereHas('addedBy')->whereHas('profile')->where('student_id', $record->id)->whereNull('deleted_at')->get();
				//dd($accountDetails);
				$record->accountDetails = $accountDetails;
			}
		}
		$dateTime = Carbon::now()->format('d-m-Y H:i');
		$cp_id = $request->cp_id;
		$c_id = $request->c_id;

		// dd($dataList->toArray());
		// dd($accountDetails->toArray());
		// dd($consentPayment->contact_phone);

		if(!empty($consentPayment->contact_phone))
		{
			$studentRecord = Students::where('contact_phone', General::encrypt($consentPayment->contact_phone))->first()->toArray();
		}else{
			$studentRecord['dob']='';
		}


		// $identityType = [
		// 	'AADHAR' => 'M',
		// 	'PAN' => 'T',
		// 	'PASSPORT' => 'P',
		// 	'VOTER' => 'V',
		// 	'DriverLicense' => 'D',
		// 	'RationCard' => 'R',
		// ];
		$identityType = [
			'AADHAR' => 'M',
			1 => 'T',
			3 => 'P',
			2 => 'V',
			4 => 'D',
			'RationCard' => 'R',
		];

		$consentRequest = $dataList->toArray();

		// actual record start
		$user['name'] = isset($consentRequest[0]) ? $consentRequest[0]['person_name'] : '';
		$user['number'] = isset($consentRequest[0]) ? $consentRequest[0]['contact_phone'] : '';
		$user['gender'] = '';
		$user['id_value'] = isset($consentRequest[0]) && $consentRequest[0] != NULL ? General::decrypt($consentRequest[0]['idvalue']) : '';
		$user['id_type'] = isset($consentRequest[0]) ? (isset($identityType[$consentRequest[0]['idtype']]) ? $identityType[$consentRequest[0]['idtype']] : 'O') : '';
		if(isset($studentRecord['dob']))
		{
			$dateOfBirth=$studentRecord['dob'];
			$dateOfBirth_new=date('d-m-Y', strtotime($dateOfBirth));
			$user['dob'] = $dateOfBirth_new;
		}
		else
		{
			$user['dob'] = 'Not Reported';
		}


		// actual record end

		$user['recordent'] = [
			'total_members' => count($records),
			'total_dues_unpaid' => 0,
			'total_dues_paid' => 0,
			'total_dues' => 0,
			'summary_overDueStatus0To89Days' => 0,
			'summary_overDueStatus90To179Days' => 0,
			'summary_overDueStatus180PlusDays' => 0
		];

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

		$user['recordent']['total_dues_unpaid'] = $user['recordent']['total_dues_unpaid'] - $user['recordent']['total_dues_paid'];

		// dd($user);

		if (isset($consentRequest[0]) && $consentRequest[0]['report'] == 2) {
			$api = ConsentAPIResponse::where('consent_request_id', $consentPayment->consent_id)->first();
			if (empty($api)) {
				$result = $this->getDataFromConsentApi($user);
			} else {
				$result = json_decode(General::decrypt($api->response), true);
			}
			// dd($result);
		} else {
			$result = [];
		}

		// dd($result);
		// dd($records);

		if (empty($result) || isset($result['CCRResponse']) && isset($result['CCRResponse']['CIRReportDataLst']) && isset($result['CCRResponse']['CIRReportDataLst'][0]) && isset($result['CCRResponse']['CIRReportDataLst'][0]['Error'])) {
			if (!empty($result)) {
				// $msg = $result['CCRResponse']['CIRReportDataLst'][0]['Error']['ErrorDesc'];
				// Session::flash('message', $msg);
				// Session::flash('alert-class', 'alert-danger');
			}
			// dd($records->toArray());
			$dateTime = Carbon::now()->format('d-m-Y H:i');
			$response = [];
			// return view('pdf',compact('openClosedAccountsArr', 'number', 'AddressInfo', 'RetailAccountDetails', 'limit', 'diff', 'totalPayments', 'totalSuccessPayment', 'records','dateTime','cp_id', 'response'));
			$pdf = PDF::loadView('pdf', compact('consentRequest', 'records', 'dateTime', 'cp_id', 'c_id', 'response', 'user'));
			$fileName = $request->r_n . '.pdf';
			return $pdf->download('Recordent-' . $fileName);

			return view('admin.students.report.index', compact('consentRequest', 'number', 'AddressInfo', 'RetailAccountDetails', 'limit', 'diff', 'totalPayments', 'totalSuccessPayment', 'records', 'dateTime', 'cp_id', 'response', 'user'));
			// return redirect()->back()->with(['message' => $result['CCRResponse']['CIRReportDataLst'][0]['Error']['ErrorDesc'], 'alert-type' => 'error']);
		} else {
			$api = ConsentAPIResponse::where('consent_request_id', $consentPayment->consent_id)->first();
			if (empty($api)) {
				$api = new ConsentAPIResponse();
				$api->consent_request_id = $consentPayment->consent_id;
				$api->response = General::encrypt(json_encode($result));
				$api->save();
			} else {
				$api->response = General::encrypt(json_encode($result));
				$api->save();
			}
		}

		// $api = ConsentAPIResponse::where('id', 1000)->first();
		// $response = json_decode($api->response, true);

		$api = ConsentAPIResponse::where('consent_request_id', $consentPayment->consent_id)->first();
		$response = json_decode(General::decrypt($api->response), true);
		// dd($response);

		// dd(User::where('user_type','INDIVIDUAL')->first()->toArray());

		// PAYMENT HISTORY COUNT LOGIC START
		$totalCreditLimit = 0;
		$totalCreditCardBalance = 0;
		$limit = 0;
		$totalPayments = 0;
		$totalSuccessPayment = 0;
		$statusArray = ['000', '*', 'STD', 'NEW', 'CLSD', 'OPEN', 'RES']; // status to be checked
		$openClosedAccountsArr = [
			'loan_accounts' => ['open' => 0, 'closed' => 0],
			'credit_card_accounts' => ['open' => 0, 'closed' => 0]
		];

		// dd($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['Enquiries']);

		if (isset($response['CCRResponse']) && isset($response['CCRResponse']['CIRReportDataLst']) && isset($response['CCRResponse']['CIRReportDataLst'][0])) {
			$dataOpened = date('Y-m-d');
			foreach ($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'] as $key => $value) {
				if (isset($value['AccountType'])) {
					if ($value['AccountType'] == 'Credit Card') {
						if (isset($value['Open']) && ($value['Open'] == 'Yes' || $value['Open'] == 'yes')) {
							$openClosedAccountsArr['credit_card_accounts']['open']++;

							if (isset($value['CreditLimit'])) {
								$totalCreditLimit += (float) $value['CreditLimit'];
							} else if (isset($value['HighCredit'])) {
								$totalCreditLimit += (float) $value['HighCredit'];
							}

							if (isset($value['Balance'])) {
								$totalCreditCardBalance += (float) $value['Balance'];
							}
						} else {
							$openClosedAccountsArr['credit_card_accounts']['closed']++;
						}
					} else {
						if (isset($value['Open']) && ($value['Open'] == 'Yes' || $value['Open'] == 'yes')) {
							$openClosedAccountsArr['loan_accounts']['open']++;
						} else {
							$openClosedAccountsArr['loan_accounts']['closed']++;
						}
					}
				} else {
					$response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'][$key]['AccountType'] = 'Other';
				}

				if (isset($value['DateOpened'])) {
					$date_now = new \DateTime($dataOpened);
					$date2    = new \DateTime($value['DateOpened']);
					if ($date_now > $date2) {
						$dataOpened = $value['DateOpened'];
					}
				}
				if (isset($value['History48Months'])) {
					foreach ($value['History48Months'] as $key_history => $value_history) {
						$totalPayments++;
						if (in_array($value_history['PaymentStatus'], $statusArray) && in_array($value_history['AssetClassificationStatus'], $statusArray)) {
							$totalSuccessPayment++;
						}
					}
				}
			}
		}
		// PAYMENT HISTORY COUNT LOGIC END

		$date1 = strtotime($dataOpened);
		$date2 = strtotime(date('Y-m-d'));
		$diff = abs($date2 - $date1);

		if ($totalCreditLimit > 0) {
			$limit = round(number_format((($totalCreditLimit - $totalCreditCardBalance) * 100) / $totalCreditLimit, 2));
		} else {
			$limit = 100;
		}

		// start sorting of account
		if (isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'])) {
			$RetailAccountDetails = $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'];
			uasort($RetailAccountDetails, function ($a, $b) {
				$a['DateOpened'] = isset($a['DateOpened']) ? $a['DateOpened'] : date('Y-m-d');
				$b['DateOpened'] = isset($b['DateOpened']) ? $b['DateOpened'] : date('Y-m-d');
				return strcmp($a['DateOpened'], $b['DateOpened']);
			});
		} else {
			$RetailAccountDetails = array();
		}
		// end sorting of account

		// start sorting of account
		if (isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['AddressInfo'])) {
			$AddressInfo = $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['AddressInfo'];
			usort($AddressInfo, function ($a, $b) {
				$a['ReportedDate'] = isset($b['ReportedDate']) ? $b['ReportedDate'] : date('Y-m-d');
				$b['ReportedDate'] = isset($a['ReportedDate']) ? $a['ReportedDate'] : date('Y-m-d');
				return strcmp($a['ReportedDate'], $b['ReportedDate']);
			});
		} else {
			$AddressInfo = array();
		}
		// end sorting of account

		//start get mobile and home number
		$number = array();
		$number['mobile'] = '';
		$number['home'] = '';
		if (isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PhoneInfo']) && !empty($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PhoneInfo'])) {
			foreach ($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PhoneInfo'] as $p_key => $p_value) {
				if ($p_value['typeCode'] == "H" && empty($number['home'])) {
					$number['home'] = $p_value['Number'];
				}
				if ((isset($user['number']) && $user['number'] == $p_value['Number']) || ($p_value['typeCode'] == "M" && empty($number['mobile']))) {
					$number['mobile'] = $p_value['Number'];
				}
				if ($p_value['typeCode'] == "T" && empty($number['workphone'])) {
					$number['workphone'] = $p_value['Number'];
				}
			}
		}
		//end get mobile and home number

		// dd($response);


		$dateTime = Carbon::now()->format('d-m-Y H:i');
		// return view('pdf',compact('openClosedAccountsArr', 'number', 'AddressInfo', 'RetailAccountDetails', 'limit', 'diff', 'totalPayments', 'totalSuccessPayment', 'records','dateTime','cp_id', 'response'));
		$pdf = PDF::loadView('pdf', compact('consentRequest', 'openClosedAccountsArr', 'number', 'AddressInfo', 'RetailAccountDetails', 'limit', 'diff', 'totalPayments', 'totalSuccessPayment', 'records', 'dateTime', 'cp_id', 'response', 'user'));
		$fileName = $request->r_n . '.pdf';
		// return $pdf->download('Recordent Comprehensive Report' . $fileName);
		return $pdf->download('Recordent Comprehensive Report' . $fileName);
		// return view('admin.students.report.index',compact('openClosedAccountsArr', 'number', 'AddressInfo', 'RetailAccountDetails', 'limit', 'diff', 'totalPayments', 'totalSuccessPayment', 'records','dateTime','cp_id', 'response'));
	}

	public function individualReportViewPdf(Request $request)
	{

		$dataList = Collection::make();
		$reportForYear = setting('admin.generate_report_from_consent_for_last_year') ? (int)setting('admin.generate_report_from_consent_for_last_year') : 0;


		$consent_payment_successful_valid_for_in_year = setting('admin.consent_payment_successful_valid_for_in_year') ? (int)setting('admin.consent_payment_successful_valid_for_in_year') : 7;

		$currentTime = Carbon::now();
		$beforeDateTime = Carbon::now()->subYear($consent_payment_successful_valid_for_in_year);

		if (!empty($request->cp_id)) {
			$consentPayment = ConsentPayment::where('id', $request->cp_id)
				->where('status', 4)
				->where('customer_type', '=', 'INDIVIDUAL')
				->where('added_by', Auth::id())
				->where('updated_at', '>=', $beforeDateTime)
				->first();
			if (empty($consentPayment)) {
				return redirect()->back()->with(['message' => "something went wrong.", 'alert-type' => 'error']);
			}
			$dataList = ConsentRequest::with('detail')->where('id', $consentPayment->consent_id)
				->where('added_by', Auth::id())
				->where('status', 3)
				->where('customer_type', '=', 'INDIVIDUAL')
				->get();
		} else {
			// return redirect()->back()->with(['message' => "something went wrong.", 'alert-type' => 'error']);
			if ($reportForYear > 0) {
				$previousYears = Carbon::now()->subYear($reportForYear);
				$dataList = ConsentRequest::with('detail')
					->where('added_by', Auth::id())
					->where('status', 3)
					->where('id', $request->c_id)
					// ->where('created_at', '>=', $previousYears)
					// ->where('customer_type', '=', 'INDIVIDUAL')
					->get();
			}

			$consentPayment = new ConsentPayment;
			$consentPayment->consent_id = $request->c_id;
		}
		$records = Collection::make();
		// dd($dataList->toArray());
		if ($dataList->count()) {
			//dd($dataList);
			$individualIds = [];
			foreach ($dataList as $data) {
				/*$studentDueFeedsIds = [];
				foreach ($data->detail as $d) {
					$studentDueFeedsIds[] = $d->due_id;
				}
				$studentIdArray = StudentDueFees::whereIn('id',$studentDueFeedsIds)->get()->pluck('student_id');
				foreach ($studentIdArray as $studentIdAr) {
					if(!in_array($studentIdAr,$individualIds)){
							$individualIds[] = $studentIdAr;
						}
				}*/
				$student = Students::with('dues')->whereHas('dues', function ($q) {
					$q->whereNull('deleted_at');
				})->where('contact_phone', General::encrypt($data->contact_phone));
				if (!empty($data->person_name)) {
					//$student = $student->where('person_name', General::encrypt($data->person_name));
				}
				$student = $student->whereNull('deleted_at')->get();

				if ($student->count()) {
					foreach ($student as $s) {
						if (!in_array($s->id, $individualIds)) {
							$individualIds[] = $s->id;
						}
					}
				}
			}
			// dd($individualIds);
			// get individual detail from ids
			$records = Students::with(['dues', 'dues.paid'])->whereIn('id', $individualIds)->get();

			// dd($records);
			foreach ($records as $record) {
				//total due
				// ketli vkht report thyu e account ma aavse...
				$totalMemberReported = StudentDueFees::select('id')->where('student_id', $record->id)->whereNull('deleted_at')->groupBy('added_by')->get();
				$record->summary_totalMemberReported = $totalMemberReported->count();

				//total due reported
				$totalDueReported = StudentDueFees::where('student_id', $record->id)->whereNull('deleted_at')->sum('due_amount');
				$record->summary_totalDueReported = $totalDueReported;

				$totalDisputeCount = Dispute::where('customer_id', $record->id)->where('customer_type', '=', 'INDIVIDUAL')->get();
				$record->totalDispute = $totalDisputeCount->count();
				//
				/*if($dueDatePeriod=='less than 30days'){
					$records = $records->whereRaw("datediff(CURDATE(),due_date) < 30");
				}elseif($dueDatePeriod=='30days to 90days'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=90 AND datediff(CURDATE(),due_date) >=30 ");

				}elseif($dueDatePeriod=='91days to 180days'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=180 AND datediff(CURDATE(),due_date) >=91 ");
				}elseif($dueDatePeriod=='181days to 1year'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=365 AND datediff(CURDATE(),due_date) >=181 ");
				}elseif($dueDatePeriod=='more than 1year'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) >365 ");
				}*/

				//overDueStatus
				//0-29
				$overDueStatusCount = StudentDueFees::whereRaw("datediff(CURDATE(),due_date) < 30")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus0To29Days = $overDueStatusCount;

				//0-89
				$overDueStatusCount = StudentDueFees::whereRaw("datediff(CURDATE(),due_date) < 90")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus0To89Days = $overDueStatusCount;

				//30 to 59 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=59 AND datediff(CURDATE(),due_date) >=30 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus30To59Days = $overDueStatusCount;

				//60 to 89 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=89 AND datediff(CURDATE(),due_date) >=60 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus60To89Days = $overDueStatusCount;

				//90 to 119 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=119 AND datediff(CURDATE(),due_date) >=90 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus90To119Days = $overDueStatusCount;

				//120 to 149 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=149 AND datediff(CURDATE(),due_date) >=120 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus120To149Days = $overDueStatusCount;

				//150 to 179 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=179 AND datediff(CURDATE(),due_date) >=90 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus90To179Days = $overDueStatusCount;


				//150 to 179 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=179 AND datediff(CURDATE(),due_date) >=150 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus150To179Days = $overDueStatusCount;

				//180plus
				$overDueStatusCount = StudentDueFees::whereRaw("datediff(CURDATE(),due_date) >=180 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus180PlusDays = $overDueStatusCount;


				/* account detail */
				$accountDetails = StudentDueFees::with(['addedBy', 'profile'])->whereHas('addedBy')->whereHas('profile')->where('student_id', $record->id)->whereNull('deleted_at')->get();
				//dd($accountDetails);
				$record->accountDetails = $accountDetails;
			}
		}
		$dateTime = Carbon::now()->format('d-m-Y H:i');
		$cp_id = $request->cp_id;
		$c_id = $request->c_id;

		// dd($dataList->toArray());
		// dd($accountDetails->toArray());
		// dd($consentPayment->contact_phone);

		// $studentRecord = Students::where('contact_phone', General::encrypt($consentPayment->contact_phone))->first()->toArray();
		if(!empty($consentPayment->contact_phone))
		{
			$studentRecord = Students::where('contact_phone', General::encrypt($consentPayment->contact_phone))->first()->toArray();
		}else{
			$studentRecord['dob']='';
		}

		// $identityType = [
		// 	'AADHAR' => 'M',
		// 	'PAN' => 'T',
		// 	'PASSPORT' => 'P',
		// 	'VOTER' => 'V',
		// 	'DriverLicense' => 'D',
		// 	'RationCard' => 'R',
		// ];
		$identityType = [
			'AADHAR' => 'M',
			1 => 'T',
			3 => 'P',
			2 => 'V',
			4 => 'D',
			'RationCard' => 'R',
		];

		$consentRequest = $dataList->toArray();

		// actual record start
		$user['name'] = isset($consentRequest[0]) ? $consentRequest[0]['person_name'] : '';
		$user['number'] = isset($consentRequest[0]) ? $consentRequest[0]['contact_phone'] : '';
		$user['gender'] = '';
		$user['id_value'] = isset($consentRequest[0]) && $consentRequest[0] != NULL ? General::decrypt($consentRequest[0]['idvalue']) : '';
		$user['id_type'] = isset($consentRequest[0]) ? (isset($identityType[$consentRequest[0]['idtype']]) ? $identityType[$consentRequest[0]['idtype']] : 'O') : '';

		if(isset($studentRecord['dob']))
		{
			$dateOfBirth=$studentRecord['dob'];
			$dateOfBirth_new=date('d-m-Y', strtotime($dateOfBirth));
			$user['dob'] = $dateOfBirth_new;
		}
		else
		{
			$user['dob'] = 'Not Reported';
		}

		//$user['dob'] = isset($consentRequest[0]) ? $consentRequest[0]['dob'] : '';
		// actual record end

		$user['recordent'] = [
			'total_members' => count($records),
			'total_dues_unpaid' => 0,
			'total_dues_paid' => 0,
			'total_dues' => 0,
			'summary_overDueStatus0To89Days' => 0,
			'summary_overDueStatus90To179Days' => 0,
			'summary_overDueStatus180PlusDays' => 0
		];

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

		$user['recordent']['total_dues_unpaid'] = $user['recordent']['total_dues_unpaid'] - $user['recordent']['total_dues_paid'];


		// dd($user);

		if (isset($consentRequest[0]) && $consentRequest[0]['report'] == 2) {
			$api = ConsentAPIResponse::where('consent_request_id', $consentPayment->consent_id)->first();
			if (empty($api)) {
				$result = $this->getDataFromConsentApi($user);
			} else {
				$result = json_decode(General::decrypt($api->response), true);
			}
		} else {
			$result = [];
		}

		// dd($result);
		// dd($records);

		if (empty($result) || isset($result['CCRResponse']) && isset($result['CCRResponse']['CIRReportDataLst']) && isset($result['CCRResponse']['CIRReportDataLst'][0]) && isset($result['CCRResponse']['CIRReportDataLst'][0]['Error'])) {
			if (!empty($result)) {
				// $msg = $result['CCRResponse']['CIRReportDataLst'][0]['Error']['ErrorDesc'];
				// Session::flash('message', $msg);
				// Session::flash('alert-class', 'alert-danger');
			}
			// dd($records->toArray());
			$dateTime = Carbon::now()->format('d-m-Y H:i');
			// return view('pdf',compact('openClosedAccountsArr', 'number', 'AddressInfo', 'RetailAccountDetails', 'limit', 'diff', 'totalPayments', 'totalSuccessPayment', 'records','dateTime','cp_id', 'response'));
			// $pdf = PDF::loadView('pdf', compact('openClosedAccountsArr', 'number', 'AddressInfo', 'RetailAccountDetails', 'limit', 'diff', 'totalPayments', 'totalSuccessPayment', 'records', 'dateTime', 'cp_id', 'c_id', 'response'));
			// $fileName = $request->r_n . '.pdf';
			// return $pdf->download('Recordent-' . $fileName);
			$response = [];
			return view('pdf_view', compact('consentRequest', 'records', 'dateTime', 'cp_id', 'c_id', 'response', 'user'));
			// return redirect()->back()->with(['message' => $result['CCRResponse']['CIRReportDataLst'][0]['Error']['ErrorDesc'], 'alert-type' => 'error']);
		} else {
			$api = ConsentAPIResponse::where('consent_request_id', $consentPayment->consent_id)->first();
			if (empty($api)) {
				$api = new ConsentAPIResponse();
				$api->consent_request_id = $consentPayment->consent_id;
				$api->response = General::encrypt(json_encode($result));
				$api->save();
			} else {
				$api->response = General::encrypt(json_encode($result));
				$api->save();
			}
		}

		// $api = ConsentAPIResponse::where('id', 1000)->first();
		// $response = json_decode($api->response, true);

		$api = ConsentAPIResponse::where('consent_request_id', $consentPayment->consent_id)->first();
		$response = json_decode(General::decrypt($api->response), true);
		// dd($response);

		// dd(User::where('user_type','INDIVIDUAL')->first()->toArray());

		// PAYMENT HISTORY COUNT LOGIC START
		$totalCreditLimit = 0;
		$totalCreditCardBalance = 0;
		$limit = 0;
		$totalPayments = 0;
		$totalSuccessPayment = 0;
		$statusArray = ['000', '*', 'STD', 'NEW', 'CLSD', 'OPEN', 'RES']; // status to be checked
		$openClosedAccountsArr = [
			'loan_accounts' => ['open' => 0, 'closed' => 0],
			'credit_card_accounts' => ['open' => 0, 'closed' => 0]
		];

		// dd($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['Enquiries']);

		if (isset($response['CCRResponse']) && isset($response['CCRResponse']['CIRReportDataLst']) && isset($response['CCRResponse']['CIRReportDataLst'][0])) {
			$dataOpened = date('Y-m-d');
			foreach ($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'] as $key => $value) {
				if (isset($value['AccountType'])) {
					if ($value['AccountType'] == 'Credit Card') {
						if (isset($value['Open']) && ($value['Open'] == 'Yes' || $value['Open'] == 'yes')) {
							$openClosedAccountsArr['credit_card_accounts']['open']++;

							if (isset($value['CreditLimit'])) {
								$totalCreditLimit += (float) $value['CreditLimit'];
							} else if (isset($value['HighCredit'])) {
								$totalCreditLimit += (float) $value['HighCredit'];
							}

							if (isset($value['Balance'])) {
								$totalCreditCardBalance += (float) $value['Balance'];
							}
						} else {
							$openClosedAccountsArr['credit_card_accounts']['closed']++;
						}
					} else {
						if (isset($value['Open']) && ($value['Open'] == 'Yes' || $value['Open'] == 'yes')) {
							$openClosedAccountsArr['loan_accounts']['open']++;
						} else {
							$openClosedAccountsArr['loan_accounts']['closed']++;
						}
					}
				} else {
					$response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'][$key]['AccountType'] = 'Other';
				}

				if (isset($value['DateOpened'])) {
					$date_now = new \DateTime($dataOpened);
					$date2    = new \DateTime($value['DateOpened']);
					if ($date_now > $date2) {
						$dataOpened = $value['DateOpened'];
					}
				}
				if (isset($value['History48Months'])) {
					foreach ($value['History48Months'] as $key_history => $value_history) {
						$totalPayments++;
						if (in_array($value_history['PaymentStatus'], $statusArray) && in_array($value_history['AssetClassificationStatus'], $statusArray)) {
							$totalSuccessPayment++;
						}
					}
				}
			}
		}
		// PAYMENT HISTORY COUNT LOGIC END

		$date1 = strtotime($dataOpened);
		$date2 = strtotime(date('Y-m-d'));
		$diff = abs($date2 - $date1);

		if ($totalCreditLimit > 0) {
			$limit = round(number_format((($totalCreditLimit - $totalCreditCardBalance) * 100) / $totalCreditLimit, 2));
		} else {
			$limit = 100;
		}

		// start sorting of account
		if (isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'])) {
			$RetailAccountDetails = $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'];
			uasort($RetailAccountDetails, function ($a, $b) {
				$a['DateOpened'] = isset($a['DateOpened']) ? $a['DateOpened'] : date('Y-m-d');
				$b['DateOpened'] = isset($b['DateOpened']) ? $b['DateOpened'] : date('Y-m-d');
				return strcmp($a['DateOpened'], $b['DateOpened']);
			});
		} else {
			$RetailAccountDetails = array();
		}
		// end sorting of account

		// start sorting of account
		if (isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['AddressInfo'])) {
			$AddressInfo = $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['AddressInfo'];
			usort($AddressInfo, function ($a, $b) {
				$a['ReportedDate'] = isset($b['ReportedDate']) ? $b['ReportedDate'] : date('Y-m-d');
				$b['ReportedDate'] = isset($a['ReportedDate']) ? $a['ReportedDate'] : date('Y-m-d');
				return strcmp($a['ReportedDate'], $b['ReportedDate']);
			});
		} else {
			$AddressInfo = array();
		}
		// end sorting of account

		//start get mobile and home number
		$number = array();
		$number['mobile'] = '';
		$number['home'] = '';
		if (isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PhoneInfo']) && !empty($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PhoneInfo'])) {
			foreach ($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PhoneInfo'] as $p_key => $p_value) {
				if ($p_value['typeCode'] == "H" && empty($number['home'])) {
					$number['home'] = $p_value['Number'];
				}
				if ((isset($user['number']) && $user['number'] == $p_value['Number']) || ($p_value['typeCode'] == "M" && empty($number['mobile']))) {
					$number['mobile'] = $p_value['Number'];
				}
				if ($p_value['typeCode'] == "T" && empty($number['workphone'])) {
					$number['workphone'] = $p_value['Number'];
				}
			}
		}
		//end get mobile and home number

		// dd($response);


		$dateTime = Carbon::now()->format('d-m-Y H:i');
		return view('pdf_view', compact('consentRequest', 'openClosedAccountsArr', 'number', 'AddressInfo', 'RetailAccountDetails', 'limit', 'diff', 'totalPayments', 'totalSuccessPayment', 'records', 'dateTime', 'cp_id', 'c_id', 'response', 'user'));
		// $pdf = PDF::loadView('pdf', compact('openClosedAccountsArr', 'number', 'AddressInfo', 'RetailAccountDetails', 'limit', 'diff', 'totalPayments', 'totalSuccessPayment', 'records', 'dateTime', 'cp_id', 'response'));
		// $fileName = $request->r_n . '.pdf';
		// return $pdf->download('Recordent Comprehensive Report' . $fileName);
		// return $pdf->stream('Recordent Comprehensive Report' . $fileName);
		// return view('admin.students.report.index',compact('openClosedAccountsArr', 'number', 'AddressInfo', 'RetailAccountDetails', 'limit', 'diff', 'totalPayments', 'totalSuccessPayment', 'records','dateTime','cp_id', 'response'));
	}


	public function individualReportDowload(Request $request)
	{

		//dd(setting('admin.icon_image'));
		if (empty($request->cp_id) || empty($request->c_id) || empty($request->r_n)) {
			return redirect()->back()->with(['message' => "something went wrong.", 'alert-type' => 'error']);
		}
		$dataList = Collection::make();
		$reportForYear = setting('admin.generate_report_from_consent_for_last_year') ? (int)setting('admin.generate_report_from_consent_for_last_year') : 0;


		$consent_payment_successful_valid_for_in_year = setting('admin.consent_payment_successful_valid_for_in_year') ? (int)setting('admin.consent_payment_successful_valid_for_in_year') : 7;

		$currentTime = Carbon::now();
		$beforeDateTime = Carbon::now()->subYear($consent_payment_successful_valid_for_in_year);

		if (!empty($request->cp_id)) {
			$consentPayment = ConsentPayment::where('id', $request->cp_id)
				->where('status', 4)
				->where('customer_type', '=', 'INDIVIDUAL')
				->where('added_by', Auth::id())
				->where('updated_at', '>=', $beforeDateTime)
				->first();
			if (empty($consentPayment)) {
				return redirect()->back()->with(['message' => "something went wrong.", 'alert-type' => 'error']);
			}
			$dataList = ConsentRequest::with('detail')->where('id', $consentPayment->consent_id)
				->where('added_by', Auth::id())
				->where('status', 3)
				->where('customer_type', '=', 'INDIVIDUAL')
				->get();
		} else {
			return redirect()->back()->with(['message' => "something went wrong.", 'alert-type' => 'error']);
			if ($reportForYear > 0) {
				$previousYears = Carbon::now()->subYear($reportForYear);
				$dataList = ConsentRequest::with('detail')
					->where('added_by', Auth::id())
					->where('status', 3)
					->where('created_at', '>=', $previousYears)
					->where('customer_type', '=', 'INDIVIDUAL')
					->get();
			}
		}
		$records = Collection::make();
		//dd($dataList);
		if ($dataList->count()) {
			//dd($dataList);
			$individualIds = [];
			foreach ($dataList as $data) {
				/*$studentDueFeedsIds = [];
				foreach ($data->detail as $d) {
					$studentDueFeedsIds[] = $d->due_id;
				}
				$studentIdArray = StudentDueFees::whereIn('id',$studentDueFeedsIds)->get()->pluck('student_id');
				foreach ($studentIdArray as $studentIdAr) {
					if(!in_array($studentIdAr,$individualIds)){
							$individualIds[] = $studentIdAr;
						}
				}*/
				$student = Students::with('dues')->whereHas('dues', function ($q) {
					$q->whereNull('deleted_at');
				})->where('contact_phone', General::encrypt($data->contact_phone));
				if (!empty($data->person_name)) {
					//$student = $student->where('person_name', General::encrypt($data->person_name));
				}
				$student =  $student->where('id', $request->c_id);
				$student = $student->whereNull('deleted_at')->get();
				if ($student->count()) {
					foreach ($student as $s) {
						if (!in_array($s->id, $individualIds)) {
							$individualIds[] = $s->id;
						}
					}
				}
			}

			// get individual detail from ids
			$records = Students::with(['dues', 'dues.paid'])->whereIn('id', $individualIds)->get();

			foreach ($records as $record) {
				//total due
				// ketli vkht report thyu e account ma aavse...
				$totalMemberReported = StudentDueFees::select('id')->where('student_id', $record->id)->whereNull('deleted_at')->groupBy('added_by')->get();
				$record->summary_totalMemberReported = $totalMemberReported->count();

				//total due reported
				$totalDueReported = StudentDueFees::where('student_id', $record->id)->whereNull('deleted_at')->sum('due_amount');
				$record->summary_totalDueReported = $totalDueReported;

				$totalDisputeCount = Dispute::where('customer_id', $record->id)->where('customer_type', '=', 'INDIVIDUAL')->get();
				$record->totalDispute = $totalDisputeCount->count();

				//
				/*if($dueDatePeriod=='less than 30days'){
					$records = $records->whereRaw("datediff(CURDATE(),due_date) < 30");
				}elseif($dueDatePeriod=='30days to 90days'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=90 AND datediff(CURDATE(),due_date) >=30 ");

				}elseif($dueDatePeriod=='91days to 180days'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=180 AND datediff(CURDATE(),due_date) >=91 ");
				}elseif($dueDatePeriod=='181days to 1year'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) <=365 AND datediff(CURDATE(),due_date) >=181 ");
				}elseif($dueDatePeriod=='more than 1year'){
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) >365 ");
				}*/

				//overDueStatus
				//0-29
				$overDueStatusCount = StudentDueFees::whereRaw("datediff(CURDATE(),due_date) < 30")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus0To29Days = $overDueStatusCount;

				//30 to 59 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=59 AND datediff(CURDATE(),due_date) >=30 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus30To59Days = $overDueStatusCount;

				//60 to 89 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=89 AND datediff(CURDATE(),due_date) >=60 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus60To89Days = $overDueStatusCount;

				//90 to 119 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=119 AND datediff(CURDATE(),due_date) >=90 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus90To119Days = $overDueStatusCount;

				//120 to 149 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=149 AND datediff(CURDATE(),due_date) >=120 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus120To149Days = $overDueStatusCount;

				//150 to 179 days
				$overDueStatusCount = StudentDueFees::whereRaw(" datediff(CURDATE(),due_date) <=179 AND datediff(CURDATE(),due_date) >=150 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus150To179Days = $overDueStatusCount;

				//180plus
				$overDueStatusCount = StudentDueFees::whereRaw("datediff(CURDATE(),due_date) >=180 ")->where('student_id', $record->id)->whereNull('deleted_at')->count();
				$record->summary_overDueStatus180PlusDays = $overDueStatusCount;


				/* account detail */
				$accountDetails = StudentDueFees::with(['addedBy', 'profile'])->whereHas('addedBy')->whereHas('profile')->where('student_id', $record->id)->whereNull('deleted_at')->get();
				//dd($accountDetails);
				$record->accountDetails = $accountDetails;
			}
		}
		$dateTime = Carbon::now()->format('d-m-Y H:i');

		//return view('admin.students.report.table',compact('records','dateTime'));
		$pdf = PDF::loadView('admin.students.report.table', ['records' => $records, 'dateTime' => $dateTime, 'reportNumber' => $request->r_n]);
		//$pdf = PDF::loadView('admin.students.report.download', ['records'=>$records,'dateTime'=>$dateTime]);
		$fileName = $request->r_n . '.pdf';
		return $pdf->download('Recordent-' . $fileName);
	}

	public function importDuePayment(Request $request)
	{
		$import = new StudentsDuePaymentImport;
		$import->uniqueUrlCode = strtolower(Str::random(10));
		try {
			$fileToArray = (new StudentsDuePaymentImport)->toArray(request()->file('file'));
		} catch (\Exception $e) {
			return redirect()->back()->with(['message' => 'Something is wrong', 'alert-type' => 'error']);
		}
		$columnNames = array_keys($fileToArray[0][0]);
		if (
			!in_array('invoice_no', $columnNames) ||
			!in_array('payment_date_ddmmyyyy', $columnNames) ||
			!in_array('payment_amount', $columnNames) ||
			!in_array('payment_note', $columnNames) ||
			!in_array('customer_id', $columnNames)
		) {
			Session::flash('message', 'Error: Some fields are missing in the sheet');
			return redirect()->back();
		}

		/*Don't want to check format to avoid issues 13-10-2020*/
		/*if($columnNames[0] !='invoice_no' ||
			$columnNames[1] !='payment_date_ddmmyyyy' ||
			$columnNames[2] !='payment_amount' ||
			$columnNames[3] !='payment_note'
		){
			Session::flash('message','Error: Format not same as Master Template. Recheck and upload');
			return redirect()->back();
		}*/
		/*comments ends here*/

		try {
			Excel::import($import, request()->file('file'));
			$totalRows = $import->getRowCount();
			if ($import->atLeastIssue === true) {
				return redirect()->route('import-due-payment.issues', [$import->uniqueUrlCode])->with(['message' => $totalRows['Updated'] . ' Record imported and because of format error ' . $totalRows['Skipped'] . ' record skipped', 'alert-type' => 'success']);
			} else {
				return redirect()->back()->with(['message' => $totalRows['Updated'] . ' Record imported and because of format error ' . $totalRows['Skipped'] . ' record skipped', 'alert-type' => 'success']);
			}
		} catch (\Exception $e) {
			//dd($e);
			return redirect()->back()->with(['message' => 'Something is wrong', 'alert-type' => 'error']);
		}
	}

	public function importDuePaymentIssues($uniqueUrlCode)
	{
		$records = BulkDuePaymentUploadIssues::where('unique_url_code', General::encrypt($uniqueUrlCode))
			->where('status', 0)
			->where('added_by', Auth::id())
			->orderBy('id', 'ASC')
			->get();
		BulkDuePaymentUploadIssues::where('unique_url_code', General::encrypt($uniqueUrlCode))
			->where('added_by', Auth::id())
			->where('status', 0)
			->update(['status' => 1]);
		return view('admin.import.import-due-payment-excel-issues', compact('records'));
	}
	public function importSuperExcel($userId)
	{
		$UploadFile = false;
		if (General::checkMemberEligibleToUploadPaymentMasterFile()) {
			$UploadFile = true;
		}
		$showUploadFile = array("isShow" => $UploadFile);
		return view('admin.import-excel-super', ['userId' => $userId])->with($showUploadFile);
	}
	// public function importExcelViewSuper($userId)
	//     {
	//     	// echo $userId;die();
	// 		$UploadFile = false;
	// 		//Log::debug(print_r($userId,true));exit();
	// 		if(General::checkMemberEligibleToUploadPaymentMasterFile()){
	// 			$UploadFile = true;
	// 			}
	// 		$showUploadFile = array("isShow"=>$UploadFile);
	// 		// $id='';

	//         return view('admin.import-excel-super',['userId' => $userId])->with($showUploadFile);

	//     }
    public function importReportSuper(Request $request, $userId)
    {

		$validator = Validator::make($request->all(), [
			'file' => 'mimes:xls,xlsx,pdf,doc,docx,txt',
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

        $today = Carbon::now();
        $path='super_admin_reports/'.$today->month."-".$today->year."-".$userId;
        Storage::disk('public')->makeDirectory($path);
        $ReportPath = Storage::disk('public')->put($path, $request->file('file'));
        $ReportFull_Path=asset("storage")."/".$ReportPath;
        $Report_Data = [
            'file_path' => $ReportFull_Path,
            'member_id' => $userId,
            'created_at' => Carbon::now(),
        ];

        IndividualAdminReports::create($Report_Data);

        return redirect()->back()->with(['message' => "Successfully uploaded", 'alert-type' => 'success']);
    }
	public function importExcelSuper(Request $request, $userId)
	{

		ini_set('max_execution_time', 0);
		Session::put('member_id', $userId);
		$import = new StudentsImport;
		$import->uniqueUrlCode = Str::random(10);

		try {
			$fileToArray = (new StudentsImport)->toArray(request()->file('file'));
		} catch (\Exception $e) {
			return redirect()->back()->with(['message' => 'Something is wrong', 'alert-type' => 'error']);
		}

		$columnNames = array_keys($fileToArray[0][0]);

		if (General::validateIndividualBulkExcelImportColumns($columnNames)) {
			Session::flash('message', 'Error: Some fields are missing in the sheet');
			return redirect()->back();
		}

		if (General::validateIndividualBulkExcelImportColumnsFormat($columnNames)) {
			Session::flash('message', 'Error: Format not same as Master Template. Recheck and upload');
			return redirect()->back();
		}

		try {
			// $remainingCustomer = General::getFreeCustomersDuesLimit($userId);
			$remainingCustomer = CustomerHelper::getRemainingFreeCustomersDuesLimit($userId);

			if ($remainingCustomer >= count($fileToArray[0])) {
				$recordsToAllowCount = count($fileToArray[0]);
			} else {
				$recordsToAllowCount = $remainingCustomer;
			}

			$totalSkippedRecordCount = count($fileToArray[0]) - $recordsToAllowCount;
			Log::debug('recordsToAllowCount = '.$recordsToAllowCount);
			Log::debug('totalSkippedRecordCount = '.$totalSkippedRecordCount);

			$skipAll = true;
			$new_student_customers_data = array();
			$existing_customers_data = array();

			$existing_student_customers_count = 0;
			$new_student_customers_count = 0;

			$is_validation_error = false;
			foreach ($fileToArray[0] as $tempKey => $tempValue) {
				$row = (array)$tempValue;
				$status = General::validateIndividualBulkUploadData($row, $import->uniqueUrlCode);

				if($status){
					$is_validation_error = true;
				}

				$records = \App\Students::where('person_name', 'LIKE', General::encrypt(strtolower($tempValue['person_name'])))
					->where('contact_phone', '=', General::encrypt($tempValue['contact_phone_number']))->whereNull('deleted_at');
				// if (!Auth::user()->hasRole('admin')) {
				// 	$records = $records->where('added_by', Auth::id());
				// } else {
				// 	$records = $records->where('added_by', $userId);
				// }

				$records = $records->first();

				$person_name = strtolower($tempValue['person_name']);
				$person_pno = $tempValue['contact_phone_number'];

				if (!empty($records) && CustomerHelper::isAlreadyExistingCustomer($userId, $records->id, 1)) {
					$skipAll = false;
					$fileToArray[0][$tempKey]['skip'] = false;
					$existing_customers_data[$person_name][$person_pno] = $tempValue['contact_phone_number'];
				} else {
					$new_student_customers_data[$person_name][$person_pno] = $tempValue['contact_phone_number'];
				}
			}

			if ($is_validation_error) {
				return redirect()->route('import-excel.issues', [$import->uniqueUrlCode, $userId])->with(['message' => 'No Records are imported due to format errors', 'alert-type' => 'error']);
			}

			// $new_student_customers_count = count($new_student_customers_data);
			$new_student_customers_count = array_sum(array_map("count", $new_student_customers_data));
			Log::debug('new_student_customers_count = '.$new_student_customers_count);

			if ($new_student_customers_count != 0 && $new_student_customers_count > $remainingCustomer) {
				$skipAll = true;
			} else {
				$skipAll = false;
			}

			if ($skipAll) {
				$remainingRecordss = $fileToArray[0];
				$totalSkippedRecordCount = 0;

				$temp = [];
				foreach($remainingRecordss as $temp_key => $temp_value){

					$checkNullArray = array_filter($temp_value);
					if(count($checkNullArray) > 0) {
						$remainingRecords[] = $temp_value;
						if(!in_array($temp_value['contact_phone_number'], $temp)){
							$temp[] = $temp_value['contact_phone_number'];
							$totalSkippedRecordCount++;
						}
					}
				}

				if ($remainingCustomer <=0) {
					$totalSkippedRecordCount = $new_student_customers_count;
				}

				if ($remainingCustomer > 0) {
					$totalSkippedRecordCount = abs($remainingCustomer - $new_student_customers_count);
				}

				$SkippedDuesRecord = new SkippedDuesRecord();
				$SkippedDuesRecord->user_id = $userId;
				$SkippedDuesRecord->request_data = json_encode($fileToArray[0]);
				$SkippedDuesRecord->total_skipped_record_count = $totalSkippedRecordCount;
				$SkippedDuesRecord->save();

				return view('admin.add-record.skipped-popup-import', compact('SkippedDuesRecord', 'remainingRecords', 'totalSkippedRecordCount'));
			}

			Excel::import($import, request()->file('file'));
			Session::remove('member_id');

			$totalRows = $import->getRowCount();
			if ($import->atLeastIssue === true) {
				return redirect()->route('import-excel.issues', [$import->uniqueUrlCode, $userId])->with(['message' => $totalRows['Updated'] . ' Record imported and because of format error ' . $totalRows['Skipped'] . ' record skipped', 'alert-type' => 'success']);
			} else {
				return redirect()->back()->with(['message' => $totalRows['Updated'] . ' Record imported and because of format error ' . $totalRows['Skipped'] . ' record skipped', 'alert-type' => 'success']);
			}
		} catch (\Exception $e) {
			//echo $e->getMessage(); die;
			$errorMsg = date('Y-m-d H:i:s') . "----individual----" . $e->getMessage();
			error_log($errorMsg, 3, storage_path() . '/logs/bulkuploads.log');
			return redirect()->back()->with(['message' => 'Something is wrong', 'alert-type' => 'error']);
		}
		// $count_limit= $import->count_plan_limit();
		// if($import->count_plan_limit()===true){
		// 	return redirect()->route('import-excel');
		// }


	}

	public function importUpdateProfile(Request $request, $userId)
	{
		$import = new StudentsUpdateProfileImport;
		$import->uniqueUrlCode = strtolower(Str::random(10));
		Session::put('member_id', $userId);
		try {

			$fileToArray = (new StudentsUpdateProfileImport)->toArray(request()->file('file'));
		} catch (\Exception $e) {
			return redirect()->back()->with(['message' => 'Something is wrong', 'alert-type' => 'error']);
		}
		$columnNames = array_keys($fileToArray[0][0]);
		if (
			!in_array('customer_id', $columnNames) ||
			!in_array('email', $columnNames) ||
			!in_array('aadhar_number', $columnNames)

		) {
			Session::flash('message', 'Error: Some fields are missing in the sheet');
			return redirect()->back();
		}
		try {
			Excel::import($import, request()->file('file'));
			Session::remove('member_id');
			$totalRows = $import->getRowCount();
			if ($import->atLeastIssue === true) {
				return redirect()->route('import-excel-profile.issues', [$import->uniqueUrlCode, $userId])->with(['message' => $totalRows['Updated'] . ' Record imported and because of format error ' . $totalRows['Skipped'] . ' record skipped', 'alert-type' => 'success']);
			} else {
				return redirect()->back()->with(['message' => $totalRows['Updated'] . ' Record imported and because of format error ' . $totalRows['Skipped'] . ' record skipped', 'alert-type' => 'success']);
			}
		} catch (\Exception $e) {
		// dd($e);
			return redirect()->back()->with(['message' => 'Something is wrong', 'alert-type' => 'error']);
		}
	}
	public function importExcelProfileIssues($uniqueUrlCode, $userId="")
	{
		$authId = $userId != "" ? ($userId) : (Auth::id());
		$records = IndividualBulkUploadIssues::where('unique_url_code', General::encrypt($uniqueUrlCode))
			->where('status', 0)
			->where('added_by', $authId)
			->orderBy('id', 'ASC')
			->get();
		IndividualBulkUploadIssues::where('unique_url_code', General::encrypt($uniqueUrlCode))
			->where('added_by', $authId)
			->where('status', 0)
			->update(['status' => 1]);
		return view('admin.import-excel-profile-issues', compact('records'));
	}


    public static function getTotalDueForStudentByCustomId(Request $request)
	{
	     $studentID = $request->studentID;
	     $dueId = $request->dueId;
	     $added_by = $request->added_by;
	     $custom_id = $request->custom_id;
	     if(!isset($custom_id)){
                  $custom_id = NULL;
	     }

	     $result=General::getTotalDueForStudentByCustomId($studentID,$added_by,$dueId,$custom_id) - General::getTotalPaidForStudentByCustomId($studentID,$added_by,$dueId,$custom_id);
	     return $result;

	}

	public static function getStudentDuesCustomerLevel(Request $request)
	{

	     $studentID = $request->input('studentID');
	     $dueId = $request->input('dueId');
	     $custom_id = $request->input('custom_id');

	  //    $getCustomId = StudentDueFees::select('external_student_id')->where('student_id','=',$studentID)->where('id','=',$dueId);
	  //    $getCustomId = $getCustomId->first();
		 // $checkCustomId = isset($getCustomId->external_student_id) ? $getCustomId->external_student_id : NULL;

	  //    $getCustomId = StudentDueFees::select('external_student_id')->where('student_id','=',$studentID);
	  //    $getCustomId = $getCustomId->first();
		 // $checkCustomId = $getCustomId->external_student_id;

	   	$dues = StudentDueFees::where('student_id',$studentID)->where('added_by',Auth::id())->where('external_student_id', $custom_id)->whereNull('deleted_at');

	    	$dues = $dues->withCount([
			'paid AS totalPaid' => function ($query)  {
				$query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at');
			}
		]);
	    	$dues = $dues->get();
            // Log::debug($dues);
            return $dues;


	 }

	public function storePayAmountCustomerLevel(Request $request){
	 	$studentID = $request->student_id;
	    $dueId = $request->student_due_id;
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
	    } else if(isset($payment_options)) {
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
			'postpaid' => Auth::user()->collection_fee_individual == 1 ? 1 : 0,
			'status' => 4,
			'invoice_type_id' => 6
		];
		if (Auth::user()->collection_fee_individual == 1) {


			$membershipPayment = MembershipPayment::create($valuesForMembershipPayment);
			General::UpdatePaymentsCustomerLevelStudent($studentID,$dueId,$paid_note,$orderArr,$skipandupdatepayment,$payment_options,$paid_date,$payment_amount);

			// $response = app('App\Http\Controllers\HomeController')->postpaid_invoice_sendmail($membershipPayment->id);
			$student = Students::where('id',$studentID)->whereNull('deleted_at')->first();
		    if(array_key_exists('send_updatepayment_sms',$request->all())) {
		    	if(isset($student)){
						$mobile_number= $student->contact_phone;
						$name= $student->person_name;
						$business_name = Auth::user()->business_name;
						$email = $student->email;
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


					$duesRecord = StudentDueFees::where('id', $dueId)->where('student_id', $studentID)->where('added_by', Auth::id())->whereNull('deleted_at')->first();

					if($duesRecord->balance_due !=0)
					{
						General::Update_Balance_Due($duesRecord->balance_due,$request->payment_amount,"Student",$dueId,$studentID);
					}
			
				

			return redirect()->back()->with(['message' => 'Payment updated successfully.', 'alert-type' => 'success']);
		}

		DB::beginTransaction();
		try {
			$send_sms_email = 0;
			if(array_key_exists('send_updatepayment_sms',$request->all())) { $send_sms_email = 1; }

			$tempDuePayment = TempDuePayment::create([
				'order_id' => Str::random(40),
				'customer_type' => 'INDIVIDUAL',
				'customer_id' => $studentID,
				'due_id' => $dueId,
				'payment_value' => $payment_amount,
				'created_at' => Carbon::now(),
				'added_by' => Auth::id(),
				'payment_note' => $paid_note,
				'payment_date' => $payment_amount,
				'send_sms_email' => $send_sms_email,
				'external_student_id' => ""
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
				'callback_url' => route('student-due-payment-callback-customer-level',[$paid_date,$paid_note,$orderArr,$payment_options,$skipandupdatepayment])
			]);
			return $payment->view('admin.payment-submit')->receive();
		} else {

			$postData = [
				'amount' => $duePayment->total_collection_value,
				'txnid' => $duePayment->order_id,
				'phone' => $userDataToPaytm->mobile_number,
				'email' => $userDataToPaytm->email,
				'firstname' => preg_replace('/\s+/', '', $userDataToPaytm->name),
				'surl' => route('student-due-payment-callback-customer-level',[$paid_date,$paid_note,$orderArr,$payment_options,$skipandupdatepayment])
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
			$customStudentId = NULL;
			if ($duePayment->status == 4) { // successful payment
				if($tempDuePayment->external_student_id!="") {
					$customStudentId = $tempDuePayment->external_student_id;
				}
				General::UpdatePaymentsCustomerLevelStudent($duePayment->customer_id,$duePayment->due_id,$paid_note,$orderArr,$skipandupdatepayment,$payment_options,$paid_date,$duePayment->payment_value);


				$membershipPayment =  MembershipPayment::where('due_id', $tempDuePayment->due_id)->first();
				if (!empty($membershipPayment)) {
					$response = app('App\Http\Controllers\HomeController')->postpaid_invoice_sendmail($membershipPayment->id);
				}
				if($tempDuePayment->send_sms_email) {
					$mobile_number= $duePayment->individualProfile->contact_phone;
					$name= $duePayment->individualProfile->person_name;
					$business_name = Auth::user()->business_name;
					$email = $duePayment->individualProfile->email;
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
			if ($duePayment->status == 4 || $duePayment->status == 5) {
				$tempDuePayment->delete();
			}

			DB::commit();
		} catch (\Exception $e) {
			// DB::rollback();
			// dd($e);
			return redirect('admin/member-users-individual-records' . $redirectQueryString)->with(['message' => 'can not store due payment.', 'alert-type' => 'error']);
		}

		if($payment_options == 0){
			if($paymentStatus == 'success'){
				$duesRecord = StudentDueFees::where('id', $duePayment->due_id)->where('student_id', $duePayment->customer_id)->where('added_by', Auth::id())->whereNull('deleted_at')->first();
		
				if($duesRecord->balance_due !=0)
				{
					General::Update_Balance_Due($duesRecord->balance_due,$duePayment->payment_value,"Student",$duePayment->due_id,$duePayment->customer_id);
				}
				}
		}
		
		return redirect('admin/member-users-individual-records' . $redirectQueryString)->with(['message' => $message, 'alert-type' => $alertType]);
	}


	public function getProofOfDueList(Request $request){

		$studentid=$request->studentid;
		$cust_id=$request->cust_id;
		$due_id=$request->due_id;
		$studentdata =  DB::table('students')
						->select('*')
						->where('id', $studentid)
						->where('added_by', Auth::id())
						->get();
		$studentdata_dues =  DB::table('student_due_fees')
						->select('*')
						->where('student_id', $studentid)
						->where('external_student_id',$cust_id)
						->where('added_by', Auth::id())
						->get();

		$students=array();
		foreach($studentdata as $rec)
		{
			if($rec->proof_of_due !=null){
				$dues_list_img=str_replace("proof_of_due/",'',$rec->proof_of_due);
			$dues_list_img=trim($dues_list_img,",");
			$proofList=explode(",",$dues_list_img);
			$proof_of_due="";
			foreach($proofList as $img)
			{
				$file_name=storage_path('app/public/proof_of_due/'.$img);
				if (file_exists($file_name)){

					$proof_of_due .=$img.",";
				}else{
					$proof_of_due .='';
				}
			}
			$proof_of_due=trim($proof_of_due,",");
			if($proof_of_due != "")
			{
				$rec->proof_of_due='proof_of_due/'.$proof_of_due;
			}else{
				$rec->proof_of_due=null;
			}

			}

			$rec->person_name=strtoupper(General::decrypt($rec->person_name));
			$rec->flag=1;
			$students[]=$rec;
		}

		$students_due=array();
		foreach($studentdata_dues as &$rec)
		{
		   $paidAmount=StudentPaidFees::where('student_id', '=', $studentid)
		   								->where('due_id', '=', $rec->id)
										->select('paid_amount')
           			 					->groupBy('student_id')
										->sum('paid_amount');
			if($paidAmount>0)
			{
				$remaing_balance=($rec->due_amount) - ($paidAmount);
			}else{
				$remaing_balance=$rec->due_amount;
			}
			$rec->remaing_balance=$remaing_balance;
			$students_due[]=$rec;
		}

		$result=array_merge($students,$students_due);
		$records=array();
		foreach($result as $rec)
		{
			$records[]=$rec;
		}

		return Response::json(['success' => true,"message"=>'', 'data' => $records], 200);
	}

	public function IsAssigneProofdDue(Request $request)
	{
		$proofof_due_file=$request->proofof_due_file;
		$studen_id=$request->studen_id;

		$data=StudentDueFees::where('student_id', $studen_id)
							->where('proof_of_due','!=' , null)
							->where('added_by', Auth::id())
							->get();

		$exsitingFiles=array();
		foreach($data as $rec)
		{
			$proofOfDue_file=$rec->proof_of_due;
			$proofOfDue=str_replace("proof_of_due/","",$proofOfDue_file);
			if (strpos($proofOfDue, $proofof_due_file) !== false) {
				$exsitingFiles[]=$rec->id;
			}

		}
		return Response::json(['success' => true,"message"=>'', 'data' => $exsitingFiles], 200);

	}

}
