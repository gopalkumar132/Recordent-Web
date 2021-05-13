<?php

namespace App\Http\Controllers;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\BusinessBulkUploadIssues;
use App\BusinessDueFees;
use App\Businesses;
use App\City;
use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\IndividualBulkUploadIssues;
use App\MembershipPayment;
use App\Sector;
use App\Students;
use App\StudentDueFees;
use App\StudentPaidFees;
use App\User;
use App\UserPricingPlan;
use Mail;
use App\Individuals;
use App\Services\SmsService;
use App\UsersOfferCodes;
use App\SkippedDuesRecord;
use App\State;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use Storage;
use General;
use Str;
use Session;
use App\TempMembershipPayment;
use Illuminate\Support\Facades\Mail as SendMail;
use HomeHelper;
use Log;
use CustomerHelper;
use CreditReportHelper;


class AddRecordController extends Controller
{

	public function index(Request $request)
	{	$usertype = Auth::user()->user_type;
		return view('admin.add-record.add-record',compact('usertype'));
	}


	/*
		Payment GateWay PayuBiz add request
	*/
	public function storereference(Request $request) {

		$user_pricing_plan = UserPricingPlan::where('user_id',Auth::id())->first();

		$consent_payment_value = $user_pricing_plan->usa_b2b_credit_report;
		$consent_payment_value_gst_perc = HomeHelper::getConsentRecordentReportGst();
		if ($consent_payment_value_gst_perc > 0) {
			$temp = ($consent_payment_value * $consent_payment_value_gst_perc) / 100;
			$temp = round($temp);
			$temp = (int)$temp;
			$consent_payment_value = $consent_payment_value + $temp;
		}

		//delete & then stored session value for business name.
		if(!empty(Session::get('business_name'))){
			Session::remove('business_name');
		}

		Session::put('business_name', $request->business_name);
		Session::put('address_line1', $request->address);
		Session::put('city_us', $request->city);
		Session::put('state_us', $request->state);
		Session::put('zip_us', $request->zip_code);

		$order_id = Str::random(40);

		$user_name = Auth::user()->name;
		$email_user = Auth::user()->email;
		$mobile_number = Auth::user()->mobile_number;

		if (Auth::user()->reports_us_business == 1) {

			$consentRequestInsert = CreditReportHelper::insertIntoConsentRequestTable();

			$consent_request_id = $consentRequestInsert->id?? 0;

			$response_api = CreditReportHelper::makeEquifaxApiCall();

			$consentPayment = CreditReportHelper::insertIntoConsentPaymentTable($order_id, $consentRequestInsert->id, $consent_payment_value, 4);

			if(empty($response_api) || (!empty($response_api->EfxTransmit->ProductCode[0]->value) && $response_api->EfxTransmit->ProductCode[0]->value=="Commercial - NoHit")) {

				$consentPayment->status = 5;
				$redirect_url = route('admin.consent.us-b2b-creditreport-no-hit-status', [base64_encode($request->business_name), $consent_payment_value]);

				$consent_api_response = CreditReportHelper::insertIntoConsentApiResponseTable($consent_request_id, $response_api, 3);				
			} else {
				Session::put('consent_request_us_id', $consentRequestInsert->id);
				$redirect_url = route('admin.consent.us-b2b-creditreport-success-status', [base64_encode($request->business_name), $consent_payment_value]);

				$consentPayment->invoice_id = CreditReportHelper::getConsentPaymentInvoiceId();
				
				$consent_api_response = CreditReportHelper::insertIntoConsentApiResponseTable($consent_request_id, $response_api, 1);
				
				CreditReportHelper::insertIntoMembershipPaymentsTable($consent_request_id);

				CreditReportHelper::usb2b_invoice_sendmail($consentPayment->id);
			}

			$consentPayment->save();

			return redirect($redirect_url);

		} else {

			$postData = [
							'amount'=>$consent_payment_value,
							'txnid'=> $order_id,
							'firstname' => preg_replace('/\s+/', '', $user_name),
							'email' => $email_user,
							'phone' => $mobile_number,
							'surl'=>route('admin.consent.payment-callback'),
						];

			General::add_to_debug_log(Auth::id(), "consent_payment_value = ".$consent_payment_value);
			$payuForm = General::generatePayuForm($postData);
			General::add_to_payment_debug_log(Auth::id(), 1);

			return view('admin.payment-submit',compact('payuForm'));
		}
	}


	/**
	 * @param Request Add Data
	 *
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */

