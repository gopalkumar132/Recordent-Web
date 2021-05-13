<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Businesses;
use App\BusinessDueFees;
use App\BusinessPaidFees;
use App\Sector;
use App\Country;
use App\State;
use App\City;
use App\SkippedDuesRecord;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use Storage;
use General;
use Mail;
use App\Services\SmsService;
use App\Individuals;
use App\UsersOfferCodes;
use App\UserType;
use Illuminate\Support\Facades\Mail as SendMail;
use HomeHelper;
use CustomerHelper;

class AddRecordController extends Controller
{

	public function index(Request $request)
	{
		$states = State::where('country_id', 101)->get();
		$stateIds = [];
		foreach ($states as $state) {
			$stateIds[] = $state->id;
		}
		$cities = City::whereIn('state_id', $stateIds)->orderBy('name', 'ASC')->get();
		$sectors = Sector::where('status', 1)->whereNull('deleted_at')->orderBy('id', 'ASC')->get();
		$userTypes = UserType::where('status',1)->orderBy('name','ASC')->get();
		return view('admin.business.add-record.add-record', compact('states', 'cities', 'sectors', 'userTypes'));
	}


	/**
	 * @param Request Add Data
	 *
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */

	public function store(Request $request)
	{
		
		$requestData = $request->all();
		$remainingRecords = array();
		$recordsToAllowCount = 0;
		$totalSkippedRecordCount = 0;
		// $remainingCustomer = General::getFreeCustomersDuesLimit(Auth::id());
		$remainingCustomer = CustomerHelper::getRemainingFreeCustomersDuesLimit(Auth::id());

		$company_name_min_character = setting('admin.company_name_min_character');
		$company_name_min_character = $company_name_min_character ? $company_name_min_character : 1;
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

		if (!is_array($request->due_amount)) {
			$request->merge(['due_amount' => str_replace(',', '', $request->due_amount)]);
		} else {
			$request_dueamount_merge = [];
			foreach ($request->due_amount as $key => $val) {
				$request_dueamount_merge[] = str_replace(',', '', $val);
			}
			$request->merge(["due_amount" => $request_dueamount_merge]);
		}
		$name_max_character= General::maxlength('name');

		$rule = [
			'company_name' => 'required|string|max:'.$name_max_character.'|min:' . $company_name_min_character,
			'user_type' => 'required|numeric',
			'unique_identification_number' => 'required|alpha_num|max:15',
			'concerned_person_name' => 'required|regex:/^[\pL\s]+$/u|max:'.$name_max_character,
			'concerned_person_designation' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
			'concerned_person_phone' => 'required|regex:/^([0-9\+\(\)]*)$/|min:10|max:10|starts_with:6,7,8,9',
			'concerned_person_alternate_phone' => 'nullable|regex:/^([0-9\+\(\)]*)$/|min:10|max:10|starts_with:6,7,8,9',
			'state' => 'required|integer',
			'city' => 'required|integer',
			'pin_code' => 'nullable|digits:6',
			'address' => 'nullable|string',
			//'due_date' => 'required||date_format:d/m/Y',
			//'paid_date' =>'nullable|date|after_or_equal:due_date',
			//'paid_amount' => 'nullable|numeric|lte:due_amount',
			//'due_amount' => 'required|numeric|gt:0|lte:1000000000',
			//'proof_of_due' => 'mimes:jpeg,bmp,png,gif,svg,pdf',
			//'due_note'=>'nullable|string|max:300',
			'due_date' => 'required|array|min:1',
			'due_date.*' => 'required|date_format:d/m/Y',
			'due_amount' => 'required|array|min:1',
			'due_amount.*' => 'required|numeric|gt:0|lte:1000000000',
			'due_note' => 'array|min:1',
			'due_note.*' => 'nullable|string|max:300',
			'proof_of_due' => 'array',
			'proof_of_due.*' => 'mimes:jpeg,jpg,bmp,xls,xlsx,png,pdf,doc,docx,txt',
		];
		if ($due_date_old_in_year) {
			$rule['due_date.*'] = $rule['due_date.*'] . '|after_or_equal:' . $due_date_old_in_year;
		}

		if ($due_date_max_future_in_year) {
			$rule['due_date.*'] = $rule['due_date.*'] . '|before_or_equal:' . $due_date_max_future_in_year;
		}

		$ruleMessage = [
			'company_name.required' => 'The business name field is required.',
			'company_name.string' => 'The business name must be a string.',
			'company_name.max' => 'The business name may not be greater than :max characters.',
			'company_name.min' => 'The business name must be at least :min characters.',
			'unique_identification_number.required' => 'The ' . General::getLabelName('unique_identification_number') . ' field is required.',
			'unique_identification_number.string' => 'The ' . General::getLabelName('unique_identification_number') . ' field must be a valid string.',
			'unique_identification_number.max' => 'The ' . General::getLabelName('unique_identification_number') . ' may not be greater than :max characters.',
			'concerned_person_name.regex' => 'The :attribute may only contain letters and space.',
			'concerned_person_designation.regex' => 'The :attribute may only contain letters, dash and space.',
			'due_amount.lte' => 'The :attribute must be less than or equal 1,00,00,00,000'
		];
		if ($due_date_old_in_year) {
			$ruleMessage['due_date.after_or_equal'] = 'The Due date must be a date after or equal to ' . $due_date_old_in_year;
		}
		if ($due_date_max_future_in_year) {
			$ruleMessage['due_date.before_or_equal'] = 'The Due date must be a date before or equal to ' . $due_date_max_future_in_year . " " . setting('admin.due_date_max_future_in_year');
		}
		$validator = Validator::make($request->all(), $rule, $ruleMessage);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$type_of_business = NULL;
        if ($request->has('type_of_business')) {
            $type_of_business = General::encrypt($request->type_of_business);
        }
        $type_of_sector = NULL;
        if ($request->has('type_of_sector')) {
            $type_of_sector = General::encrypt($request->type_of_sector);
        }

		$company_name = $request->company_name;

		$sector_id = $request->sector_id;
		$user_type = $request->user_type;
		$unique_identification_number = $request->unique_identification_number;
		
		$concerned_person_name = $request->concerned_person_name;
		$concerned_person_designation = $request->concerned_person_designation;
		$concerned_person_phone = $request->concerned_person_phone;
		$concerned_person_alternate_phone = $request->concerned_person_alternate_phone;
		
		$state_id = $request->state;
		$city_id = $request->city;
		$pincode = $request->pin_code;
		$address = $request->address;
		
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
		$paid_date = $request->paid_date;
		$paid_amount = $request->paid_amount;
		$due_amount = $request->due_amount;
		$due_note = $request->due_note;
		$paid_note = $request->paid_note;
		$invoice_no = $request->invoice_no;
		$proof_of_due = $request->file('proof_of_due');
		$collection_date = $request->collection_date;
		$grace_period = $request->grace_period_hidden;
		//$external_business_id = $request->external_business_id;
		$proofOfDue = [];
		
		$credit_period = $request->credit_period;
		$credit_period_new=array();
		foreach($credit_period as $key=>$val)
		{
			if($val == null)
			{
				$credit_period_new[]=0;
			}else
			{
				$credit_period_new[]=$val;
			}
		}
		
		$credit_period = $credit_period_new;
		/*if(!empty($request->file('proof_of_due'))){
			$proofOfDue = Storage::disk('public')->put('business/proof_of_due', $request->file('proof_of_due'));
		}*/
		$authId = Auth::id();
		
		$business = Businesses::where('unique_identification_number', '=', General::encrypt(strtoupper($unique_identification_number)))
				->where('concerned_person_phone','=',General::encrypt(strtolower($concerned_person_phone)))
				// ->where('added_by', $authId)
				->whereNull('deleted_at')->first();

		$is_new_business_customer = true;
		if(!empty($business) && CustomerHelper::isAlreadyExistingCustomer($authId, $business->id, 2)){
			$is_new_business_customer = false;
		}
		
		if ($remainingCustomer <= 0 && $is_new_business_customer) {
			$remainingRecords = $requestData;
			$totalSkippedRecordCount = 1;

			$SkippedDuesRecord = new SkippedDuesRecord();
			$SkippedDuesRecord->user_id = Auth::user()->id;
			$SkippedDuesRecord->request_data = json_encode($requestData);
			$SkippedDuesRecord->total_skipped_record_count = $totalSkippedRecordCount;
			$SkippedDuesRecord->save();

			return view('admin.add-record.skipped-business-popup', compact('SkippedDuesRecord', 'requestData', 'totalSkippedRecordCount'));
		} else {
			$recordsToAllowCount = count($request->due_date);
			$totalSkippedRecordCount = 0;
		}

		/*if ($request->hasFile('proof_of_due')) {
			foreach ($proof_of_due as $key => $file) { //dd(file_get_contents($file->getRealPath()));
				$file_get_contents = file_get_contents($file->getRealPath());
				$proofOfDue[$key] = Storage::disk('public')->put('business/proof_of_due', $file);
			}
		}*/

		if (empty($business)) {

			$business = Businesses::create([
				'company_name' => $company_name,
				'sector_id' => $sector_id,
				'user_type' => $user_type,
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
				'type_of_business' => $type_of_business,
				'type_of_sector' => $type_of_sector,
				'added_by' => $authId
			]);

			$businessId = DB::getPdo()->lastInsertId();
			if ($businessId) {

				foreach ($due_amount as $key => $val) {
					if ($recordsToAllowCount > 0) {
						//$proofDueValue = array_key_exists($key, $proofOfDue) ? $proofOfDue[$key] : "";
						$proofDueValue_proof="";
						$files = $request->file('proof_of_due_'.$key);
						
						if($files == null){
							$proofDueValue="";
						} else {
							$num_of_items = count($files);
							$num_count = 0;
							
							if ($request->hasFile('proof_of_due_'.$key)) {
								foreach ($files as $Proof_key => $file) { //dd(file_get_contents($file->getRealPath()));
									$file_get_contents = file_get_contents($file->getRealPath());

									$proofOfDue[$Proof_key] = Storage::disk('public')->put('business/proof_of_due', $file);
									$proofDueValue=$proofOfDue[$Proof_key];

									$num_count = $num_count + 1;
									if ($num_count < $num_of_items) {
										$str=",";
									} else {
										$str=""; 
									}

									$proofDueValue_proof.=str_replace("business/proof_of_due/","",$proofDueValue).$str;
								}
							}

							$proofDueValue="business/proof_of_due/".$proofDueValue_proof;
						}

						$due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
						if( $invoice_date[$key] == null){
								$invoice_date_formated = '';
						} else {
							$invoice_date_formated = Carbon::createFromFormat('d/m/Y', $invoice_date[$key])->toDateTimeString();
						}

						$collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
						$recordsToAllowCount--;

						//$customBusinessId = isset($external_business_id[$key]) ? $external_business_id[$key] : NULL;
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
							'invoice_date'=>$invoice_date_formated,
							'balance_due'=>str_replace(',', '', $due_amount[$key]),
						]);

						unset($requestData['invoice_no'][$key]);
						unset($requestData['due_amount'][$key]);
						unset($requestData['due_date'][$key]);
						unset($requestData['grace_period'][$key]);
						unset($requestData['grace_period_hidden'][$key]);
						unset($requestData['collection_date'][$key]);
						unset($requestData['due_note'][$key]);
						//unset($customBusinessId);
					}
				}

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
			
			/*----------------------------Magic link Gen Start-----------------------------*/
			// $businessId = DB::getPdo()->lastInsertId();
			$business_response=General::generate_magic_url_function($request,"business",$businessId,'');
			if($business_response['email']){
				if(empty($business_response['uniqe_url_business']))
				{
					//$response=General::sendMail($business_response,"Business");
				}
			}

			/*----------------------------Magic link Gen End-----------------------------*/
		} else {

			if ($business->id) {
				$businessId = $business->id;
				$valuesForStudent = [
					'company_name' => $company_name,
					'sector_id' => $sector_id,
					'user_type' => $user_type,
					'concerned_person_name' => $concerned_person_name,
					'concerned_person_designation' => $concerned_person_designation,
					'concerned_person_phone' => $concerned_person_phone,
					'concerned_person_alternate_phone' => $concerned_person_alternate_phone,
					'state_id' => $state_id,
					'city_id' => $city_id,
					'pincode' => $pincode,
					'address' => $address,
					'type_of_business' => $type_of_business,
			     	'type_of_sector' => $type_of_sector,
					'updated_at' => Carbon::now(),
				];

				$business->update($valuesForStudent);

				if (!is_array($due_amount)) {
					$businessDue = BusinessDueFees::where('business_id', '=', $businessId)->where('due_date', '=', $due_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
				} else {
					foreach ($due_date as $key => $val) {
						if ($recordsToAllowCount > 0) {
							$thisdue_date = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
							$businessDueArr[] = BusinessDueFees::where('business_id', '=', $businessId)->where('due_date', '=', $thisdue_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
							$recordsToAllowCount--;
							unset($requestData['invoice_no'][$key]);
							unset($requestData['due_amount'][$key]);
							unset($requestData['due_date'][$key]);
							unset($requestData['grace_period'][$key]);
							unset($requestData['grace_period_hidden'][$key]);
							unset($requestData['collection_date'][$key]);
							unset($requestData['due_note'][$key]);
							unset($requestData['invoice_date'][$key]);
						}
					
					}
				}

				//if(empty($businessDue)){
				foreach ($businessDueArr as $key => $arrval) {
					//$proofDueValue = array_key_exists($key, $proofOfDue) ? $proofOfDue[$key] : NULL;
					$proofDueValue_proof="";
                    $files = $request->file('proof_of_due_'.$key);
                    
                    if($files == null)
                    {
                        $proofDueValue="";
                    }else{
                            $num_of_items = count($files);
                                $num_count = 0;
                            if ($request->hasFile('proof_of_due_'.$key)) {
                                foreach ($files as $Proof_key => $file) { //dd(file_get_contents($file->getRealPath()));
                                    $file_get_contents = file_get_contents($file->getRealPath());

                                    $proofOfDue[$Proof_key] = Storage::disk('public')->put('business/proof_of_due', $file);
                                    $proofDueValue=$proofOfDue[$Proof_key];

                                    $num_count = $num_count + 1;
                                    if ($num_count < $num_of_items) {
                                        $str=",";
                                    }else{
                                        $str=""; 
                                    }

                                    $proofDueValue_proof.=str_replace("business/proof_of_due/","",$proofDueValue).$str;
                                }
                            }
                    $proofDueValue="business/proof_of_due/".$proofDueValue_proof;
                    }
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
					//$customBusinessId = isset($external_business_id[$key]) ? $external_business_id[$key] : NULL;
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
							'invoice_date'=>$invoice_date_formated,
							'balance_due'=>str_replace(',', '', $due_amount[$key])

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
								'invoice_date'=>$invoice_date_formated,
								'balance_due'=>str_replace(',', '', $due_amount[$key])
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
								'invoice_date'=>$invoice_date_formated,
								'balance_due'=>str_replace(',', '', $due_amount[$key])
							]);
						}
					}
					/*if($paid_date != ''){

					
					$businessPaid = BusinessPaidFees::where('due_id','=',$businessDue->id)->where('paid_date','=',$paid_date)->where('added_by',$authId)->whereNull('deleted_at')->first();
					if(empty($businessPaid)){
						$businessPaid = BusinessPaidFees::create([
								'business_id' => $businessId,
								'due_id' => $businessDue->id,
								'paid_date' => $paid_date,
								'paid_amount'=> $paid_amount,
								'paid_note'=> $paid_note,
								'added_by' => $authId,
								'created_at' => Carbon::now()
						]);
						General::storeAdminNotificationForPayment('Business',$businessPaid->id);
					}else{
						$businessPaid->update([
								'business_id' => $businessId,
								'due_id' => $businessDue->id,
								'paid_amount'=> $paid_amount,
								'paid_note'=> $paid_note,
								'updated_at' => Carbon::now()
						]);
						General::storeAdminNotificationForPayment('Business',$businessPaid->id);
					}
				}*/
				
				}
			}
		}

		CustomerHelper::insertIntoMemberCustomerIdMappingTable($authId, $businessId, 2);
		$business->email = $request->email;
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
											"transactionId" => Auth::id()
										);

				General::add_to_debug_log(Auth::id(), "Business - Initiated One code transaction Api Call.");

				$response = General::offer_codes_curl($transactionPostData, 'transaction');
				General::add_to_debug_log(Auth::id(), "Business - One code transaction Api Call Success.");

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

			view('admin.add-record.skipped-business-popup', compact('SkippedDuesRecord', 'requestData', 'totalSkippedRecordCount'));
		}

		/*$skipEmailNotification=false;
		if (General::Checkmemberid_skip_email_notifications_for_dues()) {
			$skipEmailNotification = true;
		}
		if($skipEmailNotification == false){
			if(!empty($business)){
		        $name = $business->concerned_person_name;
		        $email = $business->email;
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

	public function BusinessAssignProofDuestore(Request $request){

		$proofOfDue_file=$request->file_name;
		$due_ids=$request->hdnSelected;
		if(empty($proofOfDue_file) || empty($due_ids)){
			return redirect()->back()->withMessage('error: something went wrong');
		}
		$dueId_array=explode(",",$due_ids);
		
		foreach($dueId_array as $due_id)
		{
			$data=BusinessDueFees::where('id', $due_id)->where('added_by', Auth::id())->get();
			foreach($data as $rec)
			{
				if(!empty($rec['proof_of_due']))
				{
					$proofOfDue=str_replace("business/proof_of_due/","",$proofOfDue_file);
					$proofOfDue=$rec['proof_of_due'].",".$proofOfDue;
				}else{
					$proofOfDue='business/'.$proofOfDue_file;
				}

			    $status=BusinessDueFees::where('id', $due_id)
										->where('added_by', Auth::id())
										->update(['proof_of_due'=>$proofOfDue]);
			}	
		}
		return redirect()->back()->withMessage('Success: Proof of due is uploaded');
	}

	

	public function proofduestore(Request $request) {

		if(isset($request->customer_id)){
			$business_id= $request->customer_id;
		}else{
			$business_id= $request->customer_ids;
		}
		
		$files = $request->file('proof_of_due');
		if(empty($business_id) || empty($files)){
			return redirect()->back()->withMessage('error: something went wrong');
		}
		
		if ($request->hasFile('proof_of_due')) {
			foreach ($files as  $file) {
				$file_get_contents = file_get_contents($file->getRealPath());
				$proofOfDue_file = Storage::disk('public')->put('business/proof_of_due', $file);
				$proofOfDue=str_replace("business/proof_of_due/","",$proofOfDue_file);
			}
		}   

		$data=Businesses::where('id', $business_id)->where('added_by', Auth::id())->get(); 

		foreach($data as $rec)
			{
				if(!empty($rec['proof_of_due']))
				{
					$proofOfDue=$rec['proof_of_due'].",".$proofOfDue;
				}else{
					$proofOfDue=$proofOfDue_file;
				}

				$status=Businesses::where('id', $business_id)->where('added_by', Auth::id())->update([
					'proof_of_due'=>$proofOfDue
				]);

				
			}

		return redirect()->back()->withMessage('Success: Proof of due is uploaded');
	
	}

	


}
