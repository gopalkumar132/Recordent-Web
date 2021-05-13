<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\BusinessesImport;
use App\Exports\BusinessesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Sector;
use App\State;
use App\City;
use App\Businesses;
use App\BusinessDueFees;
use App\BusinessPaidFees;
use App\BusinessBulkUploadIssues;
use App\UsersOfferCodes;
use Illuminate\Support\Str;
use App\ConsentRequest;
use App\MembershipPayment;
use App\DuesSmsLog;
use App\User;
use App\StoreGstinLookups;
use Illuminate\Support\Facades\Mail as SendMail;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use Illuminate\Support\Collection;
use General;
use Session;
use App\Services\SmsService;
use PaytmWallet;
use App\ConsentPayment;
use App\Imports\BusinessDuePaymentImport;
use App\Imports\BusinessUpdateProfileImport;
use App\BulkDuePaymentUploadIssues;
use App\SkippedDuesRecord;
use Log;
use HomeHelper;
use App\BusinessAdminReports;
use Storage;
use PDF;
use CustomerHelper;

class AllRecordController extends Controller
{
	public function importExcelView()
	{
		$UploadFile = false;
		if (General::checkMemberEligibleToUploadPaymentMasterFile()) {
			$UploadFile = true;
		}
		$showUploadFile = array("isShow" => $UploadFile);
		return view('admin.import-excel-business')->with($showUploadFile);
	}

