<?php

namespace App\Imports;

use App\Businesses;
use App\BusinessBulkUploadIssues;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Str;
use Validator;
use Carbon\carbon;
use DB;
use Auth;
use Session;
use General;
use App\State;
use App\City;

class BusinessUpdateProfileImport implements ToModel, WithHeadingRow, WithEvents
{
	use Importable, RegistersEventListeners;
	public $count = 0;
	public $updated = 0;
	public $skiped = 0;
	public $hasIssue = false;
	public $atLeastIssue = false;
	public $uniqueUrlCode = '';

/**
 * @return array
 */
public static function beforeImport(BeforeImport $event)
{

	$worksheet = $event->reader->getActiveSheet();
	//dd($worksheet->getCollection());
    $highestRow = $worksheet->getHighestRow(); // e.g. 10

    if ($highestRow < 2) {
    	Session::flash('message','Error: File is blank');
    	$error = \Illuminate\Validation\ValidationException::withMessages([]);
    	$failure = new Failure(1, 'rows', [0 => 'Not enough rows!']);
    	$failures = [0 => $failure];
    	throw new ValidationException($error, $failures);
    }
    
}


/**
* @param array $row
*
* @return \Illuminate\Database\Eloquent\Model|null
*/
public function model(array $row)
{   
	$this->hasIssue = false;
	$row['customer_id'] = trim($row['customer_id']);
	$row['state'] = str_replace(',','',$row['state']);
	$row['city'] = trim($row['city']);
	$row['email'] = trim($row['email']);
	$row['custom_id'] = trim($row['custom_id']);
	

	if(empty($row['customer_id']) && empty($row['state']) && empty($row['city']) && empty($row['email']) && empty($row['custom_id'])){
		return null;
	}
	$currentDate = Carbon::now();
	
	++$this->count;
	$email_max_character= General::maxlength('email');
	$reasons = '';

	$rule = [
	   'customer_id'=>'required|numeric',
	   'state' => 'regex:/^[\pL\s]+$/u|max:50',
	   'city' => 'regex:/^[\pL\s]+$/u|max:50',
	   'email'=> 'nullable|max:'.$email_max_character.'|email',
	   'custom_id' => 'nullable|max:50|regex:/^[a-zA-Z0-9.\/\* (),#+-:;]+$/u'
	];

	$ruleMessage = [
		'customer_id.required'=>'The Customer Id can not be empty.',
		'customer_id.numeric'=>'The Customer Id  must be a number.',
		
		'state.regex' => 'The State name may only contain letters and space.',
		'state.max' => 'The State name may not be greater than :max characters.',

		'city.regex' => 'The City name may only contain letters and space.',
		'city.max' => 'The City name may not be greater than :max characters.',

		'email.email' => 'The Email must be a valid email.',
		'email.max' => 'The Email may not be greater than :max characters.',

		'custom_id.max' => 'The Custom Id  may not be greater than :max characters.',
		'custom_id.regex' => 'The Custom Id contained unallowed characters.',
	];

	

	$validator = Validator::make($row, $rule,$ruleMessage);

	if ($validator->fails()) {
		//dd($validator->messages()->all());
    	$this->atLeastIssue=true;
    	$this->hasIssue = true;
    	foreach($validator->messages()->all() as $error){
    		$reasons.= $error.'<br>';
    	}
    }
    $authId = Session::get('member_id');
				if (!isset($authId)) {
					$authId = Auth::id();

			    }
	$dueData = Businesses::where('id',$row['customer_id'])->where('added_by',$authId)->whereNull('deleted_at')->first();
	if(!$this->hasIssue){
    	
    	if(!$dueData){
    		$this->atLeastIssue=true;
    		$this->hasIssue = true;
    		$reasons .= 'The Customer Id. can not be matched with our database.<br>';
    	} else { 
	       if(empty($row['state']) && empty($row['city']) && empty($row['email']) && empty($row['custom_id'])){
	            	$this->atLeastIssue = true;
					$this->hasIssue = true;
					$reasons .= 'Nothing to Update .<br>';
	       }

		   if(!empty($row['state'])) {
				$state = State::where('name', '=', $row['state'])->first();
				if ($state) {
					$stateId = $state->id;
				} else {
					$this->atLeastIssue = true;
					$this->hasIssue = true;
					$reasons .= 'The State can not be matched with our database.<br>';
				}
			}
			else {
				$stateId = $dueData->state_id;
			}

			$cityId = '';
			if(!empty($row['city'])) {
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
			} else {
				if (!empty($row['state'])) {
					$city = City::where('name', '=', $row['city'])->where('state_id', $stateId)->first();
					if ($city) {
						$cityId = $city->id;
					} else {
						$this->atLeastIssue = true;
						$this->hasIssue = true;
						$reasons .= 'The City can not be matched with state with our database.<br>';
					}
				} else {
					 $cityId = $dueData->city_id;
				}
			} 
			
		 if(empty($row['email'])){
			$email= $dueData->email;
		 }
		 else {
			$email = $row['email'];
		 }
     }	
  }

    if($this->hasIssue){
    	$reasons = trim($reasons,'<br>');
    	BusinessBulkUploadIssues::create([
    		'unique_url_code'=>$this->uniqueUrlCode,
    		'added_by'=>$authId,
    		'issue'=>$reasons,
    		 'state' => $row['state'],
			'city' => $row['city'],
			'email'=>$row['email'],
			'custom_business_id' =>$row['custom_id'],
    		'created_at'=>$currentDate,
    	]);

    } else {
    	++$this->updated;
    	$updateProfile = [
           	'email' => $email,
            'state_id' => $stateId,
			'city_id' => $cityId,
            'custom_business_id' =>$row['custom_id'],
            'updated_at'=> Carbon::now()
          ];  
          $dueData->update($updateProfile);
    }
	
	
 	return null;//redirect()->back()->withError('Error: File is blank');
 }

 public function getRowCount()
 {

 	$this->skipped = $this->count - $this->updated;
 	return ['Total'=>$this->count,'Updated'=>$this->updated,'Skipped'=>$this->skipped];
 }
}
