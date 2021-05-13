<?php

namespace App\Exports;

use App\Students;
use App\StudentDueFees;
use App\StudentPaidFees;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Auth;

class StudentsExport implements FromCollection, WithHeadings
{
	protected $fromdate;
	protected $todate;
	protected $dropDownType;
	protected $loginId;
	public $paymentOnlyDownload;
	public $fromExport;
	public $is_customerPayments;
      public function __construct($loginId,$paymentOnlyDownload="",$fromExport="studentExport",$fromdate,$todate,$dropDownType,$is_customerPayments="") {
       $this->loginId = $loginId;
	   $this->paymentOnlyDownload=$paymentOnlyDownload;
	   $this->fromExport=$fromExport;
	   $this->fromdate = $fromdate;
	   $this->todate =$todate;
	   $this->dropDownType =$dropDownType;
	   $this->is_customerPayments =$is_customerPayments;
 }
    public function headings():array
    {
        if(Auth::user()->role_id == 1 || Auth::user()->role_id == 14)
		{

			if( $this->paymentOnlyDownload =="1"){

				$header_culm_name="";
				if($this->is_customerPayments == "2")
				{
					$header_culm_name='Waived Off Reason';
				}
					return [
						'Member ID',
						'Customer ID - Individual',
						'Organization Name',
						'Aadhar Number',
						'Contact Phone Number',
						'Person Name',
						'DOB (DD/MM/YYYY)',
						'Father Name',
						'Mother Name',
						'Due ID',
						'Reported Date (DD/MM/YYYY)',
						'DueDate (DD/MM/YYYY)',
						'DueAmount',
						'PaidDate (DD/MM/YYYY)',
						'Payment Updated Date',
						'PaidAmount',
						'DueNote',
						'PaidNote',
						'Email',
						'Grace Period',
						'Custom ID',
						'Invoice Number',
						$header_culm_name
				   ];

			}else{
				return [
					'Member ID',
					'Customer ID - Individual',
					'Organization Name',
					'Aadhar Number',
					'Contact Phone Number',
					'Person Name',
					'DOB (DD/MM/YYYY)',
					'Father Name',
					'Mother Name',
					'Due ID',
					'Reported Date (DD/MM/YYYY)',
					'DueDate (DD/MM/YYYY)',
					'DueAmount',
					'Balance Due Amount',
					'DueNote',
					'Email',
					'Grace Period',
					'Custom ID',
					'Invoice Number',
					'Checkmy Report Link',
					'Proof of Due'
				   ];
			}

		}

			else {

				if( $this->paymentOnlyDownload =="1"){
					$header_culm_name="";
					if($this->is_customerPayments == "2")
					{
						$header_culm_name='Waived Off Reason';
					}
						return [
							'Customer ID - Individual',
							'Organization Name',
							'Aadhar Number',
							'Contact Phone Number',
							'Person Name',
							'DOB (DD/MM/YYYY)',
							'Father Name',
							'Mother Name',
							'Due ID',
							'Reported Date (DD/MM/YYYY)',
							'DueDate (DD/MM/YYYY)',
							'DueAmount',
							'PaidDate (DD/MM/YYYY)',
							'Payment Updated Date',
							'PaidAmount',
							'DueNote',
							'PaidNote',
							'Email',
							'Grace Period',
							'Custom ID',
							'Invoice Number',
							 $header_culm_name
						   ];
				}
				else{

					return [

						'Customer ID - Individual',
						'Organization Name',
						'Aadhar Number',
						'Contact Phone Number',
						'Person Name',
						'DOB (DD/MM/YYYY)',
						'Father Name',
						'Mother Name',
						'Due ID',
						'Reported Date (DD/MM/YYYY)',
						'DueDate (DD/MM/YYYY)',
						'DueAmount',
						'Balance Due Amount',
						'DueNote',
						'Email',
						'Grace Period',
						'Custom ID',
						'Invoice Number',
						'Checkmy Report Link',
						'Proof of Due'
					   ];
				}

			}
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {


    	$authId = Auth::id();
		if(Auth::user()->role_id == 1 || Auth::user()->role_id == 14)
		{
			if($this->paymentOnlyDownload =="1")
			{
				if($this->is_customerPayments == "2")
				{

					$query=Students::select('student_due_fees.added_by as m_id','student_due_fees.student_id as s_id','users.business_name as bname','aadhar_number', 'contact_phone', 'person_name','dob as dob_dmy','father_name','mother_name','student_due_fees.id as d_id',
					DB::raw('DATE_FORMAT(student_due_fees.created_at, "%d/%m/%Y") As reported_date'),
					DB::raw('DATE_FORMAT(due_date, "%d/%m/%Y") As DueDate'),
					'student_due_fees.due_amount AS DueAmount',
					DB::raw('DATE_FORMAT(paid_date, "%d/%m/%Y") As PaidDate'),
					DB::raw('DATE_FORMAT(student_paid_fees.updated_at, "%d/%m/%Y") As PaymentUpdatedDate'),
					'student_paid_fees.paid_amount AS PaidAmount','due_note','paid_note','students.email as email','grace_period', 'student_due_fees.external_student_id','invoice_no','student_paid_fees.payment_options_drop_down')->where('student_due_fees.added_by','!=',0);
				}else{
					$query=Students::select('student_due_fees.added_by as m_id','student_due_fees.student_id as s_id','users.business_name as bname','aadhar_number', 'contact_phone', 'person_name','dob as dob_dmy','father_name','mother_name','student_due_fees.id as d_id',
					DB::raw('DATE_FORMAT(student_due_fees.created_at, "%d/%m/%Y") As reported_date'),
					DB::raw('DATE_FORMAT(due_date, "%d/%m/%Y") As DueDate'),
					'student_due_fees.due_amount AS DueAmount',
					DB::raw('DATE_FORMAT(paid_date, "%d/%m/%Y") As PaidDate'),
					DB::raw('DATE_FORMAT(student_paid_fees.updated_at, "%d/%m/%Y") As PaymentUpdatedDate'),
					'student_paid_fees.paid_amount AS PaidAmount','due_note','paid_note','students.email as email','grace_period', 'student_due_fees.external_student_id','invoice_no')->where('student_due_fees.added_by','!=',0);
				}
				/*Students::select('student_due_fees.added_by as m_id','student_due_fees.student_id as s_id','student_due_fees.id as d_id',
					DB::raw('DATE_FORMAT(student_due_fees.created_at, "%d/%m/%Y") As reported_date'),
					DB::raw('DATE_FORMAT(due_date, "%d/%m/%Y") As DueDate'),
					'student_due_fees.due_amount AS DueAmount',
					DB::raw('DATE_FORMAT(paid_date, "%d/%m/%Y") As PaidDate'),
					DB::raw('DATE_FORMAT(student_paid_fees.updated_at, "%d/%m/%Y") As PaymentUpdatedDate'),
					 'student_paid_fees.paid_amount AS PaidAmount','paid_note')->where('student_due_fees.added_by','!=',0);*/

			}
			else{
				$query=Students::select('student_due_fees.added_by as m_id','student_due_fees.student_id as s_id','users.business_name as bname','aadhar_number', 'contact_phone', 'person_name','dob as dob_dmy','father_name','mother_name','student_due_fees.id as d_id',
				DB::raw('DATE_FORMAT(student_due_fees.created_at, "%d/%m/%Y") As reported_date'),
				DB::raw('DATE_FORMAT(due_date, "%d/%m/%Y") As DueDate'),
				'student_due_fees.due_amount AS DueAmount','student_due_fees.balance_due AS balance_due',
				 'due_note','students.email as email','grace_period',
				 'student_due_fees.external_student_id','student_due_fees.invoice_no','uniqe_url_individual',DB::raw("IF(student_due_fees.proof_of_due!='null' and student_due_fees.proof_of_due!='' , student_due_fees.proof_of_due,'NO' ) As proof_of_due"))->where('student_due_fees.added_by','!=',0);
			}

			 $query->leftJoin('student_due_fees',function($q){
				$q->on('students.id','=','student_due_fees.student_id');
				$q->where('student_due_fees.deleted_at','=',null);
				if(Auth::user()->role_id != $this->loginId){
					$q->where('student_due_fees.added_by',Auth::id());
				}
				$q->groupBy('student_due_fees'.'student_id');
		})
		->leftJoin('users','student_due_fees.added_by','=','users.id');
		if($this->paymentOnlyDownload =="1")
		{
			$query->Join('student_paid_fees',function($q){
				//$q->on('student_due_fees.id','=','student_paid_fees.due_id');
				$q->on('student_due_fees.student_id','=','student_paid_fees.student_id');
				if($this->is_customerPayments == "2")
				{
					$q->where('student_paid_fees.due_id','=',0);
				}
				$q->where('student_paid_fees.deleted_at','=',null);
				if(Auth::user()->role_id != $this->loginId){
					$q->where('student_paid_fees.added_by',Auth::id());
				}
				$q->groupBy('student_paid_fees'.'student_id');
		});

		}

		return $query->where(function($q){
			if(Auth::user()->role_id != $this->loginId){
				$q->where('students.added_by',Auth::user()->id);
			}
		})
		->where(function($q){
			if(($this->fromdate != 0) && ($this->todate != 0)){
				if($this->paymentOnlyDownload =="0")
					{
					if($this->dropDownType == "Due")
					{
						$q->where('student_due_fees.due_date', '>=', $this->fromdate);
						$q->where('student_due_fees.due_date', '<', $this->todate);
					}
					if($this->dropDownType == "Reported" ){
						$q->where('student_due_fees.created_at', '>=', $this->fromdate);
						$q->where('student_due_fees.created_at', '<', $this->todate);
					}
					if($this->dropDownType == "Collections" ){
						$q->where('student_due_fees.collection_date', '>=', $this->fromdate);
						$q->where('student_due_fees.collection_date', '<', $this->todate);
					}
					if($this->dropDownType == "Invoice" ){
						$q->where('student_due_fees.invoice_date', '>=', $this->fromdate);
						$q->where('student_due_fees.invoice_date', '<', $this->todate);
					}

				}else{

					if($this->dropDownType == "Payment" ){
						$q->where('student_paid_fees.paid_date', '>=', $this->fromdate);
						$q->where('student_paid_fees.paid_date', '<=', $this->todate);
						$q->where('student_paid_fees.paid_amount', '!=', null);
					}
				}

			}else{

				if($this->dropDownType == "Reported" ){
					$q->where('student_due_fees.created_at', '!=', null);
				}else if($this->dropDownType == "Due" ){
					$q->where('student_due_fees.due_date', '!=', null);
				}else if($this->dropDownType == "Invoice" ){
					$q->where('student_due_fees.invoice_date', '!=', null);
				}else if($this->dropDownType == "Payment" ){
					$q->where('student_paid_fees.paid_date', '!=', null);
				}else{
					$q->where('student_due_fees.collection_date', '!=', null);
				}
			}
		})
		/*->where(function($q){
			if($this->date != 0){
				if($this->paymentOnlyDownload == "1"){
				$q->where('student_paid_fees.paid_date', '=', $this->date);
				}
			}
		})
		->where(function($q){
			if($this->paymentOnlyDownload == "1"){
				$q->where('student_paid_fees.paid_amount', '!=', null);
			}
		})*/
		->get();

		}


			else  {

				if($this->paymentOnlyDownload =="0"){
					$query=Students::select('student_due_fees.student_id as s_id','users.business_name as bname','aadhar_number', 'contact_phone', 'person_name','dob as dob_dmy','father_name','mother_name','student_due_fees.id as d_id',
					DB::raw('DATE_FORMAT(student_due_fees.created_at, "%d/%m/%Y") As reported_date'),
					DB::raw('DATE_FORMAT(due_date, "%d/%m/%Y") As DueDate'),
					'student_due_fees.due_amount AS DueAmount','due_note','students.email as email','grace_period', 'student_due_fees.external_student_id','student_due_fees.invoice_no','uniqe_url_individual', DB::raw("IF(student_due_fees.proof_of_due!='null' and student_due_fees.proof_of_due!='' , student_due_fees.proof_of_due,'NO' ) As proof_of_due"))->where('student_due_fees.added_by','!=',0);
				}
				if($this->paymentOnlyDownload =="1")
				{
					if($this->is_customerPayments == "2")
					{
						$query=Students::select('student_due_fees.student_id as s_id','users.business_name as bname','aadhar_number', 'contact_phone', 'person_name','dob as dob_dmy','father_name','mother_name','student_due_fees.id as d_id',
						DB::raw('DATE_FORMAT(student_due_fees.created_at, "%d/%m/%Y") As reported_date'),
						DB::raw('DATE_FORMAT(due_date, "%d/%m/%Y") As DueDate'),
						'student_due_fees.due_amount AS DueAmount',
						DB::raw('DATE_FORMAT(paid_date, "%d/%m/%Y") As PaidDate'),
						DB::raw('DATE_FORMAT(student_paid_fees.updated_at, "%d/%m/%Y") As PaymentUpdatedDate'),
						'student_paid_fees.paid_amount AS PaidAmount','due_note','paid_note','students.email as email','grace_period', 'student_due_fees.external_student_id','invoice_no','student_paid_fees.payment_options_drop_down')->where('student_due_fees.added_by','!=',0);
					}else{
						$query=Students::select('student_due_fees.student_id as s_id','users.business_name as bname','aadhar_number', 'contact_phone', 'person_name','dob as dob_dmy','father_name','mother_name','student_due_fees.id as d_id',
						DB::raw('DATE_FORMAT(student_due_fees.created_at, "%d/%m/%Y") As reported_date'),
						DB::raw('DATE_FORMAT(due_date, "%d/%m/%Y") As DueDate'),
						'student_due_fees.due_amount AS DueAmount','student_due_fees.balance_due AS balance_due',
						DB::raw('DATE_FORMAT(paid_date, "%d/%m/%Y") As PaidDate'),
						DB::raw('DATE_FORMAT(student_paid_fees.updated_at, "%d/%m/%Y") As PaymentUpdatedDate'),
						 'student_paid_fees.paid_amount AS PaidAmount','due_note','paid_note','students.email as email','grace_period', 'student_due_fees.external_student_id','invoice_no')->where('student_due_fees.added_by','!=',0);
					}
					 /*$query=Students::select('student_due_fees.student_id as s_id','student_due_fees.id as d_id',
					DB::raw('DATE_FORMAT(student_due_fees.created_at, "%d/%m/%Y") As reported_date'),
					DB::raw('DATE_FORMAT(due_date, "%d/%m/%Y") As DueDate'),
					'student_due_fees.due_amount AS DueAmount',
					DB::raw('DATE_FORMAT(paid_date, "%d/%m/%Y") As PaidDate'),
				    DB::raw('DATE_FORMAT(student_paid_fees.updated_at, "%d/%m/%Y") As PaymentUpdatedDate'),
					 'student_paid_fees.paid_amount AS PaidAmount',
					 'paid_note')->where('student_due_fees.added_by','!=',0);*/
				}

				 $query->leftJoin('student_due_fees',function($q){
					$q->on('students.id','=','student_due_fees.student_id');
					$q->where('student_due_fees.deleted_at','=',null);
					if(Auth::user()->role_id != 1){
						$q->where('student_due_fees.added_by',Auth::id());
					}
					$q->groupBy('student_due_fees'.'student_id');
			})->leftJoin('users','student_due_fees.added_by','=','users.id');

			if($this->paymentOnlyDownload =="1")
			{
				 $query->Join('student_paid_fees',function($q){
					if($this->is_customerPayments == "2")
					{
						$q->on('student_due_fees.student_id','=','student_paid_fees.student_id');
						$q->where('student_paid_fees.due_id','=',0);
					}else{
					$q->on('student_due_fees.id','=','student_paid_fees.due_id');
					}

					$q->on('student_due_fees.student_id','=','student_paid_fees.student_id');
					$q->where('student_paid_fees.deleted_at','=',null);
					if(Auth::user()->role_id != 1){
						$q->where('student_paid_fees.added_by',Auth::id());
					}
					$q->groupBy('student_paid_fees'.'student_id');
					});
			 }


			return $query->where(function($q){
				if(Auth::user()->role_id != 1){
					$q->where('students.added_by',Auth::user()->id);
				}
			})


			->where(function($q){
				if(($this->fromdate != 0) && ($this->todate != 0)){
					if($this->paymentOnlyDownload =="0")
						{
						if($this->dropDownType == "Due")
						{
							$q->where('student_due_fees.due_date', '>=', $this->fromdate);
							$q->where('student_due_fees.due_date', '<', $this->todate);
						}
						if($this->dropDownType == "Reported" ){
							$q->where('student_due_fees.created_at', '>=', $this->fromdate);
						    $q->where('student_due_fees.created_at', '<', $this->todate);
						}
						if($this->dropDownType == "Collections" ){
							$q->where('student_due_fees.collection_date', '>=', $this->fromdate);
							$q->where('student_due_fees.collection_date', '<', $this->todate);
						}
						if($this->dropDownType == "Invoice" ){
							$q->where('student_due_fees.invoice_date', '>=', $this->fromdate);
						    $q->where('student_due_fees.invoice_date', '<', $this->todate);
						}

					}else{

						if($this->dropDownType == "Payment" ){
							$q->where('student_paid_fees.paid_date', '>=', $this->fromdate);
							$q->where('student_paid_fees.paid_date', '<', $this->todate);
							$q->where('student_paid_fees.paid_amount', '!=', null);
						}
					}

				}else{

					if($this->dropDownType == "Reported" ){
						$q->where('student_due_fees.created_at', '!=', null);
					}else if($this->dropDownType == "Due" ){
						$q->where('student_due_fees.due_date', '!=', null);
					}else if($this->dropDownType == "Invoice" ){
						$q->where('student_due_fees.invoice_date', '!=', null);
					}else if($this->dropDownType == "Payment" ){
						$q->where('student_paid_fees.paid_date', '!=', null);
					}else{
						$q->where('student_due_fees.collection_date', '!=', null);
					}
				}
			})

			/*->where(function($q){
				if($this->date != 0){
					if($this->paymentOnlyDownload =="1")
				{
					$q->where('student_paid_fees.paid_date', '=', $this->date);
				}

				}
			})*/

			->get();
			}

        // Students::all();
    }
}
