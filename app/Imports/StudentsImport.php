<?php

namespace App\Imports;

use App\Students;
use App\StudentDueFees;
use App\StudentPaidFees;
use App\IndividualBulkUploadIssues;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Str;
use Validator;
use Carbon\carbon;
use DB;
use Auth;
use Session;
use General;
use Log;
use App\User;
use Illuminate\Support\Facades\Mail as SendMail;
use CustomerHelper;

class StudentsImport implements ToModel, WithHeadingRow, WithEvents, SkipsOnError
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
		Log::debug('beforeImport IndividualBulkUpload');
		$worksheet = $event->reader->getActiveSheet();
		//Log::debug(print_r($worksheet, true));
		//dd($worksheet->getCollection());
		$highestRow = $worksheet->getHighestRow(); // e.g. 10
		// Log::debug(print_r($highestRow, true));
		if ($highestRow < 2) {
			Session::flash('message', 'Error: File is blank');
			$error = \Illuminate\Validation\ValidationException::withMessages([]);
			$failure = new Failure(1, 'rows', [0 => 'Not enough rows!']);
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
		// dd(self::$remainingCustomerCount, $count, $countBusiness, Auth::user()->user_pricing_plan->plan->free_customer_limit);
	}


	/**
	 * @param array $row
	 *
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */
	public function model(array $row)
	{	//dd($row);
		$rows_count = count(array_filter($row));
		
		if($rows_count > 0){

			$records = \App\Students::where('person_name', 'LIKE', General::encrypt(strtolower($row['person_name'])))
					->where('contact_phone', '=', General::encrypt($row['contact_phone_number']))
					->whereNull('deleted_at');

			if (!Auth::user()->hasRole('admin')) {
				$records = $records->where('added_by', Auth::id());
			}
			
			$records = $records->first();
			$customId = array_key_exists("custom_id",$row) ? $row['custom_id'] : NULL;
			//Log::debug(print_r($row,true));
			if (self::$remainingCustomerCount > 0 || !empty($records)) {
				
				
				if (!preg_match("/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/",$row['duedate_ddmmyyyy'])) {

					if($row['duedate_ddmmyyyy']!="") {
						try {$row['duedate_ddmmyyyy'] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['duedate_ddmmyyyy']))->format('d/m/Y'); }
						catch (\Exception $e) {
							$errorMsg = date('Y-m-d H:i:s')."----individual----".$row['dueamount']."-----".$row['person_name']."------".$e->getMessage()."<br/>";
							error_log($errorMsg,3,storage_path().'/logs/bulkuploads.log');
						}
					}
				}
				
				$this->hasIssue = false;
				$row['person_name'] = trim($row['person_name']);
				$row['contact_phone_number'] = trim($row['contact_phone_number']);

				$row['aadhar_number'] = str_replace('-', '', $row['aadhar_number']);
				$row['aadhar_number'] = str_replace('_', '', $row['aadhar_number']);
				$row['aadhar_number'] = trim($row['aadhar_number']);

				$row['dob_ddmmyyyy'] = trim($row['dob_ddmmyyyy']);
				$row['father_name'] = trim($row['father_name']);
				$row['mother_name'] = trim($row['mother_name']);
				$row['duedate_ddmmyyyy'] = trim($row['duedate_ddmmyyyy']);
				$row['dueamount'] = str_replace(',', '', $row['dueamount']);
				$row['dueamount'] = trim($row['dueamount']);
				$row['duenote'] = trim($row['duenote']);
				$row['email'] = trim($row['email']);
				$row['grace_period'] = trim($row['grace_period']);
				$row['invoice_no'] = trim($row['invoice_no']);
				$row['custom_id'] = $customId;

				// if (!preg_match("/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/",$row['duedate_ddmmyyyy'])) {
				// 		if($row['duedate_ddmmyyyy']!="") {
				// 	$row['duedate_ddmmyyyy'] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['duedate_ddmmyyyy']))->format('d/m/Y'); }
				// 	}

				if (empty($row['person_name']) && empty($row['father_name']) && empty($row['mother_name']) && empty($row['contact_phone_number']) && empty($row['dob_ddmmyyyy']) && empty($row['duedate_ddmmyyyy']) && empty($row['dueamount']) && empty($row['duenote']) && empty($row['email']) && empty($row['grace_period'])) {
					return null;
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
				++$this->count;
				
				$authId = Session::get('member_id');
				if (!isset($authId)) {
					$authId = Auth::id();
				}

				$name_max_character= General::maxlength('name');
		    	$email_max_character= General::maxlength('email'); 

				$reasons = '';

				$rule = [
					'person_name' => 'required|max:'.$name_max_character.'|regex:/^[a-zA-Z. \/\&]+$/u',
					'contact_phone_number' => 'required|numeric|digits:10|starts_with:6,7,8,9',
					'aadhar_number' => 'nullable|numeric|digits:6',
					'dob_ddmmyyyy' => 'nullable|date_multi_format:"d-m-Y","d/m/Y"|custom_before_date_or_equal:' . Carbon::now()->format('d/m/Y') . '|custom_date_after_or_equal:' . $dob_valid_from,
					'father_name' => 'nullable|max:'.$name_max_character.'|regex:/^[a-zA-Z. \/\&]+$/u',
					'mother_name' => 'nullable|max:'.$name_max_character.'|regex:/^[a-zA-Z. \/\&]+$/u',
					'duedate_ddmmyyyy' => 'required|date_multi_format:"d-m-Y","d/m/Y"',
					'dueamount' => 'required|numeric|gt:0|min:1|lte:100000000',
					'due_note' => 'nullable|string|max:300',
					'email' => 'nullable|max:'.$email_max_character.'|email',
					'grace_period' => 'nullable|integer',
					
					'invoice_no' => 'nullable|max:40|regex:/^[a-zA-Z0-9.\/\* (),#+-@]+$/u',
					'custom_id' => 'nullable|max:50|regex:/^[a-zA-Z0-9.\/\* (),:;#+-]+$/u'
				];

				if ($due_date_old_in_year) {
					$rule['duedate_ddmmyyyy'] = $rule['duedate_ddmmyyyy'] . '|custom_date_after_or_equal:' . $due_date_old_in_year;
				}

				if ($due_date_max_future_in_year) {
					$rule['duedate_ddmmyyyy'] = $rule['duedate_ddmmyyyy'] . '|custom_before_date_or_equal:' . $due_date_max_future_in_year;
				}

				$ruleMessage = [
					'person_name.required' => 'The Person name can not be empty.',
					'person_name.max' => 'The Person name may not be greater than :max characters.',
					'person_name.regex' => 'The Person name may only contain letters and space.',

					'contact_phone_number.required' => 'The Contact Phone number can not be empty.',
					'contact_phone_number.digits' => 'The Contact Phone number must be :digits digits.',
					'contact_phone_number.numeric' => 'The Contact Phone number must be a number.',

					'aadhar_number.numeric' => 'The Aadhar number must be a number.',
					'aadhar_number.digits' => 'The Aadhar number must be :digits digits.',

					'dob_ddmmyyyy.date_multi_format' => 'The Dob must be a valid date.',
					'dob_ddmmyyyy.before_or_equal' => 'The Dob must be a date before or equal to :date.',

					'father_name.regex' => 'The Father name may only contain letters and space.',
					'father_name.max' => 'The Father name may not be greater than :max characters.',

					'mother_name.regex' => 'The Mother name may only contain letters and space.',
					'mother_name.max' => 'The Mother name may not be greater than :max characters.',

					'duedate_ddmmyyyy.required' => 'The Due date can not be empty',
					'duedate_ddmmyyyy.date_multi_format' => 'The Due date must be a valid date.',

					'dueamount.required' => 'The Due amount can not be empty.',
					'dueamount.numeric' => 'The Due amount  must be a number.',
					'dueamount.gt' => 'The Due amount must be greater than :value.',
					'dueamount.min' => 'Due amount can not be less than 500.',
					'dueamount.lte' => 'The Due amount must be less than or equal 1,00,00,000',
					'due_note.max' => 'The Due note may not be greater than :max characters.',
					'email.email' => 'The Email must be a valid email.',
					'email.max' => 'The Email may not be greater than :max characters.',
					'grace_period.integer' => 'The Grace period must be a number.',
					'invoice_no.regex' => 'The Invoice contained unallowed characters.',
					'invoice_no.max' => 'The Invoice may not be greater than :max characters.',
					'custom_id.regex'=>'The Custom Id contained unallowed characters.',
					'custom_id.max'=>'The Custom Id may not be greater than :max characters.'
				];

				$ruleMessage['dob_ddmmyyyy.custom_before_date_or_equal'] = 'The Dob must be a date before or equal to ' . Carbon::now()->format('d/m/Y');
				$ruleMessage['dob_ddmmyyyy.custom_date_after_or_equal'] = 'The Dob must be a date after or equal to ' . $dob_valid_from;

				if ($due_date_old_in_year) {
					$ruleMessage['duedate_ddmmyyyy.custom_date_after_or_equal'] = 'The Due date must be a date after or equal to ' . $due_date_old_in_year;
				}

				if ($due_date_max_future_in_year) {
					$ruleMessage['duedate_ddmmyyyy.custom_before_date_or_equal'] = 'The Due date must be a date before or equal to ' . $due_date_max_future_in_year;
				}

				$validator = Validator::make($row, $rule, $ruleMessage);

				if ($validator->fails()) {
					$this->atLeastIssue = true;
					$this->hasIssue = true;
					
					foreach ($validator->messages()->all() as $error) {
						$reasons .= $error . '<br>';
					}

					$reasons = trim($reasons, '<br>');
					IndividualBulkUploadIssues::create([
						'unique_url_code' => $this->uniqueUrlCode,
						'added_by' => $authId,
						'issue' => $reasons,
						'aadhar_number' => $row['aadhar_number'],
						'contact_phone' => $row['contact_phone_number'],
						'person_name' => $row['person_name'],
						'dob' => $row['dob_ddmmyyyy'],
						'father_name' => $row['father_name'],
						'mother_name' => $row['mother_name'],
						'due_date' => $row['duedate_ddmmyyyy'],
						'due_amount' => $row['dueamount'],
						'due_note' => $row['duenote'],
						'email' => $row['email'],
						'grace_period' => $row['grace_period'],
						'created_at' => Carbon::now(),
						'invoice_no' => $row['invoice_no']
					]);
				}

				if (!$this->hasIssue) {
					++$this->updated;

					$row['duedate_ddmmyyyy'] = str_replace('-', '/', $row['duedate_ddmmyyyy']);
					$row['dob_ddmmyyyy'] = str_replace('-', '/', $row['dob_ddmmyyyy']);
					
					$dob = '';
					if (!empty($row['dob_ddmmyyyy'])) {
						$newDob = Carbon::createFromFormat('d/m/Y', trim($row['dob_ddmmyyyy']));
						$dob = $newDob->format('Y-m-d');
					}

					$students = Students::where('person_name', 'LIKE', General::encrypt(strtolower($row['person_name'])))
						->where('contact_phone', '=', General::encrypt($row['contact_phone_number']))
						->whereNull('deleted_at')->first();
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
							$studentDue = StudentDueFees::create([
								'student_id' => $studentId,
								'due_date' => $dueDate,
								'due_amount' => $row['dueamount'],
								'due_note' => $row['duenote'],
								'created_at' => Carbon::now(),
								'added_by' => $authId,
								'invoice_no' => $row['invoice_no'],
								'external_student_id' => $customId,
								'grace_period' => $gracePeriod,
								'collection_date' => $collectionDate,
								'balance_due'=> $row['dueamount']
							]);
						}
						
						// $studentId = DB::getPdo()->lastInsertId();
						$individual_response=General::generate_magic_url_function($row,"individual",$studentId ,'indivExcelBulk');
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
						
						$studentDue = StudentDueFees::create([
							'student_id' => $studentId,
							'due_date' => $dueDate,
							'due_amount' => $row['dueamount'],
							'due_note' => $row['duenote'],
							'created_at' => Carbon::now(),
							'added_by' => $authId,
							'invoice_no' => $row['invoice_no'],
							'external_student_id' => $customId,
							'grace_period' => $gracePeriod,
							'collection_date' => $collectionDate,
							'balance_due'=> $row['dueamount']
						]);

						/**This logic is commentted because of overide of due amount*/
						/*
						$studentDue = StudentDueFees::where('student_id','=',$studentId)->where('due_date','=',$dueDate)->where('added_by',$authId)->whereNull('deleted_at')->first();
						
						if(empty($studentDue)){
							$studentDue = StudentDueFees::create([
								'student_id' => $studentId,
								'due_date' => $dueDate,
								'due_amount'=> $row['dueamount'],
								'due_note'=> $row['duenote'],
								'created_at' => Carbon::now(),
								'added_by' => $authId,
								'invoice_no'=>$row['invoice_no'],
							]);
						}else{
							$studentDue->update([
								'student_id' => $studentId,
								'due_date' => $dueDate,
								'due_amount'=> $row['dueamount'],
								'due_note'=> $row['duenote'],
								'updated_at' => Carbon::now(),
								'invoice_no'=>$row['invoice_no'],
							]);
						}*/
					}

					CustomerHelper::insertIntoMemberCustomerIdMappingTable($authId, $studentId, 1);
					
					$students->email = $row['email'];
					$students->save();
					/*$skipEmailNotification=false;
					if (General::Checkmemberid_skip_email_notifications_for_dues()) {
						$skipEmailNotification = true;
					}
					if($skipEmailNotification == false){

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
					}*/
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

					self::$remainingCustomerCount--;

					return $students;
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