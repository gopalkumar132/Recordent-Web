<?php

namespace App\Imports;

use App\Students;
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
use Illuminate\Support\Str;
use Validator;
use Carbon\carbon;
use DB;
use Auth;
use Session;
use General;

class StudentsUpdateProfileImport implements ToModel, WithHeadingRow, WithEvents
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
	$row['email'] = trim($row['email']);
	$row['aadhar_number'] = str_replace('-', '', $row['aadhar_number']);
	$row['aadhar_number'] = str_replace('_', '', $row['aadhar_number']);
	$row['aadhar_number'] = trim($row['aadhar_number']);
	

	if(empty($row['customer_id']) && empty($row['email']) && empty($row['aadhar_number'])){
		return null;
	}
	$currentDate = Carbon::now();
	
	++$this->count;
	$email_max_character= General::maxlength('email');
	$reasons = '';

	$rule = [
	   'customer_id'=>'required|numeric',
	   'email'=> 'nullable|max:'.$email_max_character.'|email',
	   'aadhar_number' => 'nullable|numeric|digits:6',
	];

	$ruleMessage = [
		'customer_id.required'=>'The Customer Id can not be empty.',
		'customer_id.numeric'=>'The Customer Id  must be a number.',
		
		'email.email' => 'The Email must be a valid email.',
		'email.max' => 'The Email may not be greater than :max characters.',

		'aadhar_number.numeric' => 'The Aadhar number must be a number.',
		'aadhar_number.digits' => 'The Aadhar number must be :digits digits.',
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
	$dueData = Students::where('id',$row['customer_id'])->where('added_by',$authId)->whereNull('deleted_at')->first();
	// dd($dueData);
	if(!$this->hasIssue){
    	
    	if(!$dueData){
    		$this->atLeastIssue=true;
    		$this->hasIssue = true;
    		$reasons .= 'The Customer Id. can not be matched with our database.<br>';
    	} else {
	       if(empty($row['email']) && empty($row['aadhar_number'])){
	            	$this->atLeastIssue = true;
					$this->hasIssue = true;
					$reasons .= 'Nothing to Update .<br>';
	      }

		  if(empty($row['email'])){
			$email= $dueData->email;
		  }
		  else {
			$email = $row['email'];
		  }
		  if(empty($row['aadhar_number'])){
			$aadhar_number= $dueData->aadhar_number;
		  } else {
			$aadhar_number = $row['aadhar_number'];
		  }
        }
     }	

    if($this->hasIssue){
    	$reasons = trim($reasons,'<br>');
    	IndividualBulkUploadIssues::create([
    		'unique_url_code'=>$this->uniqueUrlCode,
    		'added_by'=>$authId,
    		'issue'=>$reasons,
			'email'=>$row['email'],
			'aadhar_number' => $row['aadhar_number'],
    		'created_at'=>$currentDate,
    	]);

    } else {
    	++$this->updated;
    	$updateProfile = [
           	'email' => $email,
            'aadhar_number' => $aadhar_number,
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
