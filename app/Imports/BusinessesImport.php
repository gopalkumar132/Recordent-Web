<?php

namespace App\Imports;

use App\Businesses;
use App\BusinessDueFees;
use App\BusinessPaidFees;
use App\BusinessBulkUploadIssues;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use App\Sector;
use App\Country;
use App\State;
use App\City;
use Carbon\carbon;
use Validator;
use DB;
use Auth;
use Session;
use General;
use Log;
use App\User;
use App\UserType;
use Illuminate\Support\Facades\Mail as SendMail;
use CustomerHelper;

class BusinessesImport implements ToModel, WithHeadingRow, WithEvents, SkipsOnError
{
	use Importable, RegistersEventListeners, SkipsErrors;
	public $count = 0;
	public $updated = 0;
	public $skiped = 0;
	public $hasIssue = false;
	public $atLeastIssue = false;
	public $uniqueUrlCode = '';
	public static $remainingCustomerCount = 0;
	public $remainingRecords = [];

	/**
	 * @return array
	 */
	public static function beforeImport(BeforeImport $event)
	{

		//dd(Auth::user()->user_type);
		$worksheet = $event->reader->getActiveSheet();
		$highestRow = $worksheet->getHighestRow(); // e.g. 10

		if ($highestRow < 2) {
			Session::flash('message', 'Error: File is blank');

			$error = \Illuminate\Validation\ValidationException::withMessages([]);
			$failure = new Failure(1, 'rows', [0 => 'Now enough rows!']);
			$failures = [0 => $failure];
			throw new ValidationException($error, $failures);
		}

		if (Session::get('member_id')) {
			$authId = Session::get('member_id');
		} else {
			$authId = Auth::id();
		}

		$remainingCustomer = General::getFreeCustomersDuesLimit($authId);

		self::$remainingCustomerCount = $remainingCustomer;
	}