	public function importExcel(Request $request)
	{
		ini_set('max_execution_time', 0);
		$import = new BusinessesImport;
		$import->uniqueUrlCode = Str::random(10);
		try {
			$fileToArray = (new BusinessesImport)->toArray(request()->file('file'));
		} catch (\Exception $e) {
			return redirect()->back()->with(['message' => 'Something is wrong', 'alert-type' => 'error']);
		}

		$columnNames = array_keys($fileToArray[0][0]);

		if($columnNames[2]== 'unique_identification_number_gstin_business_pan'){
         	$unique_identification_number = 'unique_identification_number_gstin_business_pan';
		 } else {
			$unique_identification_number = 'unique_identification_number';
		 }

		// dd($columnNames);
		if (
			!in_array('business_name', $columnNames) ||
			!in_array('sector_name', $columnNames) ||
			!in_array($unique_identification_number, $columnNames) ||
			!in_array('concerned_person_name', $columnNames) ||
			!in_array('concerned_person_designation', $columnNames) ||
			!in_array('concerned_person_phone', $columnNames) ||
			!in_array('concerned_person_alternate_phone', $columnNames) ||
			!in_array('state', $columnNames) ||
			!in_array('city', $columnNames) ||
			!in_array('pin_code', $columnNames) ||
			!in_array('address', $columnNames) ||
			!in_array('duedate_ddmmyyyy', $columnNames) ||
			!in_array('dueamount', $columnNames) ||
			!in_array('email', $columnNames) ||
			!in_array('grace_period', $columnNames) ||
			!in_array('invoice_no', $columnNames) ||
			!in_array('business_type', $columnNames)
		) {
			Session::flash('message', 'Error: Some fields are missing in the sheet');
			return redirect()->back();
		}
		if (
			$columnNames[0] != 'business_name' ||
			$columnNames[1] != 'sector_name' ||
			$columnNames[2] != $unique_identification_number ||
			$columnNames[3] != 'concerned_person_name' ||
			$columnNames[4] != 'concerned_person_designation' ||
			$columnNames[5] != 'concerned_person_phone' ||
			$columnNames[6] != 'concerned_person_alternate_phone' ||
			$columnNames[7] != 'state' ||
			$columnNames[8] != 'city' ||
			$columnNames[9] != 'pin_code' ||
			$columnNames[10] != 'address' ||
			$columnNames[11] != 'duedate_ddmmyyyy' ||
			$columnNames[12] != 'dueamount' ||
			$columnNames[13] != 'email' ||
			$columnNames[14] != 'grace_period' ||
			$columnNames[15] != 'invoice_no' ||
			$columnNames[16] != 'business_type'
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
			Log::debug('remainingCustomer limit = '.$remainingCustomer);

			if ($remainingCustomer >= count($fileToArray[0])) {
				$recordsToAllowCount = count($fileToArray[0]);
			} else {
				$recordsToAllowCount = $remainingCustomer;
			}

			$totalSkippedRecordCount = count($fileToArray[0]) - $recordsToAllowCount;
			Log::debug('recordsToAllowCount = '.$recordsToAllowCount);
			Log::debug('totalSkippedRecordCount = '.$totalSkippedRecordCount);
			// Log::debug('fileToArray[0] = '.print_r($fileToArray[0], true));

			$skipAll = true;
			$new_business_customers_data = array();
			$existing_customers_data = array();

			$existing_business_customers_count = 0;
			$new_business_customers_count = 0;

			$is_validation_error = false;

			foreach ($fileToArray[0] as $tempKey => $tempValue) {

				$row = (array)$tempValue;
				if(isset($row['email'])){
					$mail=trim($row['email']);
					$row['email'] = str_replace(' ', '', $mail);
				}
				if(isset($row['concerned_person_phone'])){
					$concerned_person_phone=trim($row['concerned_person_phone']);
					$row['concerned_person_phone'] = str_replace(' ', '', $concerned_person_phone);
				}


				$records = Businesses::where('unique_identification_number', '=', General::encrypt(strtoupper($tempValue[$unique_identification_number])))
					->where('concerned_person_phone','=',General::encrypt(strtolower($tempValue['concerned_person_phone'])))
					// ->where('added_by', Auth::user()->id)
					->whereNull('deleted_at')
					->first();

					/*$isAlreadyExistingCustomerr="Yes" Skip basic validations */

					$isAlreadyExistingCustomer="No";
					if($records)
					{
						$isAlreadyExistingCustomer="Yes";
					}
					$status = General::validateBusinessBulkUploadData($row, $import->uniqueUrlCode,null,$isAlreadyExistingCustomer);

					if($status){
						$is_validation_error = true;

					}


				if (!empty($records) && CustomerHelper::isAlreadyExistingCustomer(Auth::user()->id, $records->id, 2)) {


					$skipAll = false;
					$fileToArray[0][$tempKey]['skip'] = false;
					$existing_customers_data[] = $tempValue[$unique_identification_number];
				} else {
					$new_business_customers_data[] = $tempValue[$unique_identification_number];
				}
			}


			if ($is_validation_error) {
				return redirect()->route('import-excel-business.issues', [$import->uniqueUrlCode])->with(['message' => 'No Records are imported due to format errors', 'alert-type' => 'error']);
			}

			$existing_business_customers_count = count(array_unique($existing_customers_data));
			$new_business_customers_count = count(array_unique($new_business_customers_data));

			if ($new_business_customers_count != 0 && $new_business_customers_count > $remainingCustomer) {
				$skipAll = true;
			} else {
				$skipAll = false;
			}

			if ($skipAll) {
				$remainingRecordss = $fileToArray[0];
				$totalSkippedRecordCount = 0;

				$temp = [];
				foreach ($remainingRecordss as $temp_key => $temp_value) {

					$checkNullArray = array_filter($temp_value);
					if(count($checkNullArray) > 0) {
						$remainingRecords[] = $temp_value;
						if (!in_array($temp_value[$unique_identification_number], $temp)) {
							$temp[] = $temp_value[$unique_identification_number];
							$totalSkippedRecordCount++;
						}
					}
				}

				if ($remainingCustomer <= 0) {
					$totalSkippedRecordCount = $new_business_customers_count;
				}

				if ($remainingCustomer > 0) {
					$totalSkippedRecordCount = abs($remainingCustomer - $new_business_customers_count);
				}

				Log::debug('totalSkippedRecordCount = '.$totalSkippedRecordCount);

				$SkippedDuesRecord = new SkippedDuesRecord();
				$SkippedDuesRecord->user_id = Auth::user()->id;
				$SkippedDuesRecord->request_data = json_encode($fileToArray[0]);
				$SkippedDuesRecord->total_skipped_record_count = $totalSkippedRecordCount;
				$SkippedDuesRecord->save();

				return view('admin.add-record.skipped-business-popup-import', compact('SkippedDuesRecord', 'remainingRecords', 'totalSkippedRecordCount'));
			}

			Excel::import($import, request()->file('file'));
			$totalRows = $import->getRowCount();
			$getRemainingRecords = $import->getRemainingRecords();

			/*One code hit transaction Api Call*/
			$duesCheck = BusinessDueFees::where('added_by', Auth::id())->where('due_amount', '>=', 500)->get();

			$checkOfferData = [];
			$TotalDueAmt = 0;
			foreach ($duesCheck as $dcKey => $dcVal) {
				$checkOfferData[] = $dcVal;
				$TotalDueAmt += $dcVal->due_amount;
			}

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
				return redirect()->route('import-excel-business.issues', [$import->uniqueUrlCode])->with(['message' => $totalRows['Updated'] . ' Record imported and because of format error ' . $totalRows['Skipped'] . ' record skipped', 'alert-type' => 'success']);
			} else {
				return redirect()->back()->with(['message' => $totalRows['Updated'] . ' Record imported and because of format error ' . $totalRows['Skipped'] . ' record skipped', 'alert-type' => 'success']);
			}
			// }
		} catch (\Exception $e) {
			$errorMsg = date('Y-m-d H:i:s') . "----business----" . $e->getMessage();
			error_log($errorMsg, 3, storage_path() . '/logs/bulkuploads.log');
			return redirect()->back()->with(['message' => 'Something is wrong', 'alert-type' => 'error']);
		}
	}

	public function importExcelIssues($uniqueUrlCode, $userId = "")
	{
		$authId = $userId != "" ? ($userId) : (Auth::id());
		$records = BusinessBulkUploadIssues::where('unique_url_code', General::encrypt(strtolower($uniqueUrlCode)))
			->where('status', 0)
			->where('added_by', $authId)
			->orderBy('id', 'ASC')
			->get();
		BusinessBulkUploadIssues::where('unique_url_code', General::encrypt(strtolower($uniqueUrlCode)))
			->where('added_by', $authId)
			->where('status', 0)
			->update(['status' => 1]);

		return view('admin.import-excel-business-issues', compact('records', 'userId'));
	}

	public function allRecords(Request $request)
	{

		$User = Auth::user();
		if (!is_null($request->getQueryString()) && ($User->email_verified_at == NULL  || $User->email_sent_at == NULL)) {
			//return redirect('admin/auth/verify');
		}
		$sectors = Sector::whereNull('deleted_at')->where('status', 1)->get();
		$states = State::where('country_id', 101)->get();
		$stateIds = [];
		foreach ($states as $state) {
			$stateIds[] = $state->id;
		}
		$cities = City::whereIn('state_id', $stateIds)->get();

		if (!empty($request->input('unique_identification_number')) || !empty($request->input('concerned_person_name')) || !empty($request->input('concerned_person_phone')) || !empty($request->input('company_name')) || !empty($request->input('sector_id')) || !empty($request->input('due_amount')) || !empty($request->input('due_date_period')) || !empty($request->input('state_id'))) {

			$records = BusinessDueFees::with(['addedBy', 'profile'])->whereHas('addedBy')->whereHas('profile', function ($q) use ($request) {
				if (!empty($request->input('company_name'))) {

					$q->where('businesses.company_name', 'LIKE', General::encrypt($request->input('company_name')));
				}
				if (!empty($request->input('unique_identification_number'))) {

					$q->where('businesses.unique_identification_number', 'LIKE', General::encrypt($request->input('unique_identification_number')));
				}
				if (!empty($request->input('concerned_person_name'))) {

					$q->where('businesses.concerned_person_name', 'LIKE', General::encrypt($request->input('concerned_person_name')));
				}
				if (!empty($request->input('concerned_person_phone'))) {

					$q->where('businesses.concerned_person_phone', 'LIKE', General::encrypt($request->input('concerned_person_phone')));
				}
				if (!empty($request->input('sector_id'))) {

					$q->where('businesses.sector_id', $request->input('sector_id'));
				}
				if (!empty($request->input('state_id'))) {

					$q->where('businesses.state_id', $request->input('state_id'));
					if (!empty($request->input('city_id'))) {
						$q->where('businesses.city_id', $request->input('city_id'));
					}
				}
			})->whereNull('deleted_at');

			if (!empty($request->input('due_date_period'))) {

				$dueDatePeriod = $request->input('due_date_period');
				if ($dueDatePeriod == 'less than 30days') {
					$records = $records->whereRaw(" datediff(CURDATE(),due_date) < 30 ");
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


			if (!empty($request->input('due_amount'))) {
				$records = $records->withCount([
					'paid AS totalPaid' => function ($query) {
						$query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at');
					}
				]);
			}

			//$records = $records->where('totalPaid','<',600);

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
		} else {

			$records = Collection::make();
		}
		$canRequestConsent = Collection::make();
		$next3MinForCounDown = '';
		$requestConsentCheckStatus = false;
		if ($records->count()) {
			$canRequestConsent = General::requestConsentEligible(Auth::id(), $request->concerned_person_phone, 'BUSINESS');
			if ($canRequestConsent->count()) {
				if ($canRequestConsent->count() < 2) {
					$canRequestConsentFirstRecord = $canRequestConsent->first();
					$next3MinForCounDown = Carbon::createFromFormat('Y-m-d H:i:s', $canRequestConsentFirstRecord->created_at);
					$next3MinForCounDown->addMinute(3);
					$next3MinForCounDown = $next3MinForCounDown->format('F d,Y H:i:s');
				}
			}
			$requestConsentCheckStatus = General::requestConsentCheckStatus(Auth::id(), $request->concerned_person_phone, $request->unique_identification_number, 'BUSINESS');
		}
		if (!empty($request->concerned_person_phone)) {
			General::updateConsentRequestSearchedAtToLatest(Auth::id(), $request->concerned_person_phone, $request->unique_identification_number, 'BUSINESS');
		}

		$consentListing = consentRequest::with('payment')
			->where('added_by', Auth::id())
			->where('customer_type', '=', 'BUSINESS')
			//->where('is_expired_by_admin',2)
			->orderBy('searched_at', 'DESC')
			->get();
		$currentTime = Carbon::now();
		return view('admin.business.all-records.index', compact('records', 'sectors', 'states', 'cities', 'canRequestConsent', 'next3MinForCounDown', 'requestConsentCheckStatus', 'consentListing', 'currentTime'));
	}

	public function searchRequestConsent(Request $request)
	{
		$dueId = '';
		$records = BusinessDueFees::with(['addedBy', 'profile'])->whereHas('addedBy')->whereHas('profile', function ($q) use ($request) {
			if (!empty($request->input('unique_identification_number'))) {

				$q->where('businesses.unique_identification_number', 'LIKE', General::encrypt($request->input('unique_identification_number')));
			}
			if (!empty($request->input('contact_phone'))) {

				$q->where('businesses.concerned_person_phone', 'LIKE', General::encrypt($request->input('contact_phone')));
			}
		})
			->whereNull('deleted_at');
		$records = $records->orderBy('id', 'DESC');
		$records = $records->get();
		foreach ($records as $data) {
			if (!Auth::user()->hasRole('admin')) {
				$dueId .= $data->id . ',';
			}
		}
		$dueId = trim($dueId, ",");
		return $dueId;
	}

	public function businessData($businessId)
	{
		$businessDueData = BusinessDueFees::select('business_due_fees.id As dueId', 'business_due_fees.business_id', 'due_amount', 'due_date', 'business_due_fees.created_at As ReportedAt', 'paid_amount', 'paid_date', 'due_note', 'customer_no', 'invoice_no', 'users.business_name', 'users.id as userId', 'user_types.name as userType')
			->leftJoin('business_paid_fees', 'business_due_fees.business_id', '=', 'business_paid_fees.business_id')
			->leftJoin('users', 'users.id', '=', 'business_due_fees.added_by')
			->leftJoin('user_types', 'users.user_type', '=', 'user_types.id')
			->where('business_due_fees.business_id', '=', $businessId)
			->whereNull('business_due_fees.deleted_at')
			->groupBy('business_due_fees.id')
			->orderBy('business_due_fees.created_at', 'DESC')
			->get();
		$business = Businesses::where('id', '=', $businessId)->first();
		//dd($studentDueData);
		return view('admin.business.all-records.business-data', compact('businessDueData', 'business', 'businessId'));
	}

	public function storeDueAmount($studentId, Request $request)
	{
		$validator = Validator::make($request->all(), [
			'contact_phone' => 'required|digits:10,10',
			'due_date' => 'required|date',
			'due_amount' => 'required|numeric|gte:500|lte:1000000000',
			'agree_terms' => 'required',
		]);
		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}
		if ($studentId == '') {

			return redirect()->back()->withError('Error: Student id is not given');
		}

		$student = Students::where('id', '=', $studentId)->first();

		if (empty($student)) {

			return redirect()->back()->withError('Error: Student record not exists');
		}

		$valuesForStudent = [
			'contact_phone' => $request->input('contact_phone'),
			'updated_at' => Carbon::now(),
		];

		$student->update($valuesForStudent);

		$valuesForStudentDueFees = [
			'student_id' => $studentId,
			'due_date' => Carbon::now(),
			'due_amount' => $request->input('due_amount'),
			'due_note' => $request->input('due_note'),
			'created_at' => Carbon::now()
		];

		$studentFee = StudentDueFees::create($valuesForStudentDueFees);
		if ($studentFee->id == '') {

			return redirect()->back()->withError('Error: Paid Amount not stored');
		}

		return redirect()->route('student-data', $studentId)->withMessage('Success: Outstanding Amount stored');
	}


	public function reportedBy($id)
	{


		$businessDueData = BusinessDueFees::select('business_due_fees.id As dueId', 'business_due_fees.business_id', 'due_amount', 'due_date', 'business_due_fees.created_at As ReportedAt', 'paid_amount', 'paid_date', 'due_note', 'customer_no', 'invoice_no', 'users.business_name', 'users.id as userId', 'user_types.name as userType', 'businesses.company_name')
			->leftJoin('business_paid_fees', 'business_due_fees.business_id', '=', 'business_paid_fees.business_id')
			->leftJoin('users', 'users.id', '=', 'business_due_fees.added_by')
			->leftJoin('user_types', 'users.user_type', '=', 'user_types.id')
			->leftJoin('businesses', 'businesses.id', '=', 'business_due_fees.business_id')
			->where('business_due_fees.added_by', '=', $id)
			->whereNull('business_due_fees.deleted_at')

			//->orderBy('businesses.firstname','asc')
			->orderBy('business_due_fees.id', 'desc')
			->groupBy('business_due_fees.id')
			->get();

		return view('admin.business.all-records.business-reported', compact('businessDueData'));
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
		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}
		if ($studentId == '') {
			//dd(1);
			return redirect()->back()->withError('Error: Student id is not given');
		}

		$student = Students::where('id', '=', $studentId)->first();

		if (empty($student)) {
			//dd(2);
			return redirect()->back()->withError('Error: Student record not exists');
		}

		$valuesForStudent = [
			'contact_phone' => $request->input('contact_phone'),
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


		$valuesForStudentPayFees = [
			'student_id' => $studentId,
			'due_id' => $request->input('outstanding'),
			'paid_date' => Carbon::now(),
			'paid_amount' => $request->input('pay_amount'),
			'paid_note' => $request->input('due_note'),
			'created_at' => Carbon::now()
		];

		$studentFee = StudentPaidFees::create($valuesForStudentPayFees);
		if ($studentFee->id == '') {
			//dd(3);
			return redirect()->back()->withError('Error: Paid Amount not stored');
		}

		return redirect()->route('student-data', $studentId)->withMessage('Success: Paid Amount stored');
	}

	public function paymentHistory(Request $request)
	{
		//return Response::json(['error' => true,'message'=>'Record not found. Please try again'], 300);
		$dueId = $request->input('due_id');
		if (empty($dueId)) {
			return Response::json(['error' => true, 'message' => 'Due id can not be null'], 300);
		}
		$paymentHistory = StudentPaidFees::select('id', 'paid_date', 'paid_amount', 'paid_note', 'deleted_at')->where('due_id', $dueId)->orderBy('id', 'DESC')->get();
		$paymentHistoryData = [];
		if ($paymentHistory->count()) {
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
		//return Response::json(['error' => true,'message'=>'Record not found. Please try again'], 300);
		$paymentId = $request->input('payment_id');
		if (empty($paymentId)) {
			return Response::json(['error' => true, 'message' => 'can not find payment history'], 300);
		}

		$paymentHistory = StudentPaidFees::where('id', $paymentId)->whereNull('deleted_at')->first();

		if (!empty($paymentHistory)) {

			$paymentHistory->deleted_at = Carbon::now();
			$paymentHistory->update();
			return Response::json(['success' => true, 200]);
		} else {
			return Response::json(['error' => true, 'message' => 'can not find payment history'], 300);
		}
	}

	public function deleteDue(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'due_id' => 'required',
			'delete_note' => 'required',
			'agree_terms' => 'required',
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

		$studentDue = StudentDueFees::where('id', $dueId)->whereNull('deleted_at')->first();
		if (empty($studentDue)) {
			return redirect()->back()->withErrors(['can not find due record']);
		}

		$studentDue->deleted_at = Carbon::now();
		$studentDue->delete_note = $deleteNote;
		$studentDue->update();
		return redirect()->back()->withMessage('successfully deleted');
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
		$loginId = Auth::user()->role_id;
		return Excel::download(new BusinessesExport($loginId,"0","businessExport",$fromdate,$todate,$dropDownType,""), 'BusinessCustomers.xlsx');
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
		$file_name='BusinessInvoicePayments-';
		$is_customerPayments="";
		$paymentsOnly = 1;
		if(isset($request->customerpayments))
		{
			$is_customerPayments="2";
			$paymentsOnly = 2;
			$file_name='BusinessCustomerPayments-';
		}

		$todate=date('Y-m-d',strtotime('+1 day', strtotime($todate)));
		$loginId = Auth::user()->role_id;

		return Excel::download(new BusinessesExport($loginId,$paymentsOnly,"businessExport",$fromdate,$todate,$dropDownType,$is_customerPayments), $file_name. Carbon::now() . '.xlsx');
	}

	public function recordsForSms(Request $request)
	{
		$User = Auth::user();
		if (!is_null($request->getQueryString()) && ($User->email_verified_at == NULL  || $User->email_sent_at == NULL)) {
			//return redirect('admin/auth/verify');
		}
		$organizationId = $request->input('organization_id');
		$authId = Auth::id();
		$currentDate = Carbon::now();
		$sectors = Sector::whereNull('deleted_at')->where('status', 1)->get();
		$states = State::where('country_id', 101)->get();
		$stateIds = [];
		foreach ($states as $state) {
			$stateIds[] = $state->id;
		}
		$cities = City::whereIn('state_id', $stateIds)->get();
		$records = BusinessDueFees::with(['addedBy', 'profile'])->whereHas('addedBy')->whereHas('profile', function ($q) {
			$q->where('businesses.concerned_person_phone', '!=', '');
		})->whereNull('deleted_at');

		if (!empty($organizationId)) {
			$records = $records->where('added_by', $organizationId);
		}
		if (!empty($request->input('due_date_period'))) {

			$dueDatePeriod = $request->input('due_date_period');
			if ($dueDatePeriod == 'less than 30days') {
				$records = $records->whereRaw(" datediff(CURDATE(),due_date) < 30 ");
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
			'paid AS totalPaid' => function ($query) use ($authId, $organizationId) {
				$query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at');
				if (!empty($organizationId)) {
					$query->where('added_by', $organizationId);
				}
			}
		]);
		// $records = $records->groupBy(['due_date','added_by']);
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
		$smsTemplates = \Config::get('sms_templates');
		$organizations = User::select('id', 'business_short')->where('status', 1)->whereNotNull('email_verified_at')->get();
		$authUser = null;
		if (!empty($request->organization_id)) {
			$authUser = User::where('status', 1)->whereNotNull('email_verified_at')->where('id', $request->organization_id)->first();
		}
		return view('admin.business.all-records.send-sms', compact('records', 'organizations', 'smsTemplates', 'authUser'));
	}


	public function recordsSendSms(Request $request)
	{
		//	dd($request->all());
		$validator = Validator::make(
			$request->all(),
			[
				'ids' => 'required',
				'template_id' => 'required_without:message',
				'message' => 'required_without:template_id|string|max:145',
				'within_date' => 'nullable|date',
			],
			[
				'ids.required' => 'Select records to send sms',
				'template_id.required_without' => 'Template is required when message is blank',
				'message.required_without' => 'The :attribute is required when no template is choosen',
			]
		);
		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

		$ids = explode(",", $request->ids);
		$AuthId = Auth::id();

		$businessList = BusinessDueFees::with(['profile', 'addedBy'])->whereHas('profile', function ($q) {
			$q->whereNotNull('concerned_person_phone')->where('concerned_person_phone', '!=', '');
		})->whereIn('id', $ids)->get();

		if (!$businessList->count()) {
			return redirect()->back()->with(['message' => "can not send sms ", 'alrert-type' => 'erro']);
		}

		$template_id = $request->template_id;
		$message = $request->message;
		if (empty($message)) {
			$message = \Config::get('sms_templates.' . $template_id . '.text');
			if (empty($message)) {
				return redirect()->back()->with(['message' => 'can not find template', 'alert-type' => 'error']);
			}
		}
		$withinDate = $request->within_date;
		$sent = true;
		$smsService = new SmsService();
		foreach ($businessList as $data) {

			$authUser = $data->addedBy;
			if ($template_id) {
				$message = General::replaceTextInSmsTemplate($template_id, 'BUSINESS', $authUser, $withinDate, '', $data);
			}
			$message = strip_tags($message);
			$smsResponse = $smsService->sendSms($data->profile->concerned_person_phone, $message);
			if ($smsResponse['fail_to_send']) {
				$sent = false;
			}

			$insert = [
				'contact_phone' => $data->profile->concerned_person_phone,
				'customer_id' => $data->profile->id,
				'due_id' => $data->id,
				'customer_type' => 'Business',
				'created_at' => Carbon::now(),
				'added_by' => $AuthId,
				'message' => $message,
				'approve_reject_status' => 1,
				'approve_reject_at' => Carbon::now()
			];
			if ($smsResponse['sent'] == 1) {
				$insert['status'] = 1;
			} else {
				$insert['status'] = 2;
			}
			DuesSmsLog::create($insert);
		}

		if (!$sent) {
			return redirect()->back()->withInput()->with(['message' => "can not send sms to some phones. Server unavailable.", 'alert-type' => 'error']);
		}
		return redirect()->back()->with(['message' => "SMS sent successfully.", 'alert-type' => 'info']);
	}


	public function recordsSentSms(Request $request)
	{
		$AuthId = Auth::id();
		$records = DuesSmsLog::with('business')->where('customer_type', '=', 'Business')->where('added_by', $AuthId)->orderBy('created_at', 'DESC')->paginate(50);

		return view('admin.business.all-records.sent-sms', compact('records'));
	}

	public function consentPayment($consentId, Request $request)
	{
		Log::debug('Consent Paymnent');
		$count_consentRequest = ConsentRequest::where('added_by',Auth::id())->get();
		// $consentRequest = ConsentRequest::where('id', $consentId)->where('status', 3)->where('added_by', Auth::id())->first();
		$consentRequest = $count_consentRequest->where('id',$consentId)->where('status',3)->first();
		$count_rec_b2c = $count_consentRequest->where('report','2');
		$count_rec_b2b = $count_consentRequest->where('report','3');
		$free_limit_b2c = config('custom_configs.free_limit_b2c');
		$free_limit_b2b = config('custom_configs.free_limit_b2b');
		$total_free_reports=config('custom_configs.total_free_reports');
		if(count($count_rec_b2c)<=$free_limit_b2c && count($count_rec_b2b)<=$free_limit_b2b && count($count_rec_b2b)+count($count_rec_b2c)<=$total_free_reports){

             if($consentRequest->report==2 || $consentRequest->report==3){
           		 if($consentRequest->report==2){
           		 $consent_payment_value = $consentRequest->report == 2 ? HomeHelper::getConsentComprehensiveReportPrice() : HomeHelper::getConsentRecordentReportPrice();
            	}
            if($consentRequest->report==3){
          		  $consent_payment_value = $consentRequest->report == 3 ? HomeHelper::getConsentComprehensiveReportPrice($consentRequest->report==3) : HomeHelper::getConsentRecordentReportPrice();
           	 }
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


        // if($consentRequest->report == 2 || $consentRequest->report == 3){
		        if($consentRequest->report == 2){
		            $invoice_type_id = $consentRequest->customer_type=="INDIVIDUAL" ? 3 : 5;
		        }
		        if($consentRequest->report == 3){
		            $invoice_type_id = $consentRequest->customer_type=="BUSINESS" ? 2 : 4;
		        }
		         $invoice_no = MembershipPayment::where('created_at','>=',date('Y-m-d 00:00:00'))->where('status',4)->count();
		         $invoice_no=$invoice_no+1;
		         $user=User::findOrFail($consentRequest->added_by);
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
		if (empty($consentRequest)) {
			return redirect()->back()->withInput()->with(['message' => "Something went wrong", 'alert-type' => 'error']);
		}

		$consent_payment_successful_valid_for_in_year = setting('admin.consent_payment_successful_valid_for_in_year') ? (int)setting('admin.consent_payment_successful_valid_for_in_year') : 7;

		$currentTime = Carbon::now();
		$beforeDateTime = Carbon::now()->subYear($consent_payment_successful_valid_for_in_year);

		$consentPaymentAlready = ConsentPayment::where('consent_id', $consentRequest->id)
			->where('status', 4)
			->where('added_by', Auth::id())
			->where('updated_at', '>=', $beforeDateTime)
			->where('customer_type', '=', 'BUSINESS')
			->orderBy('id', 'DESC')
			->first();
		if (!empty($consentPaymentAlready)) {
			return redirect()->back()->withInput()->with(['message' => "Already paid for this consent. No need to pay again", 'alert-type' => 'error']);
		}
		// $consent_payment_value = setting('admin.consent_payment_value') ? (int)setting('admin.consent_payment_value') : 100 ;
		// $consent_payment_value_gst_in_perc = setting('admin.consent_payment_value_gst_in_perc') ? (int)setting('admin.consent_payment_value_gst_in_perc') : 0 ;

		if (Auth::user()->user_pricing_plan != NULL) {
			$consent_payment_value = $consentRequest->report == 3 ? HomeHelper::getConsentComprehensiveReportPrice($consentRequest->report==3) : HomeHelper::getConsentRecordentReportPrice();
			$consent_payment_value_gst_in_perc = HomeHelper::getConsentRecordentReportGst();
		} else {
			$consent_payment_value = setting('admin.consent_payment_value') ? (int)setting('admin.consent_payment_value') : 100;
			$consent_payment_value_gst_in_perc = setting('admin.consent_payment_value_gst_in_perc') ? (int)setting('admin.consent_payment_value_gst_in_perc') : 0;
		}

		if ($consent_payment_value_gst_in_perc > 0) {
			$temp = ($consent_payment_value * $consent_payment_value_gst_in_perc) / 100;
			$temp = round($temp);
			$temp = (int)$temp;
			$consent_payment_value = $consent_payment_value + $temp;
		}


		$consentPayment = ConsentPayment::create([
			'order_id' => Str::random(40),
			'customer_type' => $consentRequest->customer_type,
			'unique_identification_number' => $consentRequest->unique_identification_number,
			'concerned_person_phone' => $consentRequest->concerned_person_phone,
			'consent_id' => $consentRequest->id,
			'payment_value' => $consent_payment_value,
			'status' => 1, //initiated
			'created_at' => Carbon::now(),
			'added_by' => Auth::id(),
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
		]);
		//dd(route('admin.consent.payment-callback'));
		$userDataToPaytm = User::findOrFail(Auth::user()->id);
		$userDataToPaytm_name = preg_replace('/\s+/', '_', $userDataToPaytm->name);

		$consentPayment->pg_type = setting('admin.payment_gateway_type');
		$consentPayment->update();

		if (setting('admin.payment_gateway_type') == 'paytm') {
			$payment = PaytmWallet::with('receive');
			$payment->prepare([
				'order' => $consentPayment->order_id,
				'user' => $userDataToPaytm_name,
				'mobile_number' => $userDataToPaytm->mobile_number,
				'email' => $userDataToPaytm->email,
				'amount' => $consent_payment_value,
				'callback_url' => route('admin.business.consent.payment-callback')
			]);

			return $payment->view('admin.payment-submit')->receive();
		} else {

			$postData = [
				'amount' => $consent_payment_value,
				'txnid' => $consentPayment->order_id,
				'firstname' => preg_replace('/\s+/', '', $userDataToPaytm->name),
				'email' => $userDataToPaytm->email,
				'phone' => $userDataToPaytm->mobile_number,
				'surl' => route('admin.business.consent.payment-callback'),
			];

			$payuForm = General::generatePayuForm($postData);

			return view('admin.payment-submit', compact('payuForm'));
		}
	}

	public function consentPaymentCallback(Request $request)
	{
		Log::debug('Consent Reports Paymnent Callback');
		if (setting('admin.payment_gateway_type') == 'paytm') {
			$transaction = PaytmWallet::with('receive');
			try {
				$response = $transaction->response();
			} catch (\Exception $e) {
				//add to db log
				return redirect()->route('business.all-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		} else {
			try {
				$response = General::verifyPayuPayment($request->all());
				if (!$response) {
					return redirect()->route('business.all-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
				}
			} catch (\Exception $e) {
				return redirect()->route('business.all-records')->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		}

		//dd($response);
		$consentPayment = ConsentPayment::where('order_id', '=', $response['ORDERID'])
			->where('status', 1)
			->where('added_by', Auth::id())
			->first();
		if (empty($consentPayment)) {
			return redirect()->route('business.all-records')->with(['message' => "Invalid consent payment", 'alert-type' => 'error']);
		}

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

		$consentPayment->transaction_id = $response['TXNID'] ?? $response['mihpayid'] ?? '';
		$consentPayment->payment_mode = $response['PAYMENTMODE'] ?? $response['mode'] ?? '';

		if ($paymentStatus == 'success') {
			$consentPayment->status = 4;
			$alertType = 'success';
			$message = 'Payment successful.';

			$consentRequest = ConsentRequest::where('id', $consentPayment->consent_id)->first();

			// $invoice_no=MembershipPayment::where('created_at','>=',date('Y-m-d 00:00:00'))->where(function($q) {
			// $q->where('status',4)
			//     ->orWhere('postpaid', 0);
			// })->count();
			$invoice_no = MembershipPayment::where('created_at', '>=', date('Y-m-d 00:00:00'))->where('status', 4)->count();
			$invoice_no = $invoice_no + 1;

			if (Auth::user()->user_pricing_plan != NULL) {
				$consent_payment_value = $consentRequest->report == 3 ? HomeHelper::getConsentComprehensiveReportPrice($consentRequest->report==3) : HomeHelper::getConsentRecordentReportPrice();
				$consent_payment_value_gst_in_perc = HomeHelper::getConsentRecordentReportGst();
			} else {
				$consent_payment_value = setting('admin.consent_payment_value') ? (int)setting('admin.consent_payment_value') : 100;
				$consent_payment_value_gst_in_perc = setting('admin.consent_payment_value_gst_in_perc') ? (int)setting('admin.consent_payment_value_gst_in_perc') : 0;
			}

			if ($consent_payment_value_gst_in_perc > 0) {
				$temp = ($consent_payment_value * $consent_payment_value_gst_in_perc) / 100;
				$temp = round($temp);
				$temp = (int)$temp;
				$consent_payment_value_final = $consent_payment_value + $temp;
			}

			$valuesForMembershipPayment = [
				'customer_id' => Auth::user()->id,
				'invoice_id' => date('dmY') . sprintf('%07d', $invoice_no),
				'pricing_plan_id' => 0,
				'customer_type' => "BUSINESS",
				'payment_value' => $consent_payment_value,
				'gst_perc' => $consent_payment_value_gst_in_perc,
				'gst_value' => $temp,
				'total_collection_value' => $consent_payment_value_final,
				'particular' => "Business " . ($consentRequest->report == 1 ? "Recordent Report" : "Recordent Comprehensive Report"),
				'consent_id' => $consentRequest->id,
				'invoice_type_id' => $consentRequest->report == 3 ? 5 : 4,
				'postpaid' => 0,
				'status' => 4,
			];
			$membershipPayment = MembershipPayment::create($valuesForMembershipPayment);
			Log::debug('Insert into Membership Payment');

		} else if ($paymentStatus == 'failed') {
			$consentPayment->status = 5;
			$alertType = 'error';
			$message = 'Payment failed.';
		} else {
			$consentPayment->status = 2;
			$alertType = 'info';
			$message = 'Payment is in progress.';
		}

		$consentPayment->raw_response = json_encode($response);
		$consentPayment->updated_at = Carbon::now();
		$consentPayment->update();

		if(isset($membershipPayment)){
          	app('App\Http\Controllers\HomeController')->postpaid_invoice_sendmail($membershipPayment->id);
        }

		return redirect()->route('all-records')->with(['message' => $message, 'alert-type' => $alertType]);
	}

	public function checkConsentStatus($consentId)
	{
		$consentList = consentRequest::with('payment')
			->where('added_by', Auth::id())
			->where('id', $consentId)
			->where('customer_type', '=', 'BUSINESS')
			//->where('is_expired_by_admin',2)
			->first();
		if (empty($consentList)) {
			return Response::json(['error' => true, 'message' => 'No record found'], 500);
		}
		$lastConsent =  consentRequest::where('added_by', Auth::id())
			->where('customer_type', '=', 'BUSINESS')
			->orderBy('searched_at', 'DESC')
			->first();

		$withHtml = View('admin.business.all-records.consent-payment-list.check-status-row', compact('consentList', 'lastConsent'))->render();
		return Response::json(['success' => true, 'message' => 'Succefully checked', 'newStatus' => $withHtml], 200);
	}


	public function importDuePayment(Request $request)
	{
		$import = new BusinessDuePaymentImport;
		$import->uniqueUrlCode = strtolower(Str::random(10));
		try {

			$fileToArray = (new BusinessDuePaymentImport)->toArray(request()->file('file'));
			//dd($fileToArray);
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
				return redirect()->route('import-business-due-payment.issues', [$import->uniqueUrlCode])->with(['message' => $totalRows['Updated'] . ' Record imported and because of format error ' . $totalRows['Skipped'] . ' record skipped', 'alert-type' => 'success']);
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
		return view('admin.import.import-business-due-payment-excel-issues', compact('records'));
	}

	// public function importSuperExcel($userId)
	//     {
	//     	//echo $userId;die()
	// 		$UploadFile = false;
	// 		if(General::checkMemberEligibleToUploadPaymentMasterFile()){
	// 			$UploadFile = true;
	// 			}
	// 		$showUploadFile = array("isShow"=>$UploadFile);
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
			'created_at' => Carbon::now()
		];

		BusinessAdminReports::create($Report_Data);

		return redirect()->back()->with(['message' => "Successfully uploaded", 'alert-type' => 'success']);

	}

	public function importExcelSuper(Request $request, $userId)
	{
		Log::debug('import-excel-business super admin');
		ini_set('max_execution_time', 0);

		Session::put('member_id', $userId);

		$import = new BusinessesImport;
		$import->uniqueUrlCode = Str::random(10);
		try {
			$fileToArray = (new BusinessesImport)->toArray(request()->file('file'));
		} catch (\Exception $e) {
			Log::debug('e->getMessage() = '.print_r($e->getMessage(), true));
			return redirect()->back()->with(['message' => 'Something is wrong', 'alert-type' => 'error']);
		}

		$columnNames = array_keys($fileToArray[0][0]);
		if($columnNames[2]== 'unique_identification_number_gstin_business_pan'){
            $unique_identification_number = 'unique_identification_number_gstin_business_pan';
        } else {
            $unique_identification_number = 'unique_identification_number';
        }

		try {
			// $remainingCustomer = General::getFreeCustomersDuesLimit($userId);
			$remainingCustomer = CustomerHelper::getRemainingFreeCustomersDuesLimit($userId);
			Log::debug('remainingCustomerLimit = '.$remainingCustomer);

			if ( General::validateBusinessBulkExcelImportColumns($columnNames, $unique_identification_number) ) {
				Session::flash('message', 'Error: Some fields are missing in the sheet');
            	return redirect()->back();
			}

			if ( General::validateBusinessBulkExcelImportColumnsFormat($columnNames, $unique_identification_number) ) {
				Session::flash('message', 'Error: Format not same as Master Template. Recheck and upload');
				return redirect()->back();
			}

			if ($remainingCustomer >= count($fileToArray[0])) {
				$recordsToAllowCount = count($fileToArray[0]);
			} else {
				$recordsToAllowCount = $remainingCustomer;
			}

			$totalSkippedRecordCount = count($fileToArray[0]) - $recordsToAllowCount;
			Log::debug('recordsToAllowCount = '.$recordsToAllowCount);
			Log::debug('totalSkippedRecordCount = '.$totalSkippedRecordCount);

			$skipAll = true;
			$new_business_customers_data = array();
			$existing_customers_data = array();

			$existing_business_customers_count = 0;
			$new_business_customers_count = 0;

			$is_validation_error = false;

			foreach ($fileToArray[0] as $tempKey => $tempValue) {

				$row = (array)$tempValue;


				$records = Businesses::where('unique_identification_number', '=', General::encrypt(strtoupper($tempValue[$unique_identification_number])))
					->where('concerned_person_phone','=',General::encrypt(strtolower($tempValue['concerned_person_phone'])))
					// ->where('added_by', $userId)
					->whereNull('deleted_at')
					->first();

					/*$isAlreadyExistingCustomerr="Yes" Skip basic validations */

					$isAlreadyExistingCustomer="No";
					if($records)
					{
						$isAlreadyExistingCustomer="Yes";
					}

					$status = General::validateBusinessBulkUploadData($row, $import->uniqueUrlCode, $userId,$isAlreadyExistingCustomer);

					if($status){
						$is_validation_error = true;
					}

				if (!empty($records) && CustomerHelper::isAlreadyExistingCustomer($userId, $records->id, 2)) {
					$skipAll = false;
					$fileToArray[0][$tempKey]['skip'] = false;
					$existing_customers_data[] = $tempValue[$unique_identification_number];
				} else {
					$new_business_customers_data[] = $tempValue[$unique_identification_number];
				}
			}

			if ($is_validation_error) {
				return redirect()->route('import-excel-business.issues', [$import->uniqueUrlCode, $userId])->with(['message' => 'No Records are imported due to format errors', 'alert-type' => 'error']);
			}

			$existing_business_customers_count = count(array_unique($existing_customers_data));
			$new_business_customers_count = count(array_unique($new_business_customers_data));

			if ($new_business_customers_count != 0 && $new_business_customers_count > $remainingCustomer) {
				$skipAll = true;
			} else {
				$skipAll = false;
			}

			if ($skipAll) {
				$remainingRecordss = $fileToArray[0];
				$totalSkippedRecordCount = 0;
				$temp = [];
				foreach ($remainingRecordss as $temp_key => $temp_value) {
					$checkNullArray = array_filter($temp_value);
					if(count($checkNullArray) > 0) {
						$remainingRecords[] = $temp_value;
						if (!in_array($temp_value[$unique_identification_number], $temp)) {
							$temp[] = $temp_value[$unique_identification_number];
							$totalSkippedRecordCount++;
						}
					}
				}

				if ($remainingCustomer <=0) {
					$totalSkippedRecordCount = $new_business_customers_count;
				}

				if ($remainingCustomer > 0) {
					$totalSkippedRecordCount = abs($remainingCustomer - $new_business_customers_count);
				}

				Log::debug('totalSkippedRecordCount = '.$totalSkippedRecordCount);

				$SkippedDuesRecord = new SkippedDuesRecord();
				$SkippedDuesRecord->user_id = $userId;
				$SkippedDuesRecord->request_data = json_encode($fileToArray[0]);
				$SkippedDuesRecord->total_skipped_record_count = $totalSkippedRecordCount;
				$SkippedDuesRecord->save();
				return view('admin.add-record.skipped-business-popup-import', compact('SkippedDuesRecord', 'remainingRecords', 'totalSkippedRecordCount'));
			}

			Excel::import($import, request()->file('file'));
			Session::remove('member_id');
			$totalRows = $import->getRowCount();
			if ($import->atLeastIssue === true) {
				return redirect()->route('import-excel-business.issues', [$import->uniqueUrlCode, $userId])->with(['message' => $totalRows['Updated'] . ' Record imported and because of format error ' . $totalRows['Skipped'] . ' record skipped', 'alert-type' => 'success']);
			} else {
				return redirect()->back()->with(['message' => $totalRows['Updated'] . ' Record imported and because of format error ' . $totalRows['Skipped'] . ' record skipped', 'alert-type' => 'success']);
			}
		} catch (\Exception $e) {
			$errorMsg = date('Y-m-d H:i:s') . "----business----" . $e->getMessage();
			error_log($errorMsg, 3, storage_path() . '/logs/bulkuploads.log');
			return redirect()->back()->with(['message' => 'Something is wrong', 'alert-type' => 'error']);
		}
	}

	public function importUpdateProfile(Request $request, $userId)
	{
		$import = new BusinessUpdateProfileImport;
		$import->uniqueUrlCode = strtolower(Str::random(10));
		Session::put('member_id', $userId);
		try {

			$fileToArray = (new BusinessUpdateProfileImport)->toArray(request()->file('file'));
		} catch (\Exception $e) {
			return redirect()->back()->with(['message' => 'Something is wrong', 'alert-type' => 'error']);
		}
		$columnNames = array_keys($fileToArray[0][0]);
		if (
			!in_array('customer_id', $columnNames) ||
			!in_array('state', $columnNames) ||
			!in_array('city', $columnNames) ||
			!in_array('email', $columnNames) ||
			!in_array('custom_id', $columnNames)

		) {
			Session::flash('message', 'Error: Some fields are missing in the sheet');
			return redirect()->back();
		}
		try {
			Excel::import($import, request()->file('file'));
			Session::remove('member_id');
			$totalRows = $import->getRowCount();
			if ($import->atLeastIssue === true) {
				return redirect()->route('import-excel-business-profile.issues', [$import->uniqueUrlCode, $userId])->with(['message' => $totalRows['Updated'] . ' Record imported and because of format error ' . $totalRows['Skipped'] . ' record skipped', 'alert-type' => 'success']);
			} else {
				return redirect()->back()->with(['message' => $totalRows['Updated'] . ' Record imported and because of format error ' . $totalRows['Skipped'] . ' record skipped', 'alert-type' => 'success']);
			}
		} catch (\Exception $e) {
		//dd($e);
			return redirect()->back()->with(['message' => 'Something is wrong', 'alert-type' => 'error']);
		}
	}
	public function importExcelProfileIssues($uniqueUrlCode, $userId="")
	{
		$authId = $userId != "" ? ($userId) : (Auth::id());
		$records = BusinessBulkUploadIssues::where('unique_url_code', General::encrypt($uniqueUrlCode))
			->where('status', 0)
			->where('added_by', $authId)
			->orderBy('id', 'ASC')
			->get();
		BusinessBulkUploadIssues::where('unique_url_code', General::encrypt($uniqueUrlCode))
			->where('added_by', $authId)
			->where('status', 0)
			->update(['status' => 1]);
		return view('admin.import-excel-business-profile-issues', compact('records'));
	}

	public function GetPopulateBasicDetails(Request $request){

		$GstPanNumber=$request->GstPanNumber;
		//Auth::id()
		$User_id=Auth::id();

		$business = Businesses::where('unique_identification_number', '=', General::encrypt($GstPanNumber))->where('added_by', '=', $User_id)->first();
		if(strlen($GstPanNumber) == 15)
		{
		   $gst_state_code = substr($GstPanNumber, 0, 2);
			$gst_state_code=$gst_state_code;
			$StateId=General::Gst_StateWise_Code($gst_state_code);
		}else{
			$StateId="";
		}
		if($business){
			if(strlen($GstPanNumber) == 15)
		{
			$gst_state_code = substr($GstPanNumber, 0, 2);
			$gst_state_code=$gst_state_code;
			$StateId=General::Gst_StateWise_Code($gst_state_code);
		}else{
			$StateId=$business['state_id'];
		}

		return Response::json(['success' => true,  'data' => $business,"StateId"=>$StateId], 200);

		}else{
			return Response::json(['success' => true, "StateId"=>$StateId], 200);
		}



	}
}