	public function store(Request $request)
	{
		$requestData = $request->all();
		//dd($requestData);
		$remainingRecords = array();
		$recordsToAllowCount = 0;
		$totalSkippedRecordCount = 0;
		$remainingCustomer = General::getFreeCustomersDuesLimit(Auth::id());

		$due_date_old_in_year = setting('admin.due_date_old_in_year');
		$due_date_max_future_in_year = setting('admin.due_date_max_future_in_year');
		$dob_valid_from = Carbon::now()->subYears(100)->format('d/m/Y');
		$currentDate = Carbon::now();

		if ($due_date_old_in_year) {
			$due_date_old_in_year = $currentDate->subYears($due_date_old_in_year)->format('d/m/Y');
		}

		$currentDate = Carbon::now();
		if ($due_date_max_future_in_year) {
			$due_date_max_future_in_year = $currentDate->addYears($due_date_max_future_in_year)->format('d/m/Y');
		}

		$request->merge(['aadhar_number' => str_replace('-', '', $request->aadhar_number)]);
		$request->merge(['aadhar_number' => str_replace('_', '', $request->aadhar_number)]);

		if (!is_array($request->due_amount)) {
			$request->merge(['due_amount' => str_replace(',', '', $request->due_amount)]);
		} else {

			$request_dueamount_merge = [];
			foreach ($request->due_amount as $key => $val) {
				$request_dueamount_merge[] = str_replace(',', '', $val);
			}

			$request->merge(["due_amount" => $request_dueamount_merge]);
		}

		//dd($request); die;
		$aadhar_number = $request->aadhar_number;
		$contact_phone = $request->contact_phone;
		$customer_no = $request->customer_no;
		$invoice_no = $request->invoice_no;
		$person_name = $request->person_name;
		$dob = $request->dob != '' ? Carbon::createFromFormat('d/m/Y', $request->dob)->toDateTimeString() : '';
		$father_name = $request->father_name;
		$mother_name = $request->mother_name;
		//$external_student_id = $request->external_student_id;

		if (!is_array($request->due_date)) {
			$due_date = Carbon::createFromFormat('d/m/Y', $request->due_date)->toDateTimeString();
		} else {

			if ($remainingCustomer >= count($request->due_date)) {
				$recordsToAllowCount = count($request->due_date);
			} else {
				$recordsToAllowCount = $remainingCustomer;
			}

			$totalSkippedRecordCount = count($request->due_date) - $recordsToAllowCount;
			$due_date = $request->due_date;
		}


		$invoice_date = $request->invoice_date;
		// $invoice_date_new=array();
		// foreach($invoice_date as $key=>$val)
		// {
		// 	if($val == null)
		// 	{
		// 		$invoice_date_new[]='';
		// 	}else
		// 	{
		// 		$invoice_date_new[]=$val;
		// 	}
		// }
		// $invoice_date=$invoice_date_new;

		$credit_period = $request->credit_period;
		$credit_period_new=array();
		foreach($credit_period as $key=>$val)
		{
			if($val == null)
			{
				$credit_period_new[]='';
			}else
			{
				$credit_period_new[]=$val;
			}
		}
		$credit_period=$credit_period_new;
		// dd($remainingCustomer, $recordsToAllowCount, $totalSkippedRecordCount);
		$due_amount = $request->due_amount;
		$due_note = $request->due_note;
		$proof_of_due = $request->file('proof_of_due');
		$collection_date = $request->collection_date;
		$grace_period = $request->grace_period_hidden;
		$name_max_character= General::maxlength('name');

		$rule = [
			'dob' => 'nullable|date_format:d/m/Y|before_or_equal:today|after_or_equal:' . $dob_valid_from,
			'father_name' => 'nullable|max:'.$name_max_character.'|regex:/^[\pL\s]+$/u',
			'mother_name' => 'nullable|max:'.$name_max_character.'|regex:/^[\pL\s]+$/u',
			'aadhar_number' => 'nullable|numeric|digits:6',
			'person_name' => 'required|max:'.$name_max_character.'|regex:/^[\pL\s]+$/u',
			'contact_phone' => 'required|numeric|digits:10|starts_with:6,7,8,9',
			'due_date' => 'required|array|min:1',
			'due_date.*' => 'required|date_format:d/m/Y',
			'due_amount' => 'required|array|min:1',
			'due_amount.*' => 'required|numeric|gt:0|lte:100000000',
			'due_note' => 'array|min:1',
			'due_note.*' => 'nullable|string|max:300',
			'proof_of_due' => 'array',
			'proof_of_due.*' => 'mimes:jpeg,jpg,bmp,xls,xlsx,png,pdf,docx,txt'
		];

		if ($due_date_old_in_year) {
			$rule['due_date.*'] = $rule['due_date.*'] . '|after_or_equal:' . $due_date_old_in_year;
		}

		if ($due_date_max_future_in_year) {
			$rule['due_date.*'] = $rule['due_date.*'] . '|before_or_equal:' . $due_date_max_future_in_year;
		}

		$ruleMessage =
			[
				'person_name.regex' => 'The :attribute may only contain letters and space.',
				'father_name.regex' => 'The :attribute may only contain letters and space.',
				'mother_name.regex' => 'The :attribute may only contain letters and space.',
				'due_amount.lte' => 'The :attribute must be less than or equal 1,00,00,000'
			];

		if ($due_date_old_in_year) {
			$ruleMessage['due_date.after_or_equal'] = 'The Due date must be a date after or equal to ' . $due_date_old_in_year;
		}

		if ($due_date_max_future_in_year) {
			$ruleMessage['due_date.before_or_equal'] = 'The Due date must be a date before or equal to ' . $due_date_max_future_in_year;
		}

		$validator = Validator::make($request->all(), $rule, $ruleMessage);

		if ($validator->fails()) {

			//return redirect()->back()->withErrors($validator)->withInput($request->all());
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$studentsTemp = Students::where('person_name', 'LIKE', General::encrypt(strtolower($person_name)))
						->where('contact_phone', '=', General::encrypt($contact_phone))
						// ->where('added_by', Auth::id())
						->whereNull('deleted_at')
						->first();

		$is_new_student_customer = true;
		if(!empty($studentsTemp) && CustomerHelper::isAlreadyExistingCustomer(Auth::id(), $studentsTemp->id, 1)){
			$is_new_student_customer = false;
		}

		if ($remainingCustomer <= 0 && $is_new_student_customer) {

			$remainingRecords = $requestData;
			$totalSkippedRecordCount = 1;

			$SkippedDuesRecord = new SkippedDuesRecord;
			$SkippedDuesRecord->user_id = Auth::user()->id;
			$SkippedDuesRecord->request_data = json_encode($requestData);
			$SkippedDuesRecord->total_skipped_record_count = $totalSkippedRecordCount;
			$SkippedDuesRecord->save();

			return view('admin.add-record.skipped-popup', compact('SkippedDuesRecord', 'requestData', 'totalSkippedRecordCount'));
		} else {
			$recordsToAllowCount = count($request->due_date);
			$totalSkippedRecordCount = 0;
		}

		$authId = Auth::id();
		//$proofOfDue = '';
		$proofOfDue = [];

		/*if(!empty($request->file('proof_of_due'))){
			$proofOfDue = Storage::disk('public')->put('proof_of_due', $request->file('proof_of_due'));
		}*/

		/*$files = $request->file('proof_of_due');

		if ($request->hasFile('proof_of_due')) {
			foreach ($files as $key => $file) { //dd(file_get_contents($file->getRealPath()));
				$file_get_contents = file_get_contents($file->getRealPath());

				$proofOfDue[$key] = Storage::disk('public')->put('proof_of_due', $file);
			}
		}*/

		$students = Students::where('person_name', 'LIKE', General::encrypt(strtolower($person_name)))
					->where('contact_phone', '=', General::encrypt($contact_phone))
					->whereNull('deleted_at')
					->first();

		if (empty($students)) {
			$students = Students::create([
				'person_name' => $person_name,
				'dob' => $dob,
				'father_name' => $father_name,
				'mother_name' => $mother_name,
				'aadhar_number' => $aadhar_number,
				'contact_phone' => $contact_phone,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now(),
				'added_by' => $authId,
			]);

			$studentId = DB::getPdo()->lastInsertId();
			if ($studentId) {

				if (!is_array($due_amount)) {
					$studentDue = StudentDueFees::where('student_id', '=', $studentId)->where('due_date', '=', $due_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
				} else {
					foreach ($due_date as $key => $val) {
						if ($recordsToAllowCount > 0) {
							$thisdue_date = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
							$studentDueArr[] = StudentDueFees::where('student_id', '=', $studentId)
												->where('due_date', '=', $thisdue_date)
												->where('added_by', $authId)
												->whereNull('deleted_at')
												->first();

							$recordsToAllowCount--;
							//$customStudentId = isset($external_student_id[$key]) ? $external_student_id[$key] : NULL;
							unset($requestData['invoice_no'][$key]);
							unset($requestData['due_amount'][$key]);
							unset($requestData['due_date'][$key]);
							unset($requestData['grace_period'][$key]);
							unset($requestData['grace_period_hidden'][$key]);
							unset($requestData['collection_date'][$key]);
							unset($requestData['due_note'][$key]);
							//unset($customStudentId);
							unset($requestData['invoice_date'][$key]);
						}

					}
				}

				foreach ($studentDueArr as $key => $arrval) {
					$proofDueValue_proof="";
					$files = $request->file('proof_of_due_'.$key);

					if($files == null){
						$proofDueValue="";
					} else {
						$num_of_items = count($files);
							$num_count = 0;
						if ($request->hasFile('proof_of_due_'.$key)) {
							foreach ($files as $Proofkey => $file) { //dd(file_get_contents($file->getRealPath()));
								$file_get_contents = file_get_contents($file->getRealPath());

								$proofOfDue[$Proofkey] = Storage::disk('public')->put('proof_of_due', $file);
								$proofDueValue=$proofOfDue[$Proofkey];

								$num_count = $num_count + 1;
								if ($num_count < $num_of_items) {
									$str=",";
								}else{
									$str="";
								}

								$proofDueValue_proof.=str_replace("proof_of_due/","",$proofDueValue).$str;
							}
						}

						$proofDueValue="proof_of_due/".$proofDueValue_proof;
					}

					try {
						if (empty($arrval)) {
							//foreach($due_date as $key=>$val) {
							$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
							if( $invoice_date[$key] == null){
								$invoice_date_formated = '';
							} else {
								$invoice_date_formated = Carbon::createFromFormat('d/m/Y', $invoice_date[$key])->toDateTimeString();
							}

							$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
							//$customStudentId = isset($external_student_id[$key]) ? $external_student_id[$key] : NULL;

							$studentDue = StudentDueFees::create([
								'student_id' => $studentId,
								'due_date' => $due_date_formated,
								'due_amount' => str_replace(',', '', $due_amount[$key]),
								'due_note' => $due_note[$key],
								'invoice_no' => $invoice_no[$key],
								'created_at' => Carbon::now(),
								'added_by' => $authId,
								'proof_of_due' => $proofDueValue,
								'collection_date' => $collection_date_formated,
								'grace_period' => $grace_period[$key],
								//'external_student_id' => $customStudentId,
								'credit_period'=>$credit_period[$key],
								'invoice_date'=>$invoice_date_formated,
								'balance_due'=>str_replace(',', '', $due_amount[$key]),
							]);
							//}
						} else {
							//if(!empty($proofOfDue[$key])){
							if (!empty($proofDueValue)) {
								$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
								if( $invoice_date[$key] == null){
									$invoice_date_formated = '';
								} else {
									$invoice_date_formated = Carbon::createFromFormat('d/m/Y', $invoice_date[$key])->toDateTimeString();
								}

								$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
								//$customStudentId = isset($external_student_id[$key]) ? $external_student_id[$key] : NULL;
								$studentDue->update([
									'student_id' => $studentId,
									'due_date' => $due_date_formated,
									'due_amount' => str_replace(',', '', $due_amount[$key]),
									'due_note' => $due_note[$key],
									'invoice_no' => $invoice_no[$key],
									'updated_at' => Carbon::now(),
									'proof_of_due' => $proofDueValue,
									'collection_date' => $collection_date_formated,
									'grace_period' => $grace_period[$key],
									//'external_student_id' => $customStudentId,
									'credit_period'=>$credit_period[$key],
									'invoice_date'=>$invoice_date_formated,
									'balance_due'=>str_replace(',', '', $due_amount[$key]),
								]);
							} else {
								//foreach($due_date as $key=>$val) {
								$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
								if( $invoice_date[$key] == null){
									$invoice_date_formated = '';
								} else {
									$invoice_date_formated = Carbon::createFromFormat('d/m/Y', $invoice_date[$key])->toDateTimeString();
								}

								$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
								//$customStudentId = isset($external_student_id[$key]) ? $external_student_id[$key] : NULL;
								$studentDue->update([
									'student_id' => $studentId,
									'due_date' => $due_date_formated,
									'due_amount' => str_replace(',', '', $due_amount[$key]),
									'due_note' => $due_note[$key],
									'invoice_no' => $invoice_no[$key],
									'updated_at' => Carbon::now(),
									'collection_date' => $collection_date_formated,
									'grace_period' => $grace_period[$key],
									//'external_student_id' => $customStudentId,
									'credit_period'=>$credit_period[$key],
									'invoice_date'=>$invoice_date_formated,
									'balance_due'=>str_replace(',', '', $due_amount[$key]),
								]);
								//}
							}
						}
					} catch (Exception $e) {
						echo 'Message: ' . $e->getMessage();
					}
				}
			}

			/*----------------------------Magic link Gen Start-----------------------------*/
			// $studentId = DB::getPdo()->lastInsertId();
			$individual_response=General::generate_magic_url_function($request,"individual",$studentId,'' );
			if($individual_response['email']){
				if(empty($individual_response['uniqe_url_individual'])){
					//$response=General::sendMail($individual_response,'Individual');
				}
			}
			/*----------------------------Magic link Gen End-----------------------------*/
		} else {

			if ($students->id) {
				$studentId = $students->id;

				$valuesForStudent = [
					'person_name' => $person_name,
					'dob' => $dob,
					'father_name' => $father_name,
					'mother_name' => $mother_name,
					'aadhar_number' => $aadhar_number,
					'contact_phone' => $contact_phone,
					'updated_at' => Carbon::now(),
				];

				/*if(empty($row['customer_number']) &&  empty($row['customer_number'])){
					if(empty($students->customer_no) && empty($students->customer_no)){
						$valuesForStudent['customer_no'] = $row['customer_number'];
						$valuesForStudent['invoice_no'] = $row['invoice_number'];
					}
				}else{
					$valuesForStudent['customer_no'] = $row['customer_number'];
					$valuesForStudent['invoice_no'] = $row['invoice_number'];
				}*/
				$students->update($valuesForStudent);

				if (!is_array($due_amount)) {
					$studentDue = StudentDueFees::where('student_id', '=', $studentId)->where('due_date', '=', $due_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
				} else {
					foreach ($due_date as $key => $val) {
						if ($recordsToAllowCount > 0) {
							$thisdue_date = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
							$studentDueArr[] = StudentDueFees::where('student_id', '=', $studentId)->where('due_date', '=', $thisdue_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
							$recordsToAllowCount--;
							unset($requestData['invoice_no'][$key]);
							unset($requestData['due_amount'][$key]);
							unset($requestData['due_date'][$key]);
							unset($requestData['grace_period'][$key]);
							unset($requestData['grace_period_hidden'][$key]);
							unset($requestData['collection_date'][$key]);
							unset($requestData['due_note'][$key]);
						}
					}
				}
				//dd($studentDueArr);
				//$studentDue = StudentDueFees::where('student_id','=',$studentId)->where('due_date','=',$due_date)->where('added_by',$authId)->whereNull('deleted_at')->first();
				foreach ($studentDueArr as $key => $arrval) {
					//$proofDueValue = array_key_exists($key, $proofOfDue) ? $proofOfDue[$key] : "";

					$proofDueValue_proof="";
                    $files = $request->file('proof_of_due_'.$key);

                    if($files == null)
                    {
                        $proofDueValue="";
                    }else{
                            $num_of_items = count($files);
                                $num_count = 0;
                            if ($request->hasFile('proof_of_due_'.$key)) {
                                foreach ($files as $Proofkey => $file) { //dd(file_get_contents($file->getRealPath()));
                                    $file_get_contents = file_get_contents($file->getRealPath());

                                    $proofOfDue[$Proofkey] = Storage::disk('public')->put('proof_of_due', $file);
                                    $proofDueValue=$proofOfDue[$Proofkey];

                                    $num_count = $num_count + 1;
                                    if ($num_count < $num_of_items) {
                                        $str=",";
                                    }else{
                                        $str="";
                                    }

                                    $proofDueValue_proof.=str_replace("proof_of_due/","",$proofDueValue).$str;
                                }
                            }
                    $proofDueValue="proof_of_due/".$proofDueValue_proof;
                    }

					try {
						if (empty($arrval)) {
							//if(empty($studentDue)){
							//foreach($due_date as $key=>$val) {
							$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
							if( $invoice_date[$key] == null)
							{
								$invoice_date_formated = '';
							}
							else
							{
								$invoice_date_formated = Carbon::createFromFormat('d/m/Y', $invoice_date[$key])->toDateTimeString();
							}

							$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
							//$customStudentId = isset($external_student_id[$key]) ? $external_student_id[$key] : NULL;
							$studentDue = StudentDueFees::create([
								'student_id' => $studentId,
								'due_date' => $due_date_formated,
								'due_amount' => str_replace(',', '', $due_amount[$key]),
								'due_note' => $due_note[$key],
								'invoice_no' => $invoice_no[$key],
								'created_at' => Carbon::now(),
								'proof_of_due' => $proofDueValue,
								'added_by' => $authId,
								'collection_date' => $collection_date_formated,
								'grace_period' => $grace_period[$key],
								//'external_student_id' => $customStudentId,
								'credit_period'=>$credit_period[$key],
								'invoice_date'=>$invoice_date_formated,
								'balance_due'=>str_replace(',', '', $due_amount[$key]),
							]);

							//}
						} else {
							//if(!empty($proofOfDue[$key])){
							if (!empty($proofDueValue)) {
								$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
								if( $invoice_date[$key] == null)
								{
									$invoice_date_formated = '';
								}
								else
								{
									$invoice_date_formated = Carbon::createFromFormat('d/m/Y', $invoice_date[$key])->toDateTimeString();
								}
								$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
								//$customStudentId = isset($external_student_id[$key]) ? $external_student_id[$key] : NULL;
								$studentDue->update([
									'student_id' => $studentId,
									'due_date' => $due_date_formated,
									'due_amount' => str_replace(',', '', $due_amount[$key]),
									'due_note' => $due_note[$key],
									'invoice_no' => $invoice_no[$key],
									'updated_at' => Carbon::now(),
									'proof_of_due' => $proofDueValue,
									'collection_date' => $collection_date_formated,
									'grace_period' => $grace_period[$key],
									//'external_student_id' => $customStudentId,
									'credit_period'=>$credit_period[$key],
								    'invoice_date'=>$invoice_date_formated,
								]);
							} else {
								foreach ($due_date as $key => $val) {
									$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
									$invoice_date_formated = Carbon::createFromFormat('d/m/Y', $invoice_date[$key])->toDateTimeString();
									$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
									//$customStudentId = isset($external_student_id[$key]) ? $external_student_id[$key] : NULL;
									$studentDue->update([
										'student_id' => $studentId,
										'due_date' => $due_date_formated,
										'due_amount' => str_replace(',', '', $due_amount[$key]),
										'due_note' => $due_note[$key],
										'invoice_no' => $invoice_no[$key],
										'updated_at' => Carbon::now(),
										'collection_date' => $collection_date_formated,
										'grace_period' => $grace_period[$key],
										//'external_student_id' => $customStudentId,
										'credit_period'=>$credit_period[$key],
										'invoice_date'=>$invoice_date_formated,
									]);
								}
							}
						}
					} catch (Exception $e) {
						echo 'Message: ' . $e->getMessage();
					}
				}
			}
		}

		CustomerHelper::insertIntoMemberCustomerIdMappingTable($authId, $studentId, 1);
		$students->email = $request->email;
		$students->save();

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

				General::add_to_debug_log(Auth::id(), "Initiated One code transaction Api Call.");
				$transactionPostData = array(
					"code" => $offerDataCheck->offer_code,
					"amount" => 0,
					"category" => "Basic",
					"transactionId" => Date('YmdHis')
				);

				$response = General::offer_codes_curl($transactionPostData, 'transaction');
				General::add_to_debug_log(Auth::id(), "One code transaction Api Call Success.");

				UsersOfferCodes::where('user_id', Auth::id())->update(array('offer_code_used' => 1, "response" => $response));
			}
		}
		/*One code hit transaction Api Call ends here*/

		if ($totalSkippedRecordCount > 0) {
			$SkippedDuesRecord = new SkippedDuesRecord;
			$SkippedDuesRecord->user_id = Auth::user()->id;
			$SkippedDuesRecord->request_data = json_encode($requestData);
			$SkippedDuesRecord->total_skipped_record_count = $totalSkippedRecordCount;
			$SkippedDuesRecord->save();

			view('admin.add-record.skipped-popup', compact('SkippedDuesRecord', 'requestData', 'totalSkippedRecordCount'));
		}

		/*$skipEmailNotification=false;
		if (General::Checkmemberid_skip_email_notifications_for_dues()) {
			$skipEmailNotification = true;
		}
		if($skipEmailNotification == false){
			if(!empty($students)){
					$name = $students->person_name;
					$email = $students->email;
					$message = view('front.emails.submitdue-email');
					if(isset($email)){
					try{
						SendMail::send('front.emails.submitdue-email', [
							'personname' => $name
						], function($message) use ($email) {
							$message->to($email)
							->subject("Your payment details updated on Recordent. Get to know what it is.");
						});

					}catch(JWTException $exception){
						$this->serverstatuscode = "0";
						$this->serverstatusdes = $exception->getMessage();

					}
				}
			}
		}*/

		return redirect()->back()->withMessage('Success: Record added');

	}

	public function makePaymentForDues($id, $type = '')
	{
		if($authId = session::get('member_id')){
            $user = User::find($authId);
        } else {
            $user = Auth::user();
            $authId = Auth::user()->id;
        }

		$SkippedDuesRecord = SkippedDuesRecord::where('id', $id)->first();

		if (empty($SkippedDuesRecord)) {

			if ($type == "import") {
				$redirect_url = 'admin/import-excel';
				if (Auth::user()->hasRole('admin')) {
					$redirect_url = 'admin/import-excel-super/'.$authId;
				}
			} else {
				$redirect_url = 'admin/add-record';
			}

			Log::debug('Empty SkippedDuesRecord in AddRecordController@postPaidForDues id ='.$id);
			return redirect($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
		}

		$additional_customers_price = $SkippedDuesRecord->total_skipped_record_count * HomeHelper::getAdditionalCustomerPrice();
		$gst_price = (($additional_customers_price) * 18) / 100;
		$amount = $additional_customers_price + $gst_price;

		$order_id = Str::random(40);

		$tempDuePayment = General::insertIntoTempMembershipPayments($order_id, 'INDIVIDUAL', $additional_customers_price, HomeHelper::getUserPricingPlanId(), 'Additional Customer Dues' );

		$duePayment = General::insertIntoMembershipPayments($tempDuePayment, $gst_price, $amount);

        $userDataToPaytm = User::findOrFail($authId);
		$userDataToPaytm_name = preg_replace('/\s+/', '_', $userDataToPaytm->name);

		if (setting('admin.payment_gateway_type') == 'paytm') {
			$payment = PaytmWallet::with('receive');
			$payment->prepare([
				'order' => $duePayment->order_id,
				'user' => $userDataToPaytm_name,
				'mobile_number' => $userDataToPaytm->mobile_number,
				'email' => $userDataToPaytm->email,
				'amount' => $amount,
				'callback_url' => $type == 'import' ? route('admin.due.payment-callback.import', ['id' => $id]) : route('admin.due.payment-callback', ['id' => $id])
			]);

			General::add_to_payment_debug_log($authId, 1);

			return $payment->view('admin.payment-submit')->receive();
		} else {
			$postData = [
				'amount' => $amount,
				'txnid' => $duePayment->order_id,
				'phone' => $userDataToPaytm->mobile_number,
				'email' => $userDataToPaytm->email,
				'firstname' => preg_replace('/\s+/', '', $userDataToPaytm->name),
				'surl' => $type == 'import' ? route('admin.due.payment-callback.import', ['id' => $id]) : route('admin.due.payment-callback', ['id' => $id])
			];

			$payuForm = General::generatePayuForm($postData);
			General::add_to_payment_debug_log($authId, 1);

			return view('admin.payment-submit', compact('payuForm'));
		}
	}

	public function makePaymentForDuesCallback($id, Request $request)
	{
		$transaction = null;

		if($authId = session::get('member_id')){
            $user = User::find($authId);
        } else {
            $user = Auth::user();
            $authId = Auth::user()->id;
        }

		$redirect_url = 'admin/add-record';

		if (setting('admin.payment_gateway_type') == 'paytm') {
			$transaction = PaytmWallet::with('receive');
			try {
				$response = $transaction->response();
			} catch (\Exception $e) {
				General::add_to_debug_log($authId, $e->getMessage());
				return redirect()->route($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}

			if ($request->STATUS != 'TXN_SUCCESS') {
				General::add_to_debug_log($authId, "Something went wrong. status != TXN_SUCCESS");
				return redirect()->route($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		} else {
			try {
				$response = General::verifyPayuPayment($request->all());
				if (!$response) {
					General::add_to_debug_log($authId, "verifyPayuPayment response is empty.");
					return redirect()->route($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
				}
			} catch (\Exception $e) {
				General::add_to_debug_log($authId, $e->getMessage());
				return redirect()->route($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		}

		$duePayment = MembershipPayment::where('order_id', '=', $response['ORDERID'])
            ->where('added_by', $authId)
            ->first();

        if (empty($duePayment)) {

            General::add_to_debug_log($authId, "Invalid Additional Customer Dues payment");
            return redirect($redirect_url)->with(['message' => "Invalid Additional Customer Dues payment", 'alert-type' => 'error']);
        }

        $tempDuePayment = TempMembershipPayment::where('order_id', '=', $response['ORDERID'])
            ->where('added_by', $authId)
            ->first();

        if (empty($tempDuePayment)) {
            General::add_to_debug_log($authId, "Invalid Additional Customer Dues payment");
            return redirect($redirect_url)->with(['message' => "Invalid Additional Customer Dues payment", 'alert-type' => 'error']);
        }

        $paymentStatus = General::getPaymentStatus($response, $transaction);
        $update_duePayment = General::updateAdditionalCustomersLimitPaymentDetails($duePayment, $response, $paymentStatus);

        if ($duePayment->status == 4 || $duePayment->status == 5) {
            $tempDuePayment->delete();
        }

        $SkippedDuesRecord = SkippedDuesRecord::where('id', $id)->first();

        if ($duePayment->status == 4) {

			$temp = $SkippedDuesRecord->toArray();
			$requestData = json_decode($temp['request_data']);

			$aadharCheck = isset($requestData->aadhar_number) ? $requestData->aadhar_number : NULL;
			$dobCheck = isset($requestData->dob) ? $requestData->dob : NULL;
			$aadhar_number = $aadharCheck;
			$contact_phone = $requestData->contact_phone;
			$invoice_no = $requestData->invoice_no;
			$person_name = $requestData->person_name;
			$dob = $dobCheck != '' ? Carbon::createFromFormat('d/m/Y', $dobCheck)->toDateTimeString() : '';
			$father_name = $requestData->father_name;
			$mother_name = $requestData->mother_name;

			if (!is_array($requestData->due_date)) {
				$due_date = Carbon::createFromFormat('d/m/Y', $requestData->due_date)->toDateTimeString();
			} else {
				$due_date = $requestData->due_date;
			}

			$due_amount = $requestData->due_amount;
			$due_note = $requestData->due_note;
			// $proof_of_due = $requestData->file('proof_of_due');
			$collection_date = $requestData->collection_date;
			$grace_period = $requestData->grace_period_hidden;
			//$custom_id = $requestData->external_student_id;
			$invoice_date = $requestData->invoice_date;


			$credit_period = $requestData->credit_period;
			$credit_period_new = array();

			foreach($credit_period as $key => $val) {
				if($val == null) {
					$credit_period_new[] = '';
				} else {
					$credit_period_new[] = $val;
				}
			}

			$credit_period = $credit_period_new;

			$proofOfDue = [];

			$students = Students::where('person_name', 'LIKE', General::encrypt(strtolower($person_name)))
						->where('contact_phone', '=', General::encrypt($contact_phone))
						->whereNull('deleted_at')
						->first();

			if (empty($students)) {
				$students = Students::create([
					'person_name' => $person_name,
					'dob' => $dob,
					'father_name' => $father_name,
					'mother_name' => $mother_name,
					'aadhar_number' => $aadhar_number,
					'contact_phone' => $contact_phone,
					'created_at' => Carbon::now(),
					'updated_at' => Carbon::now(),
					'added_by' => $authId
				]);

				$studentId = DB::getPdo()->lastInsertId();

				if ($studentId) {

					if (!is_array($due_amount)) {
						$studentDue = StudentDueFees::where('student_id', '=', $studentId)->where('due_date', '=', $due_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
					} else {
						foreach ($due_date as $key => $val) {
							$thisdue_date = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
							$studentDueArr[] = StudentDueFees::where('student_id', '=', $studentId)->where('due_date', '=', $thisdue_date)->where('added_by', $authId)->whereNull('deleted_at')->first();

						}
					}

					foreach ($studentDueArr as $key => $arrval) {
						$proofDueValue = array_key_exists($key, $proofOfDue) ? $proofOfDue[$key] : "";

						try {
							if (empty($arrval)) {

								$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
								if( $invoice_date[$key] == null) {
									$invoice_date_formated = '';
								} else {
									$invoice_date_formated = Carbon::createFromFormat('d/m/Y', $invoice_date[$key])->toDateTimeString();
								}

								$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
								//$customStudentId = isset($custom_id[$key]) ? $custom_id[$key] : NULL;

								$studentDue = StudentDueFees::create([
									'student_id' => $studentId,
									'due_date' => $due_date_formated,
									'due_amount' => str_replace(',', '', $due_amount[$key]),
									'due_note' => $due_note[$key],
									'invoice_no' => $invoice_no[$key],
									'created_at' => Carbon::now(),
									'added_by' => $authId,
									'proof_of_due' => $proofDueValue,
									'collection_date' => $collection_date_formated,
									'grace_period' => $grace_period[$key],
									//'external_student_id' => $customStudentId,
									'credit_period'=>$credit_period[$key],
									'invoice_date'=>$invoice_date_formated,
								]);

							} else {
								if (!empty($proofDueValue)) {
									$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();

									if($invoice_date[$key] == null) {
										$invoice_date_formated = '';
									} else {
										$invoice_date_formated = Carbon::createFromFormat('d/m/Y', $invoice_date[$key])->toDateTimeString();
									}

									$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
									//$customStudentId = isset($custom_id[$key]) ? $custom_id[$key] : NULL;

									$studentDue->update([
										'student_id' => $studentId,
										'due_date' => $due_date_formated,
										'due_amount' => str_replace(',', '', $due_amount[$key]),
										'due_note' => $due_note[$key],
										'invoice_no' => $invoice_no[$key],
										'updated_at' => Carbon::now(),
										'proof_of_due' => $proofDueValue,
										'collection_date' => $collection_date_formated,
										'grace_period' => $grace_period[$key],
										//'external_student_id' => $customStudentId,
										'credit_period'=>$credit_period[$key],
									    'invoice_date'=>$invoice_date_formated,
									]);
								} else {
									$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
									$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();

									if($invoice_date[$key] == null) {
										$invoice_date_formated = '';
									} else {
										$invoice_date_formated = Carbon::createFromFormat('d/m/Y', $invoice_date[$key])->toDateTimeString();
									}

									//$customStudentId = isset($custom_id[$key]) ? $custom_id[$key] : NULL;
									$studentDue->update([
										'student_id' => $studentId,
										'due_date' => $due_date_formated,
										'due_amount' => str_replace(',', '', $due_amount[$key]),
										'due_note' => $due_note[$key],
										'invoice_no' => $invoice_no[$key],
										'updated_at' => Carbon::now(),
										'collection_date' => $collection_date_formated,
										'grace_period' => $grace_period[$key],
										//'external_student_id' => $customStudentId,
										'credit_period'=>$credit_period[$key],
									    'invoice_date'=>$invoice_date_formated,
									]);
								}
							}
						} catch (Exception $e) {
							echo 'Message: ' . $e->getMessage();
						}
					}
				}

				// $studentId = DB::getPdo()->lastInsertId();
				$individual_response = General::generate_magic_url_function($requestData,"individual",$studentId ,'indivSinglerecSkip');
			} else {
				if ($students->id) {
					$studentId = $students->id;
					$valuesForStudent = [
						'person_name' => $person_name,
						'dob' => $dob,
						'father_name' => $father_name,
						'mother_name' => $mother_name,
						'aadhar_number' => $aadhar_number,
						'contact_phone' => $contact_phone,
						'updated_at' => Carbon::now(),
					];

					$students->update($valuesForStudent);

					if (!is_array($due_amount)) {
						$studentDue = StudentDueFees::where('student_id', '=', $studentId)->where('due_date', '=', $due_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
					} else {
						foreach ($due_date as $key => $val) {
							$thisdue_date = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
							$studentDueArr[] = StudentDueFees::where('student_id', '=', $studentId)
								->where('due_date', '=', $thisdue_date)
								->where('added_by', $authId)
								->whereNull('deleted_at')
								->first();
						}
					}

					foreach ($studentDueArr as $key => $arrval) {
						$proofDueValue = array_key_exists($key, $proofOfDue) ? $proofOfDue[$key] : "";

						try {
							if (empty($arrval)) {

								$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
								if( $invoice_date[$key] == null){
									$invoice_date_formated = '';
								} else {
									$invoice_date_formated = Carbon::createFromFormat('d/m/Y', $invoice_date[$key])->toDateTimeString();
								}

								$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
								//$customStudentId = isset($custom_id[$key]) ? $custom_id[$key] : NULL;
								$studentDue = StudentDueFees::create([
									'student_id' => $studentId,
									'due_date' => $due_date_formated,
									'due_amount' => str_replace(',', '', $due_amount[$key]),
									'due_note' => $due_note[$key],
									'invoice_no' => $invoice_no[$key],
									'created_at' => Carbon::now(),
									'proof_of_due' => $proofDueValue,
									'added_by' => $authId,
									'collection_date' => $collection_date_formated,
									'grace_period' => $grace_period[$key],
									//'external_student_id' => $customStudentId,
									'credit_period'=>$credit_period[$key],
									'invoice_date'=>$invoice_date_formated,
								]);
							} else {
								if (!empty($proofDueValue)) {
									$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
									$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();

									if( $invoice_date[$key] == null) {
										$invoice_date_formated = '';
									} else {
										$invoice_date_formated = Carbon::createFromFormat('d/m/Y', $invoice_date[$key])->toDateTimeString();
									}

									//$customStudentId = isset($custom_id[$key]) ? $custom_id[$key] : NULL;
									$studentDue->update([
										'student_id' => $studentId,
										'due_date' => $due_date_formated,
										'due_amount' => str_replace(',', '', $due_amount[$key]),
										'due_note' => $due_note[$key],
										'invoice_no' => $invoice_no[$key],
										'updated_at' => Carbon::now(),
										'proof_of_due' => $proofDueValue,
										'collection_date' => $collection_date_formated,
										'grace_period' => $grace_period[$key],
										//'external_student_id' => $custom_id[$key],
										'credit_period'=>$credit_period[$key],
									    'invoice_date'=>$invoice_date_formated,
									]);
								} else {
									foreach ($due_date as $key => $val) {
										$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
										$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();

										if( $invoice_date[$key] == null) {
											$invoice_date_formated = '';
										} else {
											$invoice_date_formated = Carbon::createFromFormat('d/m/Y', $invoice_date[$key])->toDateTimeString();
										}

										//$customStudentId = isset($custom_id[$key]) ? $custom_id[$key] : NULL;
										$studentDue->update([
											'student_id' => $studentId,
											'due_date' => $due_date_formated,
											'due_amount' => str_replace(',', '', $due_amount[$key]),
											'due_note' => $due_note[$key],
											'invoice_no' => $invoice_no[$key],
											'updated_at' => Carbon::now(),
											'collection_date' => $collection_date_formated,
											'grace_period' => $grace_period[$key],
											//'external_student_id' => $customStudentId,
											'credit_period'=>$credit_period[$key],
											'invoice_date'=>$invoice_date_formated,
										]);
									}
								}
							}
						} catch (Exception $e) {
							echo 'Message: ' . $e->getMessage();
						}
					}
				}
			}

			CustomerHelper::insertIntoMemberCustomerIdMappingTable($authId, $studentId, 1);

			$students->email = $requestData->email;
			$students->save();

			General::sendprepaidinvoices($duePayment->id);

			/*One code hit transaction Api Call*/

			$duesCheck = StudentDueFees::where('added_by', Auth::id())->where('due_amount', '>=', 500)->get();
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

					General::add_to_debug_log(Auth::id(), "Initiated One code transaction Api Call.");
					$transactionPostData = array(
						"code" => $offerDataCheck->offer_code,
						"amount" => 0,
						"category" => "Basic",
						"transactionId" => Auth::id()
					);
					$response = General::offer_codes_curl($transactionPostData, 'transaction');

					General::add_to_debug_log(Auth::id(), "One code transaction Api Call Success.");

					UsersOfferCodes::where('user_id', Auth::id())->update(array('offer_code_used' => 1, "response" => $response));
				}
			}
			/*One code hit transaction Api Call ends here*/
		}

		$SkippedDuesRecord->delete();

		$result_message = General::getPaymentGatewayFormattedResponseMessage($duePayment->status, 'Success: Record added');

		return redirect($redirect_url)->with($result_message);
	}

	public function makePaymentForDuesCallbackImport($id, Request $request)
	{

		$transaction = null;

		if($authId = session::get('member_id')){
            $user = User::find($authId);
        } else {
            $user = Auth::user();
            $authId = Auth::user()->id;
        }

		$redirect_url = 'admin/import-excel';

		if (Auth::user()->hasRole('admin')) {
			$redirect_url = 'admin/import-excel-super/'.$authId;
		}

		if (setting('admin.payment_gateway_type') == 'paytm') {
			$transaction = PaytmWallet::with('receive');
			try {
				$response = $transaction->response();
			} catch (\Exception $e) {
				General::add_to_debug_log($authId, $e->getMessage());
				return redirect()->route($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}

			if ($request->STATUS != 'TXN_SUCCESS') {
				General::add_to_debug_log($authId, "Something went wrong. status != TXN_SUCCESS");
				return redirect()->route($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		} else {
			try {
				$response = General::verifyPayuPayment($request->all());
				if (!$response) {
					General::add_to_debug_log($authId, "verifyPayuPayment response is empty.");
					return redirect()->route($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
				}
			} catch (\Exception $e) {
				General::add_to_debug_log($authId, $e->getMessage());
				return redirect()->route($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		}


		$duePayment = MembershipPayment::where('order_id', '=', $response['ORDERID'])
            ->where('added_by', $authId)
            ->first();

        if (empty($duePayment)) {

            General::add_to_debug_log($authId, "Invalid Additional Customer Dues payment");
            return redirect($redirect_url)->with(['message' => "Invalid Additional Customer Dues payment", 'alert-type' => 'error']);
        }

        $tempDuePayment = TempMembershipPayment::where('order_id', '=', $response['ORDERID'])
            ->where('added_by', $authId)
            ->first();

        if (empty($tempDuePayment)) {
            General::add_to_debug_log($authId, "Invalid Additional Customer Dues payment");
            return redirect($redirect_url)->with(['message' => "Invalid Additional Customer Dues payment", 'alert-type' => 'error']);
        }

        $paymentStatus = General::getPaymentStatus($response, $transaction);
        $update_duePayment = General::updateAdditionalCustomersLimitPaymentDetails($duePayment, $response, $paymentStatus);

        if ($duePayment->status == 4 || $duePayment->status == 5) {
            $tempDuePayment->delete();
        }

		$SkippedDuesRecord = SkippedDuesRecord::where('id', $id)->first();

		if ($duePayment->status == 4) {
        	$temp = $SkippedDuesRecord->toArray();
			$requestData = json_decode($temp['request_data']);

			foreach ($requestData as $key_rd => $val_rd) {
				$row = array();

				$row['person_name'] = trim($val_rd->person_name);
				$row['contact_phone_number'] = trim($val_rd->contact_phone_number);

				$row['aadhar_number'] = str_replace('-', '', $val_rd->aadhar_number);
				$row['aadhar_number'] = str_replace('_', '', $val_rd->aadhar_number);
				$row['aadhar_number'] = trim($val_rd->aadhar_number);

				$row['dob_ddmmyyyy'] = trim($val_rd->dob_ddmmyyyy);
				$row['father_name'] = trim($val_rd->father_name);
				$row['mother_name'] = trim($val_rd->mother_name);
				$row['duedate_ddmmyyyy'] = trim($val_rd->duedate_ddmmyyyy);
				$row['dueamount'] = str_replace(',', '', $val_rd->dueamount);
				$row['dueamount'] = trim($val_rd->dueamount);
				$row['duenote'] = trim($val_rd->duenote);
				$row['email'] = trim($val_rd->email);
				$row['grace_period'] = trim($val_rd->grace_period);
				$row['invoice_no'] = trim($val_rd->invoice_no);
				$row['custom_id'] = isset($val_rd->custom_id) ? $val_rd->custom_id : NULL;

				if (empty($row['person_name']) && empty($row['father_name']) && empty($row['mother_name']) && empty($row['contact_phone_number']) && empty($row['dob_ddmmyyyy']) && empty($row['duedate_ddmmyyyy']) && empty($row['dueamount']) && empty($row['duenote']) && empty($row['email']) && empty($row['grace_period'])) {
					break;
				}

				//configuation
				$dob_valid_from = Carbon::now()->subYears(100)->format('d/m/Y');

				$due_date_old_in_year = setting('admin.due_date_old_in_year');
				$due_date_max_future_in_year = setting('admin.due_date_max_future_in_year');

				$currentDate = Carbon::now();
				if ($due_date_old_in_year) {
					$due_date_old_in_year = $currentDate->subYears($due_date_old_in_year)->format('d/m/Y');
				}

				$currentDate = Carbon::now();
				if ($due_date_max_future_in_year) {
					$due_date_max_future_in_year = $currentDate->addYears($due_date_max_future_in_year)->format('d/m/Y');
				}

				$row['dueamount'] = str_replace(',', '', $row['dueamount']);
				$row['duedate_ddmmyyyy'] = str_replace('-', '/', $row['duedate_ddmmyyyy']);
				$row['dob_ddmmyyyy'] = str_replace('-', '/', $row['dob_ddmmyyyy']);

				$dob = '';
				if (!empty($row['dob_ddmmyyyy'])) {
					$newDob = Carbon::createFromFormat('d/m/Y', trim($row['dob_ddmmyyyy']));
					$dob = $newDob->format('Y-m-d');
				}

				$students = Students::where('person_name', 'LIKE', General::encrypt(strtolower($row['person_name'])))
					->where('contact_phone', '=', General::encrypt($row['contact_phone_number']))->whereNull('deleted_at')->first();

				if (empty($students)) {
					$students = Students::create([
						'person_name' => $row['person_name'],
						'dob' => $dob,
						'father_name' => $row['father_name'],
						'mother_name' => $row['mother_name'],
						'aadhar_number' => $row['aadhar_number'],
						'contact_phone' => $row['contact_phone_number'],
						'created_at' => Carbon::now(),
						'updated_at' => Carbon::now(),
						'added_by' => $authId
					]);

					$studentId = DB::getPdo()->lastInsertId();
					if ($studentId) {

						$dueDate = Carbon::createFromFormat('d/m/Y', trim($row['duedate_ddmmyyyy']));
						$dueDate  = $dueDate->format('Y-m-d');

						if ($row['grace_period'] == 0 || $row['grace_period'] == "" || $row['grace_period']<=1)
							{
								$gracePeriod = 1;
								$collectionDate = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 days'));
							} else {
								$gracePeriod = $row['grace_period'];
								$collectionDate = date('Y-m-d', strtotime($dueDate . '+ ' . $row['grace_period'] . ' days'));
							}

						$customStudentId = isset($row['custom_id']) ? $row['custom_id'] : NULL;
						$studentDue = StudentDueFees::create([
							'student_id' => $studentId,
							'due_date' => $dueDate,
							'due_amount' => $row['dueamount'],
							'due_note' => $row['duenote'],
							'created_at' => Carbon::now(),
							'added_by' => $authId,
							'invoice_no' => $row['invoice_no'],
							'external_student_id' => $customStudentId,
							'grace_period' => $gracePeriod,
							'collection_date' => $collectionDate
						]);
					}
					// $studentId = DB::getPdo()->lastInsertId();
					$individual_response = General::generate_magic_url_function($row, "individual", $studentId, 'indivExcelBulk');
				} else {

					$studentId = $students->id;
					$valuesForStudent = [
						'person_name' => $row['person_name'],
						'dob' => $dob,
						'father_name' => $row['father_name'],
						'mother_name' => $row['mother_name'],
						'aadhar_number' => $row['aadhar_number'],
						'contact_phone' => $row['contact_phone_number'],
						'updated_at' => Carbon::now()
						//'added_by' => $authId,
					];

					$students->update($valuesForStudent);

					$dueDate = Carbon::createFromFormat('d/m/Y', trim($row['duedate_ddmmyyyy']));
					$dueDate  = $dueDate->format('Y-m-d');
					if ($row['grace_period'] == 0 || $row['grace_period'] == "" || $row['grace_period']<=1)
							{
								$gracePeriod = 1;
								$collectionDate = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 days'));
							} else {
								$gracePeriod = $row['grace_period'];
								$collectionDate = date('Y-m-d', strtotime($dueDate . '+ ' . $row['grace_period'] . ' days'));
							}

					$customStudentId = isset($row['custom_id']) ? $row['custom_id'] : NULL;
					$studentDue = StudentDueFees::create([
						'student_id' => $studentId,
						'due_date' => $dueDate,
						'due_amount' => $row['dueamount'],
						'due_note' => $row['duenote'],
						'created_at' => Carbon::now(),
						'added_by' => $authId,
						'invoice_no' => $row['invoice_no'],
						'external_student_id' => $customStudentId,
						'grace_period' => $gracePeriod,
						'collection_date' => $collectionDate
					]);
				}

				CustomerHelper::insertIntoMemberCustomerIdMappingTable($authId, $studentId, 1);

				$students->email = $row['email'];
				$students->save();
			}

			General::sendprepaidinvoices($duePayment->id);
        }

		$SkippedDuesRecord->delete();
		Session::remove('member_id');

        $result_message = General::getPaymentGatewayFormattedResponseMessage($duePayment->status, 'Success: Records imported');
		return redirect($redirect_url)->with($result_message);
	}

	public function makePaymentForBusinessDues($id, $type = '')
	{
		if($member_id = session::get('member_id')){
            $user = User::find($member_id);
        } else {
            $user = Auth::user();
            $member_id = Auth::user()->id;
        }

        $SkippedDuesRecord = SkippedDuesRecord::where('id', $id)->first();

		if (empty($SkippedDuesRecord)) {

			if ($type == "import") {
				$redirect_url = 'admin/import-excel-super';
				if (Auth::user()->hasRole('admin')) {
					$redirect_url = 'admin/import-excel-super/'.$authId;
				}
			} else {
				$redirect_url = 'business.add-record';
			}

			Log::debug('Empty SkippedDuesRecord in AddRecordController@makePaymentForBusinessDues id = '.$id);
			return redirect($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
		}


        $additional_customers_price = $SkippedDuesRecord->total_skipped_record_count * HomeHelper::getAdditionalCustomerPrice();
		$gst_price = (($additional_customers_price) * 18) / 100;
		$amount = $additional_customers_price + $gst_price;

		$order_id = Str::random(40);

		$tempDuePayment = General::insertIntoTempMembershipPayments($order_id, 'BUSINESS', $additional_customers_price, HomeHelper::getUserPricingPlanId(), 'Additional Customer Dues' );

		$duePayment = General::insertIntoMembershipPayments($tempDuePayment, $gst_price, $amount);

		$userDataToPaytm = User::findOrFail($member_id);
		$userDataToPaytm_name = preg_replace('/\s+/', '_', $userDataToPaytm->name);

		if (setting('admin.payment_gateway_type') == 'paytm') {
			$payment = PaytmWallet::with('receive');
			$payment->prepare([
				'order' => $duePayment->order_id,
				'user' => $userDataToPaytm_name,
				'mobile_number' => $userDataToPaytm->mobile_number,
				'email' => $userDataToPaytm->email,
				'amount' => $amount,
				'callback_url' => $type == 'import' ? route('admin.business.due.payment-callback.import', ['id' => $id]) : route('admin.business.due.payment-callback', ['id' => $id])
			]);

			General::add_to_payment_debug_log($member_id, 1);

			return $payment->view('admin.payment-submit')->receive();
		} else {
			$postData = [
				'amount' => $amount,
				'txnid' => $duePayment->order_id,
				'phone' => $userDataToPaytm->mobile_number,
				'email' => $userDataToPaytm->email,
				'firstname' => preg_replace('/\s+/', '', $userDataToPaytm->name),
				'surl' => $type == 'import' ? route('admin.business.due.payment-callback.import', ['id' => $id]) : route('admin.business.due.payment-callback', ['id' => $id]),
			];

			$payuForm = General::generatePayuForm($postData);
			General::add_to_payment_debug_log($member_id, 1);

			return view('admin.payment-submit', compact('payuForm'));
		}
	}

	public function makePaymentForBusinessDuesCallback($id, Request $request)
	{
		$transaction = null;

		if($authId = session::get('member_id')){
            $user = User::find($authId);
        } else {
            $user = Auth::user();
            $authId = Auth::user()->id;
        }

		$redirect_url = route('business.add-record');

		if (setting('admin.payment_gateway_type') == 'paytm') {
			$transaction = PaytmWallet::with('receive');
			try {
				$response = $transaction->response();
			} catch (\Exception $e) {
				General::add_to_debug_log($authId, $e->getMessage());
				return redirect()->route($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}

			if ($request->STATUS != 'TXN_SUCCESS') {
				General::add_to_debug_log($authId, "Something went wrong. status != TXN_SUCCESS");
				return redirect()->route($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		} else {
			try {
				$response = General::verifyPayuPayment($request->all());
				if (!$response) {
					General::add_to_debug_log($authId, "verifyPayuPayment response is empty.");
					return redirect()->route($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
				}
			} catch (\Exception $e) {
				General::add_to_debug_log($authId, $e->getMessage());
				return redirect()->route($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		}

		$duePayment = MembershipPayment::where('order_id', '=', $response['ORDERID'])
            ->where('added_by', $authId)
            ->first();

        if (empty($duePayment)) {

            General::add_to_debug_log($authId, "Invalid Additional Customer Dues payment");
            return redirect($redirect_url)->with(['message' => "Invalid Additional Customer Dues payment", 'alert-type' => 'error']);
        }

        $tempDuePayment = TempMembershipPayment::where('order_id', '=', $response['ORDERID'])
            ->where('added_by', $authId)
            ->first();

        if (empty($tempDuePayment)) {
            General::add_to_debug_log($authId, "Invalid Additional Customer Dues payment");
            return redirect($redirect_url)->with(['message' => "Invalid Additional Customer Dues payment", 'alert-type' => 'error']);
        }

        $paymentStatus = General::getPaymentStatus($response, $transaction);
        $update_duePayment = General::updateAdditionalCustomersLimitPaymentDetails($duePayment, $response, $paymentStatus);

        if ($duePayment->status == 4 || $duePayment->status == 5) {
            $tempDuePayment->delete();
        }

        $SkippedDuesRecord = SkippedDuesRecord::where('id', $id)->first();

        if ($duePayment->status == 4) {

			$temp = $SkippedDuesRecord->toArray();
			$requestData = json_decode($temp['request_data']);

			$company_name = $requestData->company_name;

			$sector_id = $requestData->sector_id;
			$unique_identification_number = $requestData->unique_identification_number;
			$concerned_person_name = $requestData->concerned_person_name;
			$concerned_person_designation = $requestData->concerned_person_designation;
			$concerned_person_phone = $requestData->concerned_person_phone;
			$concerned_person_alternate_phone = $requestData->concerned_person_alternate_phone;
			$state_id = $requestData->state;
			$city_id = $requestData->city;
			$pincode = $requestData->pin_code;
			$address = $requestData->address;

			if (!is_array($requestData->due_date)) {
				$due_date = Carbon::createFromFormat('d/m/Y', $requestData->due_date)->toDateTimeString();
			} else {
				$due_date = $requestData->due_date;
			}
			// $paid_date = $requestData->paid_date;
			// $paid_amount = $requestData->paid_amount;
			$due_amount = $requestData->due_amount;
			$due_note = $requestData->due_note;
			// $paid_note = $requestData->paid_note;
			$invoice_no = $requestData->invoice_no;
			// $proof_of_due = $requestData->file('proof_of_due');
			$collection_date = $requestData->collection_date;
			$grace_period = $requestData->grace_period_hidden;

			//$customer_id = $requestData->external_business_id;
			$proofOfDue = [];

			$invoice_date = $requestData->invoice_date;


			$credit_period = $requestData->credit_period;
			$credit_period_new = array();
			foreach($credit_period as $key=>$val){
				if($val == null) {
					$credit_period_new[] = '';
				} else {
					$credit_period_new[] = $val;
				}
			}

			$credit_period = $credit_period_new;

			$business = Businesses::where('unique_identification_number', '=', General::encrypt(strtoupper($unique_identification_number)))
					->where('concerned_person_phone','=',General::encrypt(strtolower($concerned_person_phone)))
					->whereNull('deleted_at')
					->first();

			if (empty($business)) {

				$business = Businesses::create([
					'company_name' => $company_name,
					'sector_id' => $sector_id,
					'unique_identification_number' => $unique_identification_number,
					'concerned_person_name' => $concerned_person_name,
					'concerned_person_designation' => $concerned_person_designation,
					'concerned_person_phone' => $concerned_person_phone,
					'concerned_person_alternate_phone' => $concerned_person_alternate_phone,
					'state_id' => $state_id,
					'city_id' => $city_id,
					'pincode' => $pincode,
					'address' => $address,
					'created_at' => Carbon::now(),
					'added_by' => $authId
				]);

				$businessId = DB::getPdo()->lastInsertId();
				if ($businessId) {

					foreach ($due_amount as $key => $val) {
						$proofDueValue = array_key_exists($key, $proofOfDue) ? $proofOfDue[$key] : "";
						$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
						if( $invoice_date[$key] == null) {
							$invoice_date_formated = '';
						} else {
							$invoice_date_formated = Carbon::createFromFormat('d/m/Y', $invoice_date[$key])->toDateTimeString();
						}

						$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
						//$customBusinessId = isset($customer_id[$key]) ? $customer_id[$key] : NULL;
						$businessDue = BusinessDueFees::create([
							'business_id' => $businessId,
							'due_date' => $due_date_formated,
							'due_amount' => str_replace(',', '', $due_amount[$key]),
							'due_note' => $due_note[$key],
							'invoice_no' => $invoice_no[$key],
							'created_at' => Carbon::now(),
							'added_by' => $authId,
							'proof_of_due' => $proofDueValue,
							'collection_date' => $collection_date_formated,
							'grace_period' => $grace_period[$key],
							//'external_business_id' => $customBusinessId,
							'credit_period'=>$credit_period[$key],
							'invoice_date'=>$invoice_date_formated
						]);
					}
				}

				// $businessId = DB::getPdo()->lastInsertId();
				$individual_response = General::generate_magic_url_function($requestData, "business", $businessId, 'BusinessRecSkip');
			} else {

				if ($business->id) {
					$businessId = $business->id;
					$valuesForStudent = [
						'company_name' => $company_name,
						'sector_id' => $sector_id,
						'concerned_person_name' => $concerned_person_name,
						'concerned_person_designation' => $concerned_person_designation,
						'concerned_person_phone' => $concerned_person_phone,
						'concerned_person_alternate_phone' => $concerned_person_alternate_phone,
						'state_id' => $state_id,
						'city_id' => $city_id,
						'pincode' => $pincode,
						'address' => $address,
						'updated_at' => Carbon::now()
					];

					$business->update($valuesForStudent);

					if (!is_array($due_amount)) {
						$businessDue = BusinessDueFees::where('business_id', '=', $businessId)
								->where('due_date', '=', $due_date)
								->where('added_by', $authId)
								->whereNull('deleted_at')
								->first();
					} else {
						foreach ($due_date as $key => $val) {
							$thisdue_date = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
							$businessDueArr[] = BusinessDueFees::where('business_id', '=', $businessId)
								->where('due_date', '=', $thisdue_date)
								->where('added_by', $authId)
								->whereNull('deleted_at')
								->first();
						}
					}

					foreach ($businessDueArr as $key => $arrval) {
						$proofDueValue = array_key_exists($key, $proofOfDue) ? $proofOfDue[$key] : "";
						$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();

						if( $invoice_date[$key] == null){
							$invoice_date_formated = '';
						} else {
							$invoice_date_formated = Carbon::createFromFormat('d/m/Y', $invoice_date[$key])->toDateTimeString();
						}

						$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
						//$customBusinessId = isset($customer_id[$key]) ? $customer_id[$key] : NULL;
						if (empty($arrval)) {
							$businessDue = BusinessDueFees::create([
								'business_id' => $businessId,
								'due_date' => $due_date_formated,
								'due_amount' => str_replace(',', '', $due_amount[$key]),
								'due_note' => $due_note[$key],
								'invoice_no' => $invoice_no[$key],
								'created_at' => Carbon::now(),
								'added_by' => $authId,
								'proof_of_due' => $proofDueValue,
								'collection_date' => $collection_date_formated,
								'grace_period' => $grace_period[$key],
								//'external_business_id' => $customBusinessId,
								'credit_period'=>$credit_period[$key],
								'invoice_date'=>$invoice_date_formated
							]);
						} else {
							if (!empty($proofOfDue)) {
								$businessDue->update([
									'business_id' => $businessId,
									'due_date' => $due_date_formated,
									'due_amount' => str_replace(',', '', $due_amount[$key]),
									'due_note' => $due_note[$key],
									'updated_at' => Carbon::now(),
									'proof_of_due' => $proofDueValue,
									'invoice_no' => $invoice_no[$key],
									'collection_date' => $collection_date_formated,
									'grace_period' => $grace_period[$key],
									//'external_business_id' => $customBusinessId,
									'credit_period'=>$credit_period[$key],
								    'invoice_date'=>$invoice_date_formated
								]);
							} else {
								$businessDue->update([
									'business_id' => $businessId,
									'due_date' => $due_date_formated,
									'due_amount' => str_replace(',', '', $due_amount[$key]),
									'due_note' => $due_note[$key],
									'invoice_no' => $invoice_no[$key],
									'updated_at' => Carbon::now(),
									'collection_date' => $collection_date_formated,
									'grace_period' => $grace_period[$key],
									//'external_business_id' => $customBusinessId,
									'credit_period'=>$credit_period[$key],
									'invoice_date'=>$invoice_date_formated
								]);
							}
						}
					}
				}
			}

			CustomerHelper::insertIntoMemberCustomerIdMappingTable($authId, $businessId, 2);

			$business->email = $request->email;
			$business->save();

			General::sendprepaidinvoices($duePayment->id);

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
					$transactionPostData = array(
						"code" => $offerDataCheck->offer_code,
						"amount" => 0,
						"category" => "Basic",
						"transactionId" => Date('YmdHis')
					);

					General::add_to_debug_log(Auth::id(), "Business - Initiated One code transaction Api Call.");

					$response = General::offer_codes_curl($transactionPostData, 'transaction');
					General::add_to_debug_log(Auth::id(), "Business - One code transaction Api Call Success.");

					UsersOfferCodes::where('user_id', Auth::id())->update(array('offer_code_used' => 1, "response" => $response));
				}
			}
			/*One code hit transaction Api Call ends here*/
		}

		$SkippedDuesRecord->delete();
		$result_message = General::getPaymentGatewayFormattedResponseMessage($duePayment->status, 'Success: Record added');

		return redirect($redirect_url)->with($result_message);
	}

	public function makePaymentForBusinessDuesCallbackImport($id, Request $request)
	{
		$transaction = null;

		if($authId = session::get('member_id')){
            $user = User::find($authId);
        } else {
            $user = Auth::user();
            $authId = Auth::user()->id;
        }

		$redirect_url = 'admin/business/import-excel';

		if (Auth::user()->hasRole('admin')) {
			$redirect_url = 'admin/import-excel-super/'.$authId;
		}

		if (setting('admin.payment_gateway_type') == 'paytm') {
			$transaction = PaytmWallet::with('receive');
			try {
				$response = $transaction->response();
			} catch (\Exception $e) {
				General::add_to_debug_log($authId, $e->getMessage());
				return redirect()->route($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}

			if ($request->STATUS != 'TXN_SUCCESS') {
				General::add_to_debug_log($authId, "Something went wrong. status != TXN_SUCCESS");
				return redirect()->route($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		} else {
			try {
				$response = General::verifyPayuPayment($request->all());
				if (!$response) {
					General::add_to_debug_log($authId, "verifyPayuPayment response is empty.");
					return redirect()->route($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
				}
			} catch (\Exception $e) {
				General::add_to_debug_log($authId, $e->getMessage());
				return redirect()->route($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		}

		$duePayment = MembershipPayment::where('order_id', '=', $response['ORDERID'])
            ->where('added_by', $authId)
            ->first();

        if (empty($duePayment)) {

            General::add_to_debug_log($authId, "Invalid Additional Customer Dues payment");
            return redirect($redirect_url)->with(['message' => "Invalid Additional Customer Dues payment", 'alert-type' => 'error']);
        }

        $tempDuePayment = TempMembershipPayment::where('order_id', '=', $response['ORDERID'])
            ->where('added_by', $authId)
            ->first();

        if (empty($tempDuePayment)) {
            General::add_to_debug_log($authId, "Invalid Additional Customer Dues payment");
            return redirect($redirect_url)->with(['message' => "Invalid Additional Customer Dues payment", 'alert-type' => 'error']);
        }

        $paymentStatus = General::getPaymentStatus($response, $transaction);
        $update_duePayment = General::updateAdditionalCustomersLimitPaymentDetails($duePayment, $response, $paymentStatus);

        if ($duePayment->status == 4 || $duePayment->status == 5) {
            $tempDuePayment->delete();
        }

		$SkippedDuesRecord = SkippedDuesRecord::where('id', $id)->first();

		if ($duePayment->status == 4) {

			$temp = $SkippedDuesRecord->toArray();
			$requestData = json_decode($temp['request_data']);

			foreach ($requestData as $key_rd => $val_rd) {
				$row = array();

				$row = (array)$val_rd;
				foreach ($row as $key => &$value) {
					if (!empty($key)) {
						$value = trim($value);
					}
				}

				if (!preg_match("/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/", $row['duedate_ddmmyyyy'])) {
					if ($row['duedate_ddmmyyyy'] != "") {
						$row['duedate_ddmmyyyy'] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['duedate_ddmmyyyy']))->format('d/m/Y');
					}
				}

				if (
					empty($row['business_name']) &&
					empty($row['sector_name']) &&
					empty($row['unique_identification_number_gstin_business_pan']) &&
					empty($row['concerned_person_name']) &&
					empty($row['concerned_person_designation']) &&
					empty($row['concerned_person_phone']) &&
					empty($row['state']) &&
					empty($row['city']) &&
					empty($row['duedate_ddmmyyyy']) &&
					empty($row['dueamount']) &&
					empty($row['email']) &&
					empty($row['grace_period'])
				) {
					break;
				}

				$row['dueamount'] = str_replace(',', '', $row['dueamount']);

				$sectorId = '';
				if (!empty($row['sector_name'])) {
					$sector = Sector::where('name', '=', $row['sector_name'])->first();
					if ($sector) {
						// $sectorId = $sector->id;
					}
				}

				$stateId = '';
				if (!empty($row['state'])) {
					$state = State::where('name', '=', $row['state'])->first();
					if ($state) {
						$stateId = $state->id;
					}
				}

				$cityId = '';
				if (!empty($row['city'])) {
					if (!empty($stateId)) {
						$city = City::where('name', '=', $row['city'])->where('state_id', $stateId)->first();
						if ($city) {
							$cityId = $city->id;
						}
					}
				}

				$row['duedate_ddmmyyyy'] = str_replace('-', '/', $row['duedate_ddmmyyyy']);
				$businesses = Businesses::where('unique_identification_number', '=', General::encrypt(strtoupper($row['unique_identification_number_gstin_business_pan'])))
					->where('concerned_person_phone','=',General::encrypt(strtolower($row['concerned_person_phone'])))
					->whereNull('deleted_at')
					->first();

				if (empty($businesses)) {
					$businesses = Businesses::create([
						'company_name' => $row['business_name'],
						// 'sector_id' => $sectorId,
						'unique_identification_number' => $row['unique_identification_number_gstin_business_pan'],
						'concerned_person_name' => $row['concerned_person_name'],
						'concerned_person_designation' => $row['concerned_person_designation'],
						'concerned_person_phone' => $row['concerned_person_phone'],
						'concerned_person_alternate_phone' => $row['concerned_person_alternate_phone'],
						'state_id' => $stateId,
						'city_id' => $cityId,
						'pincode' => $row['pin_code'],
						'address' => $row['address'],
						'created_at' => Carbon::now(),
						'updated_at' => Carbon::now(),
						'added_by' => $authId
					]);

					$businessId = DB::getPdo()->lastInsertId();
					if ($businessId) {

						$dueDate = Carbon::createFromFormat('d/m/Y', $row['duedate_ddmmyyyy']);
						$dueDate  = $dueDate->format('Y-m-d');
						if ($row['grace_period'] == 0 || $row['grace_period'] == "" || $row['grace_period']<=1)
						{
							$gracePeriod = 1;
							$collectionDate = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 days'));
						} else {
							$gracePeriod = $row['grace_period'];
							$collectionDate = date('Y-m-d', strtotime($dueDate . '+ ' . $row['grace_period'] . ' days'));
						}

						$customBusinessId = isset($row['custom_id']) ? $row['custom_id'] : NULL;
						$businessDue = BusinessDueFees::create([
							'business_id' => $businessId,
							'due_date' => $dueDate,
							'due_amount' => $row['dueamount'],
							//'due_note'=> $row['duenote'],
							'created_at' => Carbon::now(),
							'added_by' => $authId,
							'invoice_no' => $row['invoice_no'],
							'external_business_id' => $customBusinessId,
							'grace_period' => $gracePeriod,
							'collection_date' => $collectionDate
						]);
					}

					// $businessId = DB::getPdo()->lastInsertId();
					$individual_response = General::generate_magic_url_function($row, "business", $businessId, 'BusinessExcelBulk');

				} else {
					$businessId = $businesses->id;
					$valuesForBusiness = [
						'company_name' => $row['business_name'],
						// 'sector_id' => $sectorId,
						'unique_identification_number' => $row['unique_identification_number_gstin_business_pan'],
						'concerned_person_name' => $row['concerned_person_name'],
						'concerned_person_designation' => $row['concerned_person_designation'],
						'concerned_person_phone' => $row['concerned_person_phone'],
						'concerned_person_alternate_phone' => $row['concerned_person_alternate_phone'],
						'state_id' => $stateId,
						'city_id' => $cityId,
						'pincode' => $row['pin_code'],
						'address' => $row['address'],
						'updated_at' => Carbon::now()
					];

					$businesses->update($valuesForBusiness);

					$dueDate = Carbon::createFromFormat('d/m/Y', $row['duedate_ddmmyyyy']);
					$dueDate  = $dueDate->format('Y-m-d');

					if ($row['grace_period'] == 0 || $row['grace_period'] == "" || $row['grace_period'] <= 1){
						$gracePeriod = 1;
						$collectionDate = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 days'));
					} else {
						$gracePeriod = $row['grace_period'];
						$collectionDate = date('Y-m-d', strtotime($dueDate . '+ ' . $row['grace_period'] . ' days'));
					}


					// $customBusinessId = isset($row['custom_id']) ? $customer_id[$key] ? NULL;
					$customBusinessId = NULL;
					if (isset($row['custom_id']) && isset($customer_id[$key])) {
						$customBusinessId = $customer_id[$key];
					}

					$businessDue = BusinessDueFees::create([
						'business_id' => $businessId,
						'due_date' => $dueDate,
						'due_amount' => $row['dueamount'],
						//'due_note'=> $row['duenote'],
						'created_at' => Carbon::now(),
						'added_by' => $authId,
						'invoice_no' => $row['invoice_no'],
						'external_business_id' => $customBusinessId,
						'grace_period' => $gracePeriod,
						'collection_date' => $collectionDate
					]);
				}

				CustomerHelper::insertIntoMemberCustomerIdMappingTable($authId, $businessId, 2);

				$businesses->email = $row['email'];
				$businesses->save();
			}

			General::sendprepaidinvoices($duePayment->id);
		}

		$SkippedDuesRecord->delete();
		Session::remove('member_id');

		$result_message = General::getPaymentGatewayFormattedResponseMessage($duePayment->status, 'Success: Records imported');

		return redirect($redirect_url)->with($result_message);
	}

	public function postPaidForDues($id, $type = '')
	{
		if (Session::get('member_id')) {
			$authId = Session::get('member_id');
			$user = User::find($authId);
		} else {
			$authId = Auth::user()->id;
			$user = Auth::user();
		}

		$additional_customer_price = HomeHelper::getAdditionalCustomerPrice();

		$SkippedDuesRecord = SkippedDuesRecord::where('id', $id)->first();

		if (empty($SkippedDuesRecord)) {

			if ($type == "import") {
				$redirect_url = 'admin/import-excel';
				if (Auth::user()->hasRole('admin')) {
					$redirect_url = 'admin/import-excel-super/'.$authId;
				}
			} else {
				$redirect_url = 'admin/add-record';
			}

			Log::debug('Empty SkippedDuesRecord in AddRecordController@postPaidForDues id ='.$id);
			return redirect($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
		}

		$amount = ($SkippedDuesRecord->total_skipped_record_count * $additional_customer_price) + (($SkippedDuesRecord->total_skipped_record_count * $additional_customer_price * 18) / 100);

		$invoice_no = MembershipPayment::where('created_at', '>=', date('Y-m-d 00:00:00'))->where('status', 4)->count();
		$invoice_no = $invoice_no + 1;

		$valuesForMembershipPayment = [
			'customer_id' => $authId,
			'invoice_id' => date('dmY') . sprintf('%07d', $invoice_no),
			'pricing_plan_id' => 0,
			'customer_type' => "INDIVIDUAL",
			'payment_value' => $SkippedDuesRecord->total_skipped_record_count * $additional_customer_price,
			'gst_perc' => 18,
			'gst_value' => (($SkippedDuesRecord->total_skipped_record_count * $additional_customer_price) * 18) / 100,
			'total_collection_value' => $amount,
			'particular' => "Additional Customer Dues",
			'postpaid' => 1,
			'status' => 4,
			'invoice_type_id' => 7
		];

		if ($type == 'import') {

			$temp = $SkippedDuesRecord->toArray();
			$requestData = json_decode($temp['request_data']);

			foreach ($requestData as $key_rd => $val_rd) {
				$row = array();
				$row['person_name'] = trim($val_rd->person_name);
				$row['contact_phone_number'] = trim($val_rd->contact_phone_number);

				$row['aadhar_number'] = str_replace('-', '', $val_rd->aadhar_number);
				$row['aadhar_number'] = str_replace('_', '', $val_rd->aadhar_number);
				$row['aadhar_number'] = trim($val_rd->aadhar_number);

				$row['dob_ddmmyyyy'] = trim($val_rd->dob_ddmmyyyy);
				$row['father_name'] = trim($val_rd->father_name);
				$row['mother_name'] = trim($val_rd->mother_name);
				$row['duedate_ddmmyyyy'] = trim($val_rd->duedate_ddmmyyyy);
				$row['dueamount'] = str_replace(',', '', $val_rd->dueamount);
				$row['dueamount'] = trim($val_rd->dueamount);
				$row['duenote'] = trim($val_rd->duenote);
				$row['email'] = trim($val_rd->email);
				$row['grace_period'] = trim($val_rd->grace_period);
				$row['invoice_no'] = trim($val_rd->invoice_no);
				$row['custom_id'] = isset($val_rd->custom_id) ? $val_rd->custom_id : NULL;

				if (empty($row['person_name']) && empty($row['father_name']) && empty($row['mother_name']) && empty($row['contact_phone_number']) && empty($row['dob_ddmmyyyy']) && empty($row['duedate_ddmmyyyy']) && empty($row['dueamount']) && empty($row['duenote']) && empty($row['email']) && empty($row['grace_period'])) {
					break;
				}

				$row['dueamount'] = str_replace(',', '', $row['dueamount']);

				$reasons = '';

				$row['duedate_ddmmyyyy'] = str_replace('-', '/', $row['duedate_ddmmyyyy']);
				$row['dob_ddmmyyyy'] = str_replace('-', '/', $row['dob_ddmmyyyy']);

				$dob = '';
				if (!empty($row['dob_ddmmyyyy'])) {
					$newDob = Carbon::createFromFormat('d/m/Y', trim($row['dob_ddmmyyyy']));
					$dob = $newDob->format('Y-m-d');
				}

				$students = Students::where('person_name', 'LIKE', General::encrypt(strtolower($row['person_name'])))
					->where('contact_phone', '=', General::encrypt($row['contact_phone_number']))->whereNull('deleted_at')->first();

				if (empty($students)) {
					$students = Students::create([
						'person_name' => $row['person_name'],
						'dob' => $dob,
						'father_name' => $row['father_name'],
						'mother_name' => $row['mother_name'],
						'aadhar_number' => $row['aadhar_number'],
						'contact_phone' => $row['contact_phone_number'],
						'created_at' => Carbon::now(),
						'updated_at' => Carbon::now(),
						'added_by' => $authId
					]);

					$studentId = DB::getPdo()->lastInsertId();
					if ($studentId) {

						$dueDate = Carbon::createFromFormat('d/m/Y', trim($row['duedate_ddmmyyyy']));
						$dueDate  = $dueDate->format('Y-m-d');
						if ($row['grace_period'] == 0 || $row['grace_period'] == "" || $row['grace_period']<=1)
						{
							$gracePeriod = 1;
							$collectionDate = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 days'));
						} else {
							$gracePeriod = $row['grace_period'];
							$collectionDate = date('Y-m-d', strtotime($dueDate . '+ ' . $row['grace_period'] . ' days'));
						}
						$customStudentId = isset($row['custom_id']) ? $row['custom_id'] : NULL;
						$studentDue = StudentDueFees::create([
							'student_id' => $studentId,
							'due_date' => $dueDate,
							'due_amount' => $row['dueamount'],
							'due_note' => $row['duenote'],
							'created_at' => Carbon::now(),
							'added_by' => $authId,
							'invoice_no' => $row['invoice_no'],
							'external_student_id' => $customStudentId,
							'grace_period' => $gracePeriod,
							'collection_date' => $collectionDate
						]);

						$individual_response = General::generate_magic_url_function($row, "individual", $studentId, 'indivExcelBulk');
					}
				} else {

					$studentId = $students->id;
					$valuesForStudent = [
						'person_name' => $row['person_name'],
						'dob' => $dob,
						'father_name' => $row['father_name'],
						'mother_name' => $row['mother_name'],
						'aadhar_number' => $row['aadhar_number'],
						'contact_phone' => $row['contact_phone_number'],
						'updated_at' => Carbon::now(),
						//'added_by' => $authId,
					];

					$students->update($valuesForStudent);

					$dueDate = Carbon::createFromFormat('d/m/Y', trim($row['duedate_ddmmyyyy']));
					$dueDate  = $dueDate->format('Y-m-d');
					if ($row['grace_period'] == 0 || $row['grace_period'] == "" || $row['grace_period']<=1)
						{
							$gracePeriod = 1;
							$collectionDate = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 days'));
						} else {
							$gracePeriod = $row['grace_period'];
							$collectionDate = date('Y-m-d', strtotime($dueDate . '+ ' . $row['grace_period'] . ' days'));
						}
					$customStudentId = isset($row['custom_id']) ? $row['custom_id'] : NULL;
					$studentDue = StudentDueFees::create([
						'student_id' => $studentId,
						'due_date' => $dueDate,
						'due_amount' => $row['dueamount'],
						'due_note' => $row['duenote'],
						'created_at' => Carbon::now(),
						'added_by' => $authId,
						'invoice_no' => $row['invoice_no'],
						'external_student_id' => $customStudentId,
						'grace_period' => $gracePeriod,
						'collection_date' => $collectionDate
					]);
				}

				CustomerHelper::insertIntoMemberCustomerIdMappingTable($authId, $studentId, 1);

				$students->email = $row['email'];
				$students->save();

				/*if ($row['grace_period'] != 0) {

					$studentDue->grace_period = $row['grace_period'];
					if (strtotime($dueDate) <= strtotime(date('Y-m-d'))) {
						$studentDue->collection_date = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 days'));
					} else {
						$studentDue->collection_date = date('Y-m-d', strtotime($dueDate . '+ ' . $row['grace_period'] . ' days'));
					}

					// $row['collection_date'];
					$studentDue->save();
				}*/
			}

			$SkippedDuesRecord->delete();

			$membershipPayment = MembershipPayment::create($valuesForMembershipPayment);
			Session::remove('member_id');
			$response = app('App\Http\Controllers\HomeController')->postpaid_invoice_sendmail($membershipPayment->id);

			if (Auth::user()->hasRole('admin')) {
				return redirect('admin/import-excel-super/'.$authId)->withMessage('Success: Record added');
			}

			return redirect('admin/import-excel')->withMessage('Success: Record added');
		} else {
			$authId = Auth::id();
			$temp = $SkippedDuesRecord->toArray();
			$requestData = json_decode($temp['request_data']);

			$aadharCheck = isset($requestData->aadhar_number) ? $requestData->aadhar_number : NULL;
			$dobCheck = isset($requestData->dob) ? $requestData->dob : NULL;
			$aadhar_number = $aadharCheck;
			$contact_phone = $requestData->contact_phone;
			$invoice_no = $requestData->invoice_no;
			$person_name = $requestData->person_name;
			$dob = $dobCheck != '' ? Carbon::createFromFormat('d/m/Y', $dobCheck)->toDateTimeString() : '';
			$father_name = $requestData->father_name;
			$mother_name = $requestData->mother_name;

			if (!is_array($requestData->due_date)) {
				$due_date = Carbon::createFromFormat('d/m/Y', $requestData->due_date)->toDateTimeString();
			} else {
				$due_date = $requestData->due_date;
			}

			$due_amount = $requestData->due_amount;
			$due_note = $requestData->due_note;
			// $proof_of_due = $requestData->file('proof_of_due');
			$collection_date = $requestData->collection_date;
			$grace_period = $requestData->grace_period_hidden;
			//$custom_id = $requestData->external_student_id;
			// dd($id, $request->all(), $SkippedDuesRecord->toArray(), json_decode($SkippedDuesRecord->request_data), 'in');

			$proofOfDue = [];
			// $files = $request->file('proof_of_due');
			// if ($request->hasFile('proof_of_due')) {
			// 	foreach ($files as $key => $file) { //dd(file_get_contents($file->getRealPath()));
			// 		$file_get_contents = file_get_contents($file->getRealPath());

			// 		$proofOfDue[$key] = Storage::disk('public')->put('proof_of_due', $file);
			// 	}
			// }

			$students = Students::where('person_name', 'LIKE', General::encrypt(strtolower($person_name)))
				->where('contact_phone', '=', General::encrypt($contact_phone))->whereNull('deleted_at')->first();

			if (empty($students)) {
				$students = Students::create([
					'person_name' => $person_name,
					'dob' => $dob,
					'father_name' => $father_name,
					'mother_name' => $mother_name,
					'aadhar_number' => $aadhar_number,
					'contact_phone' => $contact_phone,
					'created_at' => Carbon::now(),
					'updated_at' => Carbon::now(),
					'added_by' => $authId
				]);

				$studentId = DB::getPdo()->lastInsertId();
				if ($studentId) {

					if (!is_array($due_amount)) {
						$studentDue = StudentDueFees::where('student_id', '=', $studentId)->where('due_date', '=', $due_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
					} else {
						foreach ($due_date as $key => $val) {
							$thisdue_date = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
							$studentDueArr[] = StudentDueFees::where('student_id', '=', $studentId)->where('due_date', '=', $thisdue_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
						}
					}

					foreach ($studentDueArr as $key => $arrval) {
						$proofDueValue = array_key_exists($key, $proofOfDue) ? $proofOfDue[$key] : "";

						try {
							if (empty($arrval)) {
								$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
								$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
								//$customStudentId = isset($custom_id[$key]) ? $custom_id[$key] : NULL;
								$studentDue = StudentDueFees::create([
									'student_id' => $studentId,
									'due_date' => $due_date_formated,
									'due_amount' => str_replace(',', '', $due_amount[$key]),
									'due_note' => $due_note[$key],
									'invoice_no' => $invoice_no[$key],
									'created_at' => Carbon::now(),
									'added_by' => $authId,
									'proof_of_due' => $proofDueValue,
									'collection_date' => $collection_date_formated,
									'grace_period' => $grace_period[$key],
									//'external_student_id' => $customStudentId,
								]);

							} else {
								if (!empty($proofDueValue)) {

									$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
									$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
									//$customStudentId = isset($custom_id[$key]) ? $custom_id[$key] : NULL;
									$studentDue->update([
										'student_id' => $studentId,
										'due_date' => $due_date_formated,
										'due_amount' => str_replace(',', '', $due_amount[$key]),
										'due_note' => $due_note[$key],
										'invoice_no' => $invoice_no[$key],
										'updated_at' => Carbon::now(),
										'proof_of_due' => $proofDueValue,
										'collection_date' => $collection_date_formated,
										'grace_period' => $grace_period[$key],
										//'external_student_id' => $customStudentId,
									]);
								} else {
									//foreach($due_date as $key=>$val) {
									$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
									$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
									//$customStudentId = isset($custom_id[$key]) ? $custom_id[$key] : NULL;
									$studentDue->update([
										'student_id' => $studentId,
										'due_date' => $due_date_formated,
										'due_amount' => str_replace(',', '', $due_amount[$key]),
										'due_note' => $due_note[$key],
										'invoice_no' => $invoice_no[$key],
										'updated_at' => Carbon::now(),
										'collection_date' => $collection_date_formated,
										'grace_period' => $grace_period[$key],
										//'external_student_id' => $customStudentId,
									]);
								}
							}
						} catch (Exception $e) {
							echo 'Message: ' . $e->getMessage();
						}
					}

					$individual_response = General::generate_magic_url_function($requestData, "individual", $studentId, 'indivSinglerecSkip');
				}
			} else {
				if ($students->id) {
					$studentId = $students->id;
					$valuesForStudent = [
						'person_name' => $person_name,
						'dob' => $dob,
						'father_name' => $father_name,
						'mother_name' => $mother_name,
						'aadhar_number' => $aadhar_number,
						'contact_phone' => $contact_phone,
						'updated_at' => Carbon::now()
					];

					/*if(empty($row['customer_number']) &&  empty($row['customer_number'])){
						if(empty($students->customer_no) && empty($students->customer_no)){
							$valuesForStudent['customer_no'] = $row['customer_number'];
							$valuesForStudent['invoice_no'] = $row['invoice_number'];
						}
					}else{
						$valuesForStudent['customer_no'] = $row['customer_number'];
						$valuesForStudent['invoice_no'] = $row['invoice_number'];
					}*/

					$students->update($valuesForStudent);

					if (!is_array($due_amount)) {
						$studentDue = StudentDueFees::where('student_id', '=', $studentId)->where('due_date', '=', $due_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
					} else {
						foreach ($due_date as $key => $val) {
							$thisdue_date = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
							$studentDueArr[] = StudentDueFees::where('student_id', '=', $studentId)->where('due_date', '=', $thisdue_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
						}
					}

					//dd($studentDueArr);
					//$studentDue = StudentDueFees::where('student_id','=',$studentId)->where('due_date','=',$due_date)->where('added_by',$authId)->whereNull('deleted_at')->first();

					foreach ($studentDueArr as $key => $arrval) {
						$proofDueValue = array_key_exists($key, $proofOfDue) ? $proofOfDue[$key] : "";
						try {
							if (empty($arrval)) {
								//if(empty($studentDue)){
								//foreach($due_date as $key=>$val) {
								$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
								$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
								//$customStudentId = isset($custom_id[$key]) ? $custom_id[$key] : NULL;
								$studentDue = StudentDueFees::create([
									'student_id' => $studentId,
									'due_date' => $due_date_formated,
									'due_amount' => str_replace(',', '', $due_amount[$key]),
									'due_note' => $due_note[$key],
									'invoice_no' => $invoice_no[$key],
									'created_at' => Carbon::now(),
									'proof_of_due' => $proofDueValue,
									'added_by' => $authId,
									'collection_date' => $collection_date_formated,
									'grace_period' => $grace_period[$key],
									//'external_student_id' => $customStudentId,
								]);
								//}
							} else {
								//if(!empty($proofOfDue[$key])){
								if (!empty($proofDueValue)) {
									$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
									$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
									//$customStudentId = isset($custom_id[$key]) ? $custom_id[$key] : NULL;
									$studentDue->update([
										'student_id' => $studentId,
										'due_date' => $due_date_formated,
										'due_amount' => str_replace(',', '', $due_amount[$key]),
										'due_note' => $due_note[$key],
										'invoice_no' => $invoice_no[$key],
										'updated_at' => Carbon::now(),
										'proof_of_due' => $proofDueValue,
										'collection_date' => $collection_date_formated,
										'grace_period' => $grace_period[$key],
										//'external_student_id' => $customStudentId,
									]);
								} else {
									foreach ($due_date as $key => $val) {
										$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
										$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
										//$customStudentId = isset($custom_id[$key]) ? $custom_id[$key] : NULL;
										$studentDue->update([
											'student_id' => $studentId,
											'due_date' => $due_date_formated,
											'due_amount' => str_replace(',', '', $due_amount[$key]),
											'due_note' => $due_note[$key],
											'invoice_no' => $invoice_no[$key],
											'updated_at' => Carbon::now(),
											'collection_date' => $collection_date_formated,
											'grace_period' => $grace_period[$key],
											//'external_student_id' => $customStudentId,
										]);
									}
								}
							}
						} catch (Exception $e) {
							echo 'Message: ' . $e->getMessage();
						}

						}
				}
			}

			CustomerHelper::insertIntoMemberCustomerIdMappingTable($authId, $studentId, 1);

			$students->email = $requestData->email;
			$students->save();


			/*One code hit transaction Api Call*/

			$duesCheck = StudentDueFees::where('added_by', Auth::id())->where('due_amount', '>=', 500)->get();

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

					General::add_to_debug_log(Auth::id(), "Initiated One code transaction Api Call.");
					$transactionPostData = array(
						"code" => $offerDataCheck->offer_code,
						"amount" => 0,
						"category" => "Basic",
						"transactionId" => Date('YmdHis')
					);
					$response = General::offer_codes_curl($transactionPostData, 'transaction');
					General::add_to_debug_log(Auth::id(), "One code transaction Api Call Success.");

					UsersOfferCodes::where('user_id', Auth::id())->update(array('offer_code_used' => 1, "response" => $response));
				}
			}

			/*One code hit transaction Api Call ends here*/
			$SkippedDuesRecord->delete();

			$membershipPayment = MembershipPayment::create($valuesForMembershipPayment);
			$response = app('App\Http\Controllers\HomeController')->postpaid_invoice_sendmail($membershipPayment->id);

			return redirect('admin/add-record')->withMessage('Success: Record added');
		}
	}

	public function postPaidForBusinessDues($id, $type = '')
	{
		if (Session::get('member_id')) {
			$authId = Session::get('member_id');
			$user = User::find($authId);
		} else {
			$authId = Auth::user()->id;
			$user = Auth::user();
		}

		$additional_customer_price = HomeHelper::getAdditionalCustomerPrice();

		$SkippedDuesRecord = SkippedDuesRecord::where('id', $id)->first();

		if (empty($SkippedDuesRecord)) {

			if ($type == "import") {
				$redirect_url = 'admin/import-excel-super';
				if (Auth::user()->hasRole('admin')) {
					$redirect_url = 'admin/import-excel-super/'.$authId;
				}
			} else {
				$redirect_url = 'business.add-record';
			}

			Log::debug('Empty SkippedDuesRecord in AddRecordController@postPaidForBusinessDues id ='.$id);
			return redirect($redirect_url)->with(['message' => "Something went wrong", 'alert-type' => 'error']);
		}

		$amount = ($SkippedDuesRecord->total_skipped_record_count * $additional_customer_price) + (($SkippedDuesRecord->total_skipped_record_count * $additional_customer_price * 18) / 100);

		$invoice_no = MembershipPayment::where('created_at', '>=', date('Y-m-d 00:00:00'))->where('status', 4)->count();
		$invoice_no = $invoice_no + 1;
		$valuesForMembershipPayment = [
			'customer_id' => $authId,
			'invoice_id' => date('dmY') . sprintf('%07d', $invoice_no),
			'pricing_plan_id' => 0,
			'customer_type' => "BUSINESS",
			'payment_value' => $SkippedDuesRecord->total_skipped_record_count * $additional_customer_price,
			'gst_perc' => 18,
			'gst_value' => (($SkippedDuesRecord->total_skipped_record_count * $additional_customer_price * 18) / 100),
			'total_collection_value' => $amount,
			'particular' => "Additional Customer Dues",
			'postpaid' => 1,
			'status' => 4,
			'invoice_type_id' => 7
		];

		if ($type == 'import') {

			$temp = $SkippedDuesRecord->toArray();
			$requestData = json_decode($temp['request_data']);

			foreach ($requestData as $key_rd => $val_rd) {
				$row = array();
				$row = (array)$val_rd;

				foreach ($row as $key => &$value) {
					if (!empty($key)) {
						$value = trim($value);
					}
				}

				if (!preg_match("/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/", $row['duedate_ddmmyyyy'])) {
					if ($row['duedate_ddmmyyyy'] != "") {
						$row['duedate_ddmmyyyy'] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['duedate_ddmmyyyy']))->format('d/m/Y');
					}
				}

				if (
					empty($row['business_name']) &&
					empty($row['sector_name']) &&
					empty($row['unique_identification_number_gstin_business_pan']) &&
					empty($row['concerned_person_name']) &&
					empty($row['concerned_person_designation']) &&
					empty($row['concerned_person_phone']) &&
					empty($row['state']) &&
					empty($row['city']) &&
					empty($row['duedate_ddmmyyyy']) &&
					empty($row['dueamount']) &&
					empty($row['email']) &&
					empty($row['grace_period'])
				) {
					break;
				}

				$row['dueamount'] = str_replace(',', '', $row['dueamount']);

				$sectorId = '';
				if (!empty($row['sector_name'])) {
					$sector = Sector::where('name', '=', $row['sector_name'])->first();
					if ($sector) {
						// $sectorId = $sector->id;
					}
				}

				$stateId = '';
				if (!empty($row['state'])) {
					$state = State::where('name', '=', $row['state'])->first();
					if ($state) {
						$stateId = $state->id;
					}
				}

				$cityId = '';
				if (!empty($row['city'])) {
					if (!empty($stateId)) {
						$city = City::where('name', '=', $row['city'])->where('state_id', $stateId)->first();
						if ($city) {
							$cityId = $city->id;
						}
					}
				}
				$customBusinessId = isset($row['custom_id']) ? $row['custom_id'] : NULL;
				$row['duedate_ddmmyyyy'] = str_replace('-', '/', $row['duedate_ddmmyyyy']);
				$businesses = Businesses::where('unique_identification_number', '=', General::encrypt(strtoupper($row['unique_identification_number_gstin_business_pan'])))
					->where('concerned_person_phone','=',General::encrypt(strtolower($row['concerned_person_phone'])))
					->whereNull('deleted_at')->first();

				if (empty($businesses)) {
					$businesses = Businesses::create([
						'company_name' => $row['business_name'],
						// 'sector_id' => $sectorId,
						'unique_identification_number' => $row['unique_identification_number_gstin_business_pan'],
						'concerned_person_name' => $row['concerned_person_name'],
						'concerned_person_designation' => $row['concerned_person_designation'],
						'concerned_person_phone' => $row['concerned_person_phone'],
						'concerned_person_alternate_phone' => $row['concerned_person_alternate_phone'],
						'state_id' => $stateId,
						'city_id' => $cityId,
						'pincode' => $row['pin_code'],
						'address' => $row['address'],
						'created_at' => Carbon::now(),
						'updated_at' => Carbon::now(),
						'added_by' => $authId
					]);

					$businessId = DB::getPdo()->lastInsertId();
					if ($businessId) {

						$dueDate = Carbon::createFromFormat('d/m/Y', $row['duedate_ddmmyyyy']);
						$dueDate  = $dueDate->format('Y-m-d');

						if ($row['grace_period'] == 0 || $row['grace_period'] == "" || $row['grace_period']<=1){
							$gracePeriod = 1;
							$collectionDate = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 days'));
						} else {
							$gracePeriod = $row['grace_period'];
							$collectionDate = date('Y-m-d', strtotime($dueDate . '+ ' . $row['grace_period'] . ' days'));
						}

						$businessDue = BusinessDueFees::create([
							'business_id' => $businessId,
							'due_date' => $dueDate,
							'due_amount' => $row['dueamount'],
							//'due_note'=> $row['duenote'],
							'created_at' => Carbon::now(),
							'added_by' => $authId,
							'invoice_no' => $row['invoice_no'],
							'external_business_id' => $customBusinessId,
							'grace_period' => $gracePeriod,
							'collection_date' => $collectionDate
						]);

						$individual_response = General::generate_magic_url_function($row, "business", $businessId, 'BusinessExcelBulk');
					}
				} else {
					$businessId = $businesses->id;
					$valuesForBusiness = [
						'company_name' => $row['business_name'],
						// 'sector_id' => $sectorId,
						'unique_identification_number' => $row['unique_identification_number_gstin_business_pan'],
						'concerned_person_name' => $row['concerned_person_name'],
						'concerned_person_designation' => $row['concerned_person_designation'],
						'concerned_person_phone' => $row['concerned_person_phone'],
						'concerned_person_alternate_phone' => $row['concerned_person_alternate_phone'],
						'state_id' => $stateId,
						'city_id' => $cityId,
						'pincode' => $row['pin_code'],
						'address' => $row['address'],
						'updated_at' => Carbon::now(),
						'external_business_id' => $customBusinessId,
					];

					$businesses->update($valuesForBusiness);

					$dueDate = Carbon::createFromFormat('d/m/Y', $row['duedate_ddmmyyyy']);
					$dueDate  = $dueDate->format('Y-m-d');

					if ($row['grace_period'] == 0 || $row['grace_period'] == "" || $row['grace_period']<=1){
						$gracePeriod = 1;
						$collectionDate = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 days'));
					} else {
						$gracePeriod = $row['grace_period'];
						$collectionDate = date('Y-m-d', strtotime($dueDate . '+ ' . $row['grace_period'] . ' days'));
					}

					$businessDue = BusinessDueFees::create([
						'business_id' => $businessId,
						'due_date' => $dueDate,
						'due_amount' => str_replace(',', '', $row['dueamount']),
						//'due_note'=> $row['duenote'],
						'created_at' => Carbon::now(),
						'added_by' => $authId,
						'invoice_no' => $row['invoice_no'],
						'external_business_id' => $customBusinessId,
						'grace_period' => $gracePeriod,
						'collection_date' => $collectionDate
					]);
				}

				CustomerHelper::insertIntoMemberCustomerIdMappingTable($authId, $businessId, 2);
				$businesses->email = $row['email'];
				$businesses->save();

				/*if ($row['grace_period'] != 0) {
					$businessDue->grace_period = $row['grace_period'];
					if (strtotime($dueDate) <= strtotime(date('Y-m-d'))) {
						$businessDue->collection_date = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 days'));
					} else {
						$businessDue->collection_date = date('Y-m-d', strtotime($dueDate . '+ ' . $row['grace_period'] . ' days'));
					}

					// $row['collection_date'];
					$businessDue->save();
				}*/
			}

			$SkippedDuesRecord->delete();

			$membershipPayment = MembershipPayment::create($valuesForMembershipPayment);
			$response = app('App\Http\Controllers\HomeController')->postpaid_invoice_sendmail($membershipPayment->id);

			if (Auth::user()->hasRole('admin')) {
				return redirect('admin/import-excel-super/'.$authId)->withMessage('Success: Records imported');
			}

			return redirect('admin/business/import-excel')->withMessage('Success: Record added');
		} else {
			$authId = Auth::id();
			$temp = $SkippedDuesRecord->toArray();
			$requestData = json_decode($temp['request_data']);

			$company_name = $requestData->company_name;

			$sector_id = $requestData->sector_id;
			$unique_identification_number = $requestData->unique_identification_number;
			$concerned_person_name = $requestData->concerned_person_name;
			$concerned_person_designation = $requestData->concerned_person_designation;
			$concerned_person_phone = $requestData->concerned_person_phone;
			$concerned_person_alternate_phone = $requestData->concerned_person_alternate_phone;
			$state_id = $requestData->state;
			$city_id = $requestData->city;
			$pincode = $requestData->pin_code;
			$address = $requestData->address;
			if (!is_array($requestData->due_date)) {
				$due_date = Carbon::createFromFormat('d/m/Y', $requestData->due_date)->toDateTimeString();
			} else {
				$due_date = $requestData->due_date;
			}
			// $paid_date = $requestData->paid_date;
			// $paid_amount = $requestData->paid_amount;
			$due_amount = $requestData->due_amount;
			$due_note = $requestData->due_note;
			// $paid_note = $requestData->paid_note;
			$invoice_no = $requestData->invoice_no;
			// $proof_of_due = $requestData->file('proof_of_due');
			$collection_date = $requestData->collection_date;
			$grace_period = $requestData->grace_period_hidden;
			//$custom_id = $requestData->external_business_id;
			$proofOfDue = [];

			$business = Businesses::where('unique_identification_number', '=', General::encrypt(strtoupper($unique_identification_number)))
				->where('concerned_person_phone','=',General::encrypt(strtolower($concerned_person_phone)))
				->whereNull('deleted_at')
				->first();

			// if ($request->hasFile('proof_of_due')) {
			// 	foreach ($proof_of_due as $key => $file) { //dd(file_get_contents($file->getRealPath()));
			// 		$file_get_contents = file_get_contents($file->getRealPath());
			// 		$proofOfDue[$key] = Storage::disk('public')->put('business/proof_of_due', $file);
			// 	}
			// }

			if (empty($business)) {

				$business = Businesses::create([
					'company_name' => $company_name,
					'sector_id' => $sector_id,
					'unique_identification_number' => $unique_identification_number,
					'concerned_person_name' => $concerned_person_name,
					'concerned_person_designation' => $concerned_person_designation,
					'concerned_person_phone' => $concerned_person_phone,
					'concerned_person_alternate_phone' => $concerned_person_alternate_phone,
					'state_id' => $state_id,
					'city_id' => $city_id,
					'pincode' => $pincode,
					'address' => $address,
					'created_at' => Carbon::now(),
					'added_by' => $authId
				]);

				$businessId = DB::getPdo()->lastInsertId();
				if ($businessId) {

					foreach ($due_amount as $key => $val) {
						$proofDueValue = array_key_exists($key, $proofOfDue) ? $proofOfDue[$key] : "";
						$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
						$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
						//$customBusinessId = isset($custom_id[$key]) ? $custom_id[$key] : NULL;
						$businessDue = BusinessDueFees::create([
							'business_id' => $businessId,
							'due_date' => $due_date_formated,
							'due_amount' => str_replace(',', '', $due_amount[$key]),
							'due_note' => $due_note[$key],
							'invoice_no' => $invoice_no[$key],
							'created_at' => Carbon::now(),
							'added_by' => $authId,
							'proof_of_due' => $proofDueValue,
							'collection_date' => $collection_date_formated,
							'grace_period' => $grace_period[$key],
							//'external_business_id' => $customBusinessId,
						]);
					}

					$individual_response = General::generate_magic_url_function($requestData, "business", $businessId, 'BusinessRecSkip');

					/*if($paid_date != ''){
						$businessPaid = BusinessPaidFees::create([
							'business_id' => $businessId,
							'due_id' => $businessDue->id,
							'paid_date' => $paid_date,
							'paid_amount'=> $paid_amount,
							'paid_note'=> $paid_note,
							'added_by' => $authId,
							'created_at' => Carbon::now(),
						]);
						General::storeAdminNotificationForPayment('Business',$businessPaid->id);
					}*/
				}
			} else {

				if ($business->id) {
					$businessId = $business->id;
					$valuesForStudent = [
						'company_name' => $company_name,
						'sector_id' => $sector_id,
						'concerned_person_name' => $concerned_person_name,
						'concerned_person_designation' => $concerned_person_designation,
						'concerned_person_phone' => $concerned_person_phone,
						'concerned_person_alternate_phone' => $concerned_person_alternate_phone,
						'state_id' => $state_id,
						'city_id' => $city_id,
						'pincode' => $pincode,
						'address' => $address,
						'updated_at' => Carbon::now()
					];

					$business->update($valuesForStudent);

					if (!is_array($due_amount)) {
						$businessDue = BusinessDueFees::where('business_id', '=', $businessId)->where('due_date', '=', $due_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
					} else {
						foreach ($due_date as $key => $val) {

							$thisdue_date = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
							$businessDueArr[] = BusinessDueFees::where('business_id', '=', $businessId)->where('due_date', '=', $thisdue_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
						}
					}

					//if(empty($businessDue)){
					foreach ($businessDueArr as $key => $arrval) {
						$proofDueValue = array_key_exists($key, $proofOfDue) ? $proofOfDue[$key] : "";
						$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
						$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
						//$customBusinessId = isset($custom_id[$key]) ? $custom_id[$key] : NULL;
						if (empty($arrval)) {
							$businessDue = BusinessDueFees::create([
								'business_id' => $businessId,
								'due_date' => $due_date_formated,
								'due_amount' => str_replace(',', '', $due_amount[$key]),
								'due_note' => $due_note[$key],
								'invoice_no' => $invoice_no[$key],
								'created_at' => Carbon::now(),
								'added_by' => $authId,
								'proof_of_due' => $proofDueValue,
								'collection_date' => $collection_date_formated,
								'grace_period' => $grace_period[$key],
								//'external_business_id' => $customBusinessId,

							]);
						} else {
							if (!empty($proofOfDue)) {
								$businessDue->update([
									'business_id' => $businessId,
									'due_date' => $due_date_formated,
									'due_amount' => str_replace(',', '', $due_amount[$key]),
									'due_note' => $due_note[$key],
									'updated_at' => Carbon::now(),
									'proof_of_due' => $proofDueValue,
									'invoice_no' => $invoice_no[$key],
									'collection_date' => $collection_date_formated,
									'grace_period' => $grace_period[$key],
									//'external_business_id' => $customBusinessId,
								]);
							} else {
								$businessDue->update([
									'business_id' => $businessId,
									'due_date' => $due_date_formated,
									'due_amount' => str_replace(',', '', $due_amount[$key]),
									'due_note' => $due_note[$key],
									'invoice_no' => $invoice_no[$key],
									'updated_at' => Carbon::now(),
									'collection_date' => $collection_date_formated,
									'grace_period' => $grace_period[$key],
									//'external_business_id' => $customBusinessId,
								]);
							}
						}
					}
				}
			}

			CustomerHelper::insertIntoMemberCustomerIdMappingTable($authId, $businessId, 2);
			// $business->email = $request->email;
			$business->save();


			// if($request->grace_period!=0){
			//$businessDue->grace_period=$request->grace_period;
			//$collection_date = Carbon::createFromFormat('d/m/Y', $request->collection_date)->toDateTimeString();
			//$businessDue->collection_date=$collection_date;
			//$businessDue->save();
			// }

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
					$transactionPostData = array(
						"code" => $offerDataCheck->offer_code,
						"amount" => 0,
						"category" => "Basic",
						"transactionId" => Date('YmdHis')
					);

					General::add_to_debug_log(Auth::id(), "Business - Initiated One code transaction Api Call.");

					$response = General::offer_codes_curl($transactionPostData, 'transaction');
					General::add_to_debug_log(Auth::id(), "Business - One code transaction Api Call Success.");

					UsersOfferCodes::where('user_id', Auth::id())->update(array('offer_code_used' => 1, "response" => $response));
				}
			}
			/*One code hit transaction Api Call ends here*/

			$SkippedDuesRecord->delete();

			$membershipPayment = MembershipPayment::create($valuesForMembershipPayment);
			$response = app('App\Http\Controllers\HomeController')->postpaid_invoice_sendmail($membershipPayment->id);

			return redirect(route('business.add-record'))->withMessage('Success: Record added');
		}
		
	}


	public function assignproofduestore(Request $request){

		$proofOfDue_file=$request->file_name;
		$due_ids=$request->hdnSelected;
		if(empty($proofOfDue_file) || empty($due_ids)){
			return redirect()->back()->withMessage('error: something went wrong');
		}

		$dueId_array=explode(",",$due_ids);
		foreach($dueId_array as $due_id)
		{
			$data=StudentDueFees::where('id', $due_id)->where('added_by', Auth::id())->get();
			foreach($data as $rec)
			{
				if(!empty($rec['proof_of_due']))
				{
					$proofOfDue=str_replace("proof_of_due/","",$proofOfDue_file);
					$proofOfDue=$rec['proof_of_due'].",".$proofOfDue;
				}else{
					$proofOfDue=$proofOfDue_file;
				}
			    $status=StudentDueFees::where('id', $due_id)
										->where('added_by', Auth::id())
										->update(['proof_of_due'=>$proofOfDue]);
			}	
		}
		return redirect()->back()->withMessage('Success: Proof of due is uploaded');

	}

	public function proofduestore(Request $request) {
		if(isset($request->customer_id)){
			$student_id= $request->customer_id;
		}else{
			$student_id= $request->customer_ids;
		}

		$files = $request->file('proof_of_due');
		if(empty($student_id) || empty($files)){
			return redirect()->back()->withMessage('error: something went wrong');
		}

		if ($request->hasFile('proof_of_due')) {
			foreach ($files as  $file) {
				$file_get_contents = file_get_contents($file->getRealPath());
				$proofOfDue_file = Storage::disk('public')->put('proof_of_due', $file);
				$proofOfDue=str_replace("proof_of_due/","",$proofOfDue_file);
			}
		}   
		$data=Students::where('id', $student_id)->where('added_by', Auth::id())->get(); 
		foreach($data as $rec)
		{
			if(!empty($rec['proof_of_due']))
			{
				$proofOfDue=$rec['proof_of_due'].",".$proofOfDue;
			}else{
				$proofOfDue=$proofOfDue_file;
			}
			$status=Students::where('id', $student_id)->where('added_by', Auth::id())->update([
				'proof_of_due'=>$proofOfDue
			]);	
		}
		return redirect()->back()->withMessage('Success: Proof of due is uploaded');
	
	}

}