	/**
	 * @param array $row
	 *
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */
	public function model(array $row)
	{
		$rows_count = count(array_filter($row));
		if($rows_count > 0){
			$arr_name= array_keys($row);
			$col_name = $arr_name[2];

			if($col_name == 'unique_identification_number_gstin_business_pan'){
				$unique_identification_number = 'unique_identification_number_gstin_business_pan';
			} else {
				$unique_identification_number = 'unique_identification_number';
			}
			$customId = array_key_exists("custom_id",$row) ? $row['custom_id'] : NULL;
			$businessesTemp = Businesses::where('unique_identification_number', '=', General::encrypt(strtoupper($row[$unique_identification_number])))->whereNull('deleted_at')->first();

			$isAlreadyExistingCustomerr="No";
			if($businessesTemp)
			{
				$isAlreadyExistingCustomerr="Yes";
			}

			if (self::$remainingCustomerCount > 0 || !empty($businessesTemp)) {

				ini_set('max_execution_time', 0);
				$this->hasIssue = false;
				foreach ($row as $key => &$value) {
					if (!empty($key)) {
						$value = trim($value);
					}
				}

				if (!preg_match("/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/",$row['duedate_ddmmyyyy'])) {

					if($row['duedate_ddmmyyyy']!="") {
						try {$row['duedate_ddmmyyyy'] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['duedate_ddmmyyyy']))->format('d/m/Y'); }
						catch (\Exception $e) {
							$errorMsg = date('Y-m-d H:i:s')."----business----".$row['dueamount']."-----".$row['business_name']."------".$e->getMessage();
							error_log($errorMsg,3,storage_path().'/logs/bulkuploads.log');
						}
					}
				}

				if (
					empty($row['business_name']) &&
					//empty($row['sector_name']) &&
					empty($row[$unique_identification_number]) &&
					empty($row['concerned_person_name']) &&
					empty($row['concerned_person_designation']) &&
					empty($row['concerned_person_phone']) &&
					empty($row['state']) &&
					empty($row['city']) &&
					empty($row['duedate_ddmmyyyy']) &&
					empty($row['dueamount']) &&
					empty($row['email']) &&
					empty($row['grace_period']) &&
					empty($row['business_type'])
				) {
					return null;
				}

				//configuration
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

				$row['dueamount'] = str_replace(',', '', $row['dueamount']);
				++$this->count;

				$authId = Session::get('member_id');
				if (!isset($authId)) {
					$authId = Auth::id();

			    }

			    $name_max_character= General::maxlength('name');
		        $email_max_character= General::maxlength('email');
		        $reasons = '';

				if($isAlreadyExistingCustomerr != "Yes"){


		        $rule= [
					'business_name' => 'required|max:'.$name_max_character.'|min:'.$company_name_min_character,
					'sector_name' => 'nullable|regex:/^[a-zA-Z&\/\- ]+$/u|max:50',
					'unique_identification_number' => 'alpha_num|max:15',
					'concerned_person_name' => 'nullable|regex:/^[a-zA-Z. \/\&]+$/u|max:'.$name_max_character,
					'concerned_person_designation' => 'nullable|regex:/^[\pL\s\-.]+$/u|max:50',
					'concerned_person_phone' => 'numeric|digits:10,starts_with:6,7,8,9',
					'concerned_person_alternate_phone' => 'nullable|numeric|digits:10,starts_with:6,7,8,9',
					'state' => 'nullable|regex:/^[\pL\s]+$/u|max:50',
					'city' => 'nullable|regex:/^[\pL\s]+$/u|max:50',
					'pin_code'=> 'nullable|digits:6',
					'address'=> 'nullable|string',
					'duedate_ddmmyyyy' => 'required|date_multi_format:"d-m-Y","d/m/Y"',

					'dueamount' => 'required|numeric|gt:0|min:1|lte:1000000000',
					'email'=> 'nullable|max:'.$email_max_character.'|email',
					'grace_period'=> 'nullable|integer',

					'invoice_no' => 'nullable|max:30|regex:/^[a-zA-Z0-9.\/\* (),#+-@]+$/u',
					'custom_id' => 'nullable|max:50|regex:/^[a-zA-Z0-9.\/\* (),:;#+-]+$/u',
					'business_type' => 'nullable|regex:/^[a-zA-Z&\/\- ]+$/u|max:50',
				];

				if ($due_date_old_in_year) {
					$rule['duedate_ddmmyyyy'] = $rule['duedate_ddmmyyyy'] . '|custom_date_after_or_equal:' . $due_date_old_in_year;
				}

				if ($due_date_max_future_in_year) {
					$rule['duedate_ddmmyyyy'] = $rule['duedate_ddmmyyyy'] . '|custom_before_date_or_equal:' . $due_date_max_future_in_year;
				}

				$ruleMessage = [
					'business_name.required' => 'The Business name can not be empty.',
					// 'business_name.string' => 'The Business name must be a string.',
					'business_name.max' => 'The Business name may not be greater than :max characters.',

					'sector_name.required' => 'The Sector name can not be empty.',
					'sector_name.regex' => 'The Sector name may only contain letters and space.',
					'sector_name.max' => 'The Sector name may not be greater than :max characters.',

					'unique_identification_number.required' => 'The Unique identification number can not be empty.',
					'unique_identification_number.string' => 'The unique identification number must be a string.',
					'unique_identification_number.max' => 'The Unique identification number may not be greater than :max characters.',

					// 'concerned_person_name.required' => 'The Concerned person name can not be empty.',
					'concerned_person_name.regex' => 'The Concerned person name may only contain letters and space.',
					'concerned_person_name.max' => 'The Concerned person name may not be greater than :max characters.',

					// 'concerned_person_designation.required' => 'The Concerned person designation can not be empty.',
					'concerned_person_designation.regex' => 'The Concerned person designation may only contain letters, dash and space.',
					'concerned_person_designation.max' => 'The Concerned person designation may not be greater than :max characters.',

					'concerned_person_phone.required' => 'The Concerned person phone can not be empty.',
					'concerned_person_phone.digits' => 'The Concerned person phone must be :digits digits.',
					'concerned_person_phone.numeric' => 'The Concerned person phone must be a number.',

					'concerned_person_alternate_phone.digits' => 'The Concerned person alternate phone must be :digits digits.',
					'concerned_person_alternate_phone.numeric' => 'The Concerned person alternate phone must be a number.',

					'state.required' => 'The State can not be empty.',
					'state.regex' => 'The State name may only contain letters and space.',
					'state.max' => 'The State name may not be greater than :max characters.',

					'city.required' => 'The City can not be empty.',
					'city.regex' => 'The City name may only contain letters and space.',
					'city.max' => 'The City name may not be greater than :max characters.',

					'pin_code.string' => 'The Pincode must be a string.',
					'pin_code.alpha_num' => 'The Pincode may only contain letters and numbers.',
					'pin_code.max' => 'The Pincode may not be greater than :max characters.',

					'address.string' => 'The Address must be a string.',
					'duedate_ddmmyyyy.required' => 'The Due date can not be empty',
					'duedate_ddmmyyyy.date_multi_format' => 'The Due date must be a valid date.',

					'dueamount.required' => 'The Due amount can not be empty.',
					'dueamount.numeric' => 'The Due amount  must be a number.',
					'dueamount.gt' => 'The Due amount must be greater than :value.',
					'dueamount.min' => 'Due amount can not be less than 500.',
					'dueamount.lte' => 'The Due amount must be less than or equal 1,00,00,00,000',
					'email.email' => 'The Email must be a valid email.',
					'email.max' => 'The Email may not be greater than :max characters.',
					'grace_period.integer' => 'The Grace period must be a number.',
					'invoice_no.regex' => 'The Invoice contained unallowed characters.',
					'invoice_no.max' => 'The Invoice may not be greater than :max characters.',
					'custom_id.regex'=>'The Custom Id contained unallowed characters.',
					'custom_id.max'=>'The Custom Id may not be greater than :max characters.',
					'business_type.required' => 'The Business type can not be empty.',
					'business_type.regex' => 'The Business type may only contain letters and space.',
					'business_type.max' => 'The Business type may not be greater than :max characters.'
				];

				if ($due_date_old_in_year) {
					$ruleMessage['duedate_ddmmyyyy.custom_date_after_or_equal'] = 'The Due date must be a date after or equal to ' . $due_date_old_in_year;
				}

				if ($due_date_max_future_in_year) {
					$ruleMessage['duedate_ddmmyyyy.custom_before_date_or_equal'] = 'The Due date must be a date before or equal to ' . $due_date_max_future_in_year;
				}
			}else{

		        $rule= [
					'duedate_ddmmyyyy' => 'required|date_multi_format:"d-m-Y","d/m/Y"',

					'dueamount' => 'required|numeric|gt:0|min:1|lte:1000000000',
				];

				if ($due_date_old_in_year) {
					$rule['duedate_ddmmyyyy'] = $rule['duedate_ddmmyyyy'] . '|custom_date_after_or_equal:' . $due_date_old_in_year;
				}

				if ($due_date_max_future_in_year) {
					$rule['duedate_ddmmyyyy'] = $rule['duedate_ddmmyyyy'] . '|custom_before_date_or_equal:' . $due_date_max_future_in_year;
				}

				$ruleMessage = [
					'duedate_ddmmyyyy.required' => 'The Due date can not be empty',
					'duedate_ddmmyyyy.date_multi_format' => 'The Due date must be a valid date.',

					'dueamount.required' => 'The Due amount can not be empty.',
					'dueamount.numeric' => 'The Due amount  must be a number.',
					'dueamount.gt' => 'The Due amount must be greater than :value.',
					'dueamount.min' => 'Due amount can not be less than 500.',
					'dueamount.lte' => 'The Due amount must be less than or equal 1,00,00,00,000',

				];

				if ($due_date_old_in_year) {
					$ruleMessage['duedate_ddmmyyyy.custom_date_after_or_equal'] = 'The Due date must be a date after or equal to ' . $due_date_old_in_year;
				}

				if ($due_date_max_future_in_year) {
					$ruleMessage['duedate_ddmmyyyy.custom_before_date_or_equal'] = 'The Due date must be a date before or equal to ' . $due_date_max_future_in_year;
				}


			}
				$validator = Validator::make($row, $rule, $ruleMessage);

				if ($validator->fails()) {
					$this->atLeastIssue = true;
					$this->hasIssue = true;
					foreach ($validator->messages()->all() as $error) {
						$reasons .= $error . '<br>';
					}
				}

				if (empty($row['concerned_person_name'])) {
					$row['concerned_person_name']=NULL;
				}
				if(empty($row['concerned_person_designation']))
				{
					$row['concerned_person_designation']=NULL;
				}

				if (!empty($row['sector_name'])) {
					$sector = Sector::where('name', '=', $row['sector_name'])->first();
					if ($sector) {
						 $sectorId = $sector->id;
					} else {
						$this->atLeastIssue = true;
						$this->hasIssue = true;
						$reasons .= 'The Sector name can not be matched with our database.<br>';
					}
				}
				else {
					$sectorId=null;
				}
				$userType = null;
				if (!empty($row['business_type'])) {
					$user_type = UserType::where('name', '=', $row['business_type'])->where('status','1')->first();
					if ($user_type) {
					 $userType = $user_type->id;
					} else {
						$this->atLeastIssue = true;
						$this->hasIssue = true;
						$reasons .= 'The Business type can not be matched with our database.<br>';
					}
				}

				$stateId = '';
				if (!empty($row['state'])) {
					$state = State::where('name', '=', $row['state'])->first();
					if ($state) {
						$stateId = $state->id;
					} else {
						$this->atLeastIssue = true;
						$this->hasIssue = true;
						$reasons .= 'The State can not be matched with our database.<br>';
					}
				}

				$cityId = '';
				if (!empty($row['city'])) {
					if (!empty($stateId)) {
						$city = City::where('name', '=', $row['city'])->where('state_id', $stateId)->first();
						if ($city) {
							$cityId = $city->id;
						} else {
							$this->atLeastIssue = true;
							$this->hasIssue = true;
							$reasons .= 'The City can not be matched with state with our database.<br>';
						}
					} else {
						$this->atLeastIssue = true;
						$this->hasIssue = true;
						$reasons .= 'Due to state, The City can not be matched with our database.<br>';
					}
				}

				if ($this->hasIssue) {
					$reasons = trim($reasons, '<br>');
					BusinessBulkUploadIssues::create([
						'unique_url_code' => $this->uniqueUrlCode,
						'added_by' => $authId,
						'issue' => $reasons,
						'company_name' => $row['business_name'],
						'sector_name' => $row['sector_name'],
						'unique_identification_number' => $row[$unique_identification_number],
						'concerned_person_name' => $row['concerned_person_name'],
						'concerned_person_designation' => $row['concerned_person_designation'],
						'concerned_person_phone' => $row['concerned_person_phone'],
						'concerned_person_alternate_phone' => $row['concerned_person_alternate_phone'],
						'state' => $row['state'],
						'city' => $row['city'],
						'pincode' => $row['pin_code'],
						'address' => $row['address'],
						'due_date' => $row['duedate_ddmmyyyy'],
						'due_amount' => $row['dueamount'],
						'email' => $row['email'],
						'grace_period' => $row['grace_period'],
						'business_type' => $row['business_type'],
						'created_at' => Carbon::now(),
						'invoice_no' => $row['invoice_no']
					]);
				} else {

					++$this->updated;
					$row['duedate_ddmmyyyy'] = str_replace('-', '/', $row['duedate_ddmmyyyy']);
					$businesses = Businesses::where('unique_identification_number', '=', General::encrypt(strtoupper($row[$unique_identification_number])))
						->where('concerned_person_phone','=',General::encrypt(strtolower($row['concerned_person_phone'])))
						->whereNull('deleted_at')
						->first();
					if (empty($businesses)) {
						$businesses = Businesses::create([
							'company_name' => $row['business_name'],
							'sector_id' => $sectorId,
							'unique_identification_number' => $row[$unique_identification_number],
							'concerned_person_name' => $row['concerned_person_name'],
							'concerned_person_designation' => $row['concerned_person_designation'],
							'concerned_person_phone' => $row['concerned_person_phone'],
							'concerned_person_alternate_phone' => $row['concerned_person_alternate_phone'],
							'state_id' => $stateId,
							'city_id' => $cityId,
							'pincode' => $row['pin_code'],
							'address' => $row['address'],
							'user_type' => $userType,
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

							$businessDue = BusinessDueFees::create([
								'business_id' => $businessId,
								'due_date' => $dueDate,
								'due_amount' => $row['dueamount'],
								//'due_note'=> $row['duenote'],
								'created_at' => Carbon::now(),
								'added_by' => $authId,
								'invoice_no' => $row['invoice_no'],
								'external_business_id' => $customId,
								'grace_period' => $gracePeriod,
								'collection_date' => $collectionDate,
								'balance_due'=> $row['dueamount']
							]);
						}

					// $businessId = DB::getPdo()->lastInsertId();
					$individual_response=General::generate_magic_url_function($row,"business",$businessId ,'BusinessExcelBulk');

					} else {
						$businessId = $businesses->id;
						$valuesForBusiness = [
							'company_name' => $row['business_name'],
							'sector_id' => $sectorId,
							'unique_identification_number' => $row[$unique_identification_number],
							'concerned_person_name' => $row['concerned_person_name'],
							'concerned_person_designation' => $row['concerned_person_designation'],
							'concerned_person_phone' => $row['concerned_person_phone'],
							'concerned_person_alternate_phone' => $row['concerned_person_alternate_phone'],
							'state_id' => $stateId,
							'city_id' => $cityId,
							'pincode' => $row['pin_code'],
							'address' => $row['address'],
							'user_type' => $userType,
							'updated_at' => Carbon::now()
						];

						$businesses->update($valuesForBusiness);

						$dueDate = Carbon::createFromFormat('d/m/Y', $row['duedate_ddmmyyyy']);
						$dueDate  = $dueDate->format('Y-m-d');

						if ($row['grace_period'] == 0 || $row['grace_period'] == "" || $row['grace_period']<=1) {
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
							'external_business_id' => $customId,
							'grace_period' => $gracePeriod,
							'collection_date' => $collectionDate,
							'balance_due'=> $row['dueamount']
						]);

						/**This logic is commentted because of overide of due amount*/
						/*
				    	$businessDue = BusinessDueFees::where('business_id','=',$businessId)->where('due_date','=',$dueDate)->where('added_by',$authId)->whereNull('deleted_at')->first();

				    	if(empty($businessDue)){
				    		$businessDue = BusinessDueFees::create([
				    			'business_id' => $businessId,
				    			'due_date' => $dueDate,
				    			'due_amount'=> $row['dueamount'],
													//'due_note'=> $row['duenote'],
				    			'created_at' => Carbon::now(),
				    			'added_by' => $authId,
								'invoice_no'=>$row['invoice_no'],
				    		]);
				    	}else{
				    		$businessDue->update([
				    			'business_id' => $businessId,
				    			'due_date' => $dueDate,
				    			'due_amount'=> $row['dueamount'],
													//'due_note'=> $row['duenote'],
				    			'updated_at' => Carbon::now(),
								'invoice_no'=>$row['invoice_no'],
				    		]);
				    	}*/
					}

					CustomerHelper::insertIntoMemberCustomerIdMappingTable($authId, $businessId, 2);

			 $businesses->email = $row['email'];
			 $businesses->save();
			/* $skipEmailNotification=false;
			 if (General::Checkmemberid_skip_email_notifications_for_dues()) {
				$skipEmailNotification = true;
			}
			if($skipEmailNotification == false){
			$name = $businesses->concerned_person_name;
	        $email = $businesses->email;
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
         }*/
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
					self::$remainingCustomerCount--;
					return $businesses;
				}

				return null; //redirect()->back()->withError('Error: File is blank');
			} else {
				$this->remainingRecords[] = $row;
				return null;
			}
		}
	}

	public function getRowCount()
	{
		$this->skipped = $this->count - $this->updated;
		return ['Total' => $this->count, 'Updated' => $this->updated, 'Skipped' => $this->skipped];
	}

	public function getRemainingRecords()
	{
		return $this->remainingRecords;
	}
}
