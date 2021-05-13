<?php

namespace App\Imports;

use App\Students;
use App\StudentDueFees;
use App\StudentPaidFees;
use App\BulkDuePaymentUploadIssues;

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

class StudentsDuePaymentImport implements ToModel, WithHeadingRow, WithEvents
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
	$row['invoice_no'] = trim($row['invoice_no']);
	$row['payment_date_ddmmyyyy'] = trim($row['payment_date_ddmmyyyy']);
	$row['payment_amount'] = str_replace(',','',$row['payment_amount']);
	$row['payment_amount'] = trim($row['payment_amount']);
	$row['payment_note'] = trim($row['payment_note']);
	$row['customer_id'] = trim($row['customer_id']);
	

	if(empty($row['invoice_no']) && empty($row['payment_date_ddmmyyyy']) && empty($row['payment_amount']) && empty($row['customer_id'])){
		return null;
	}
	$currentDate = Carbon::now();
	
	++$this->count;
	$authId = Auth::id();
	$reasons = '';

	$rule = [
	   'invoice_no'=>'required|regex:/^[a-zA-Z0-9.\/\* (),#+-]+$/u',
	   'payment_date_ddmmyyyy' => 'required|date_multi_format:"d-m-Y","d/m/Y"',
	   'payment_amount' => 'required|numeric|gt:0|lte:100000000',
	   'payment_note'=>'nullable|string|max:300',
	   'customer_id'=>'numeric',
	];

	$ruleMessage = [
		'invoice_no.required'=>'The Invoice No. can not be empty.',
		'invoice_no.regex'=>'The Invoice No. must be valid format.',
		
		'payment_date_ddmmyyyy.required'=>'The Payment date can not be empty',
		'payment_date_ddmmyyyy.date_multi_format'=>'The Payment date must be a valid date.',

		'payment_amount.required'=>'The Payment amount can not be empty.',
		'payment_amount.numeric'=>'The Payment amount  must be a number.',
		'payment_amount.gt'=>'The Payment amount must be greater than :value.',
		'payment_amount.lte'=>'The Payment amount must be less than or equal 1,00,00,000',
		'payment_note.max'=>'The Payment note may not be greater than :max characters.',
		// 'customer_id.required'=>'The Customer Id can not be empty.',
		'customer_id.numeric'=>'The Customer Id  must be a number.',
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
	
	if(!$this->hasIssue){
    	$dueData = StudentDueFees::where('invoice_no','LIKE',$row['invoice_no']);
    	if($row['customer_id']!=''){
    		$dueData = $dueData->where('student_id',$row['customer_id']);

    	}
    	$dueData = $dueData->withCount([
				'paid AS totalPaid' => function ($query) use($authId){
            		$query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at')->where('added_by',$authId);
        		}
    		]);
    	$dueData = $dueData->where('added_by',$authId)->whereNull('deleted_at')->first();
    	if(!$dueData){
    		$this->atLeastIssue=true;
    		$this->hasIssue = true;
    		if($row['customer_id']!=''){
    		$reasons.='The Invoice no/Customer Id can not be matched with our database.<br>';
    	} else {
         $reasons.='The Invoice no can not be matched with our database.<br>';
    	}
    	}else{
	    	$balanceDue = $dueData->due_amount - $dueData->totalPaid;
	    	if($balanceDue<$row['payment_amount']){
	    		$this->atLeastIssue=true;
	    		$this->hasIssue = true;
	    		$reasons.='Payment Amount is exceeding due balance.<br>';
	    	}
	    }	
    }

    if($this->hasIssue){
    	$reasons = trim($reasons,'<br>');
    	BulkDuePaymentUploadIssues::create([
    		'unique_url_code'=>$this->uniqueUrlCode,
    		'added_by'=>$authId,
    		'issue'=>$reasons,
    		'invoice_no'=>$row['invoice_no'],
    		'payment_date'=>$row['payment_date_ddmmyyyy'],
    		'payment_amount'=>$row['payment_amount'],
    		'payment_note'=>$row['payment_note'],
    		'issue_type'=>'INDIVIDUAL',
    		'created_at'=>$currentDate,
    	]);

    }else{
		++$this->updated;
		$row['payment_date_ddmmyyyy'] = str_replace('-','/',$row['payment_date_ddmmyyyy']);
		$payment_date = Carbon::createFromFormat('d/m/Y', $row['payment_date_ddmmyyyy'])->toDateString();
		$paid = StudentPaidFees::create([
			'student_id'=>$dueData->student_id,
			'due_id'=>$dueData->id,
			'paid_date'=>$payment_date,
			'paid_amount'=>$row['payment_amount'],
			'paid_note'=>$row['payment_note'],
			'created_at'=>$currentDate,
			'external_student_id'=>$dueData->external_student_id,
			'added_by'=>$authId,
		]);
		return $paid;
	}
 	return null;//redirect()->back()->withError('Error: File is blank');
 }

 public function getRowCount()
 {
 	$this->skipped = $this->count - $this->updated;
 	return ['Total'=>$this->count,'Updated'=>$this->updated,'Skipped'=>$this->skipped];
 }
}
