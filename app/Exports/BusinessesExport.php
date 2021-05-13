<?php

namespace App\Exports;

use App\Businesses;
use App\BusinessDueFees;
use App\BusinessPaidFees;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Auth;

class BusinessesExport implements FromCollection, WithHeadings
{
    protected $fromdate;
	protected $todate;
	protected $dropDownType;
	protected $loginId;
	public $paymentOnlyDownload;
      public function __construct($loginId,$paymentOnlyDownload="",$fromExport="businessExport",$fromdate,$todate,$dropDownType) {

       $this->loginId = $loginId;
	   $this->paymentOnlyDownload=$paymentOnlyDownload;
	   $this->fromExport=$fromExport;
	   $this->fromdate = $fromdate;
	   $this->todate =$todate;
	   $this->dropDownType =$dropDownType;
   }
    public function headings():array
    {
        /*return ['First Name',
				'Last Name',
				'DOB (MM/DD/YYYY)',
				'Father First Name',
				'Father Last Name',
				'Mother First Name',
				'Mother Last Name',
				'Aadhar',
				'Contact Phone',
				'Due Date (MM/DD/YYYY)',
				'Due Amount',
				'Paid Date (MM/DD/YYYY)',
				'Paid Amount',
				'Due Note',
				'Paid Note',
			   ];*/
		if(Auth::user()->role_id == 1 || Auth::user()->role_id == 14)
		{
			if( $this->paymentOnlyDownload =="1" || $this->paymentOnlyDownload =="2"){
				return [
					'Member ID',
					'Customer ID - Business',
						'Organization Name',
						'Company Name',
						'Business PAN/GSTIN (Unique Identification Number)',
						'Person Name',
						'Sector',
						'Designation',
						'Contact Phone',
						'Alternate Contact Phone',
						'Address',
						'State',
						'City',
						'Pincode',
						'Due ID',
						'Reported Date (DD/MM/YYYY)',
						'DueDate (DD/MM/YYYY)',
						'DueAmount',
						'PaidDate (DD/MM/YYYY)',
						'Payment Updated Date',
						'PaidAmount',
						'PaidNote',
						'DueNote',
						'Email',
						'Grace Period',
						'Proof of Due',
						'Custom ID',
						'Invoice Number',
            'Waived Off Reason'
				   ];
			}else{
				return [
					'Member ID',
					'Customer ID - Business',
					'Organization Name',
					'Company Name',
					'Business PAN/GSTIN (Unique Identification Number)',
					'Person Name',
					'Sector',
					'Designation',
					'Contact Phone',
					'Alternate Contact Phone',
					'Address',
					'State',
					'City',
					'Pincode',
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
					'Proof of Due',
				   ];
			}
		}

			   else {

				if( $this->paymentOnlyDownload =="1" || $this->paymentOnlyDownload =="2"){
					return [
						'Customer ID - Business',
						'Organization Name',
						'Company Name',
						'Business PAN/GSTIN (Unique Identification Number)',
						'Person Name',
						'Sector',
						'Designation',
						'Contact Phone',
						'Alternate Contact Phone',
						'Address',
						'State',
						'City',
						'Pincode',
						'Due ID',
						'Reported Date (DD/MM/YYYY)',
						'DueDate (DD/MM/YYYY)',
						'DueAmount',
						'PaidDate (DD/MM/YYYY)',
						'Payment Updated Date',
						'PaidAmount',
						'PaidNote',
						'DueNote',
						'Email',
						'Grace Period',
						'Proof of Due',
						'Custom ID',
						'Invoice Number',
            'Waived Off Reason'
					   ];
				}else{
					return [
						'Customer ID - Business',
						'Organization Name',
						'Company Name',
						'Business PAN/GSTIN (Unique Identification Number)',
						'Person Name',
						'Sector',
						'Designation',
						'Contact Phone',
						'Alternate Contact Phone',
						'Address',
						'State',
						'City',
						'Pincode',
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
						'Proof of Due',
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
			if( $this->paymentOnlyDownload =="1")
			{
				$query= Businesses::select('business_due_fees.added_by as m_id','business_due_fees.business_id as b_id','users.business_name as bname','businesses.company_name','businesses.unique_identification_number','businesses.concerned_person_name','sectors.name as sector_name',
				'concerned_person_designation','concerned_person_phone','concerned_person_alternate_phone',
				'businesses.address as baddress','states.name as state_name','cities.name as city_name','businesses.pincode','business_due_fees.id as d_id',
				DB::raw('DATE_FORMAT(business_due_fees.created_at, "%d/%m/%Y") As reported_date'),
				DB::raw('DATE_FORMAT(due_date, "%d/%m/%Y") As DueDate'),
			   'business_due_fees.due_amount AS DueAmount', DB::raw('DATE_FORMAT(paid_date, "%d/%m/%Y") As PaidDate'),
			   DB::raw('DATE_FORMAT(business_paid_fees.updated_at, "%d/%m/%Y") As PaymentUpdatedDate'),
				'business_paid_fees.paid_amount AS PaidAmount','paid_note','due_note','businesses.email as email','grace_period',DB::raw("IF(business_due_fees.proof_of_due!='null' and business_due_fees.proof_of_due!='' , 'YES','NO' ) As proof_of_due"), 'business_due_fees.external_business_id','invoice_no','business_paid_fees.payment_options_drop_down')
				   ->where('business_due_fees.added_by','!=',0);

				/*Businesses::select('business_due_fees.added_by as m_id','business_due_fees.business_id as b_id',
								 'business_due_fees.id as d_id',
								  DB::raw('DATE_FORMAT(business_due_fees.created_at, "%d/%m/%Y") As reported_date'),
								  DB::raw('DATE_FORMAT(due_date, "%d/%m/%Y") As DueDate'),
								 'business_due_fees.due_amount AS DueAmount', DB::raw('DATE_FORMAT(paid_date, "%d/%m/%Y") As PaidDate'),
								 DB::raw('DATE_FORMAT(business_paid_fees.updated_at, "%d/%m/%Y") As PaymentUpdatedDate'),
								  'business_paid_fees.paid_amount AS PaidAmount','paid_note')->where('business_due_fees.added_by','!=',0);*/
			} else if($this->paymentOnlyDownload =="2") {
        $query= Businesses::select('business_due_fees.added_by as m_id','business_due_fees.business_id as b_id','users.business_name as bname','businesses.company_name','businesses.unique_identification_number','businesses.concerned_person_name','sectors.name as sector_name',
        'concerned_person_designation','concerned_person_phone','concerned_person_alternate_phone',
        'businesses.address as baddress','states.name as state_name','cities.name as city_name','businesses.pincode','business_due_fees.id as d_id',
        DB::raw('DATE_FORMAT(business_due_fees.created_at, "%d/%m/%Y") As reported_date'),
        DB::raw('DATE_FORMAT(due_date, "%d/%m/%Y") As DueDate'),
         'business_due_fees.due_amount AS DueAmount', DB::raw('DATE_FORMAT(paid_date, "%d/%m/%Y") As PaidDate'),
         DB::raw('DATE_FORMAT(business_paid_fees.updated_at, "%d/%m/%Y") As PaymentUpdatedDate'),
        'business_paid_fees.paid_amount AS PaidAmount','paid_note','due_note','businesses.email as email','grace_period',DB::raw("IF(business_due_fees.proof_of_due!='null' and business_due_fees.proof_of_due!='' , 'YES','NO' ) As proof_of_due"), 'business_due_fees.external_business_id','invoice_no','business_paid_fees.payment_options_drop_down')
;
           //->where('business_due_fees.added_by','!=',0);
      }
			else
			{
				  $query= Businesses::select('business_due_fees.added_by as m_id','business_due_fees.business_id as b_id','users.business_name as bname','businesses.company_name','businesses.unique_identification_number','businesses.concerned_person_name','sectors.name as sector_name',
				'concerned_person_designation','concerned_person_phone','concerned_person_alternate_phone',
				'businesses.address as baddress','states.name as state_name','cities.name as city_name','businesses.pincode','business_due_fees.id as d_id',
				DB::raw('DATE_FORMAT(business_due_fees.created_at, "%d/%m/%Y") As reported_date'),
				DB::raw('DATE_FORMAT(due_date, "%d/%m/%Y") As DueDate'), 'business_due_fees.due_amount AS DueAmount', 'business_due_fees.balance_due AS balance_due',
				'due_note','businesses.email as email','grace_period', 'business_due_fees.external_business_id','business_due_fees.invoice_no','uniqe_url_business', DB::raw("IF(business_due_fees.proof_of_due!='null' and business_due_fees.proof_of_due!='' , business_due_fees.proof_of_due,'NO' ) As proof_of_due"))
				   ->where('business_due_fees.added_by','!=',0);
			}

				$query->leftJoin('business_due_fees',function($q){
									$q->on('businesses.id','=','business_due_fees.business_id');
									$q->where('business_due_fees.deleted_at','=',null);
									if(Auth::user()->role_id != $this->loginId){
										$q->where('business_due_fees.added_by',Auth::id());
									}
								    $q->groupBy('business_due_fees'.'business_id');
							})
							->leftJoin('users','business_due_fees.added_by','=','users.id');
							 if($this->paymentOnlyDownload == "1")
							 {
								 $query->leftJoin('business_paid_fees',function($q){
									$q->on('business_due_fees.id','=','business_paid_fees.due_id');
									$q->where('business_paid_fees.deleted_at','=',null);
									if(Auth::user()->role_id != $this->loginId){
										$q->where('business_paid_fees.added_by',Auth::id());
									}
								    $q->groupBy('business_paid_fees'.'business_id');
								});
							 }

               if($this->paymentOnlyDownload == "2")
							 {
								 $query->Join('business_paid_fees',function($q){
									$q->on('business_due_fees.business_id','=','business_paid_fees.business_id');
                  $q->where('business_paid_fees.due_id','=',0);
                  $q->where('business_paid_fees.deleted_at','=',null);
									if(Auth::user()->role_id != $this->loginId){
										$q->where('business_paid_fees.added_by',Auth::id());
									}
								    //$q->groupBy('business_paid_fees'.'business_id');
								});
							 }

					return $query->leftJoin('sectors','businesses.sector_id','=','sectors.id')
							->leftJoin('states','businesses.state_id','=','states.id')
							->leftJoin('cities','businesses.city_id','=','cities.id')
							->where(function($q){
								if(Auth::user()->role_id != $this->loginId){
									$q->where('businesses.added_by',Auth::user()->id);
								}
							})
							->where(function($q){
								if(($this->fromdate != 0) && ($this->todate != 0)){
									if($this->paymentOnlyDownload =="1")
										{

											if($this->dropDownType == "Payment" ){
												$q->where('business_paid_fees.paid_date', '>=', $this->fromdate);
												$q->where('business_paid_fees.paid_date', '<', $this->todate);
											}

									}else{
										if($this->dropDownType == "Due")
										{
											$q->where('business_due_fees.due_date', '>=', $this->fromdate);
											$q->where('business_due_fees.due_date', '<', $this->todate);
										}
										if($this->dropDownType == "Reported" ){
											$q->where('business_due_fees.created_at', '>=', $this->fromdate);
											$q->where('business_due_fees.created_at', '<', $this->todate);
										}
										if($this->dropDownType == "Collections" ){
											$q->where('business_due_fees.collection_date', '>=', $this->fromdate);
											$q->where('business_due_fees.collection_date', '<', $this->todate);
										}
										if($this->dropDownType == "Invoice" ){
											$q->where('business_due_fees.invoice_date', '>=', $this->fromdate);
											$q->where('business_due_fees.invoice_date', '<', $this->todate);
										}

									}

								}else{

									if($this->dropDownType == "Reported" ){
										$q->where('business_due_fees.created_at', '!=', null);
									}else if($this->dropDownType == "Due" ){
										$q->where('business_due_fees.due_date', '!=', null);
									}else if($this->dropDownType == "Invoice" ){
										$q->where('business_due_fees.invoice_date', '!=', null);
									}else if($this->dropDownType == "Payment" ){
										$q->where('business_paid_fees.paid_date', '!=', null);
									}else{
										$q->where('business_due_fees.collection_date', '!=', null);
									}
								}
							})

							/*->where(function($q){
								if($this->date != 0){
									if($this->paymentOnlyDownload == "1"){
										$q->where('business_paid_fees.paid_date', '=', $this->date);
									}

								}
							})
							->where(function($q){
								if($this->paymentOnlyDownload == "1"){
									// $q->where('business_paid_fees.paid_amount', '!=', null);
								}
							})*/
							->get();
							// return $data;
		}

		else {

			if( $this->paymentOnlyDownload =="1")
			{
				 $query= Businesses::select('business_due_fees.business_id as b_id','users.business_name as bname','businesses.company_name','businesses.unique_identification_number','businesses.concerned_person_name','sectors.name as sector_name',
				 'concerned_person_designation','concerned_person_phone','concerned_person_alternate_phone',
				 'businesses.address as baddress','states.name as state_name','cities.name as city_name','businesses.pincode','business_due_fees.id as d_id',
				 DB::raw('DATE_FORMAT(business_due_fees.created_at, "%d/%m/%Y") As reported_date'),
				 DB::raw('DATE_FORMAT(due_date, "%d/%m/%Y") As DueDate'),
				'business_due_fees.due_amount AS DueAmount', DB::raw('DATE_FORMAT(paid_date, "%d/%m/%Y") As PaidDate'),
				DB::raw('DATE_FORMAT(business_paid_fees.updated_at, "%d/%m/%Y") As PaymentUpdatedDate'),
				 'business_paid_fees.paid_amount AS PaidAmount','paid_note','due_note','businesses.email as email','grace_period',DB::raw("IF(business_due_fees.proof_of_due!='null' and business_due_fees.proof_of_due!='' , 'YES','NO' ) As proof_of_due"), 'business_due_fees.external_business_id','invoice_no')->where('business_due_fees.added_by','!=',0);

				/* Businesses::select('business_due_fees.business_id as b_id','business_due_fees.id as d_id',
				DB::raw('DATE_FORMAT(business_due_fees.created_at, "%d/%m/%Y") As reported_date'),
				DB::raw('DATE_FORMAT(due_date, "%d/%m/%Y") As DueDate'),
			   'business_due_fees.due_amount AS DueAmount', DB::raw('DATE_FORMAT(paid_date, "%d/%m/%Y") As PaidDate'),
			   DB::raw('DATE_FORMAT(business_paid_fees.updated_at, "%d/%m/%Y") As PaymentUpdatedDate'),
				'business_paid_fees.paid_amount AS PaidAmount','paid_note')->where('business_due_fees.added_by','!=',0);*/
			}
      else if($this->paymentOnlyDownload =="2") {
       $query= Businesses::select('business_due_fees.added_by as m_id','business_due_fees.business_id as b_id','users.business_name as bname','businesses.company_name','businesses.unique_identification_number','businesses.concerned_person_name','sectors.name as sector_name',
       'concerned_person_designation','concerned_person_phone','concerned_person_alternate_phone',
       'businesses.address as baddress','states.name as state_name','cities.name as city_name','businesses.pincode','business_due_fees.id as d_id',
       DB::raw('DATE_FORMAT(business_due_fees.created_at, "%d/%m/%Y") As reported_date'),
       DB::raw('DATE_FORMAT(due_date, "%d/%m/%Y") As DueDate'),
        'business_due_fees.due_amount AS DueAmount', DB::raw('DATE_FORMAT(paid_date, "%d/%m/%Y") As PaidDate'),
        DB::raw('DATE_FORMAT(business_paid_fees.updated_at, "%d/%m/%Y") As PaymentUpdatedDate'),
       'business_paid_fees.paid_amount AS PaidAmount','paid_note','due_note','businesses.email as email','grace_period',DB::raw("IF(business_due_fees.proof_of_due!='null' and business_due_fees.proof_of_due!='' , 'YES','NO' ) As proof_of_due"), 'business_due_fees.external_business_id','invoice_no','business_paid_fees.payment_options_drop_down')
          ->where('business_due_fees.added_by','!=',0);
     }
      else{
				 $query= Businesses::select('business_due_fees.business_id as b_id','users.business_name as bname','businesses.company_name','businesses.unique_identification_number','businesses.concerned_person_name','sectors.name as sector_name',
								  'concerned_person_designation','concerned_person_phone','concerned_person_alternate_phone',
								  'businesses.address as baddress','states.name as state_name','cities.name as city_name','businesses.pincode','business_due_fees.id as d_id',
								  DB::raw('DATE_FORMAT(business_due_fees.created_at, "%d/%m/%Y") As reported_date'),
								  DB::raw('DATE_FORMAT(due_date, "%d/%m/%Y") As DueDate'),
								 'business_due_fees.due_amount AS DueAmount','business_due_fees.balance_due AS balance_due',
								'due_note','businesses.email as email','grace_period','business_due_fees.external_business_id','business_due_fees.invoice_no','uniqe_url_business',DB::raw("IF(business_due_fees.proof_of_due!='null' and business_due_fees.proof_of_due!='' , business_due_fees.proof_of_due,'NO' ) As proof_of_due"))
	                                 ->where('business_due_fees.added_by','!=',0);

			}

			$query->leftJoin('business_due_fees',function($q){
									$q->on('businesses.id','=','business_due_fees.business_id');
									$q->where('business_due_fees.deleted_at','=',null);
									if(Auth::user()->role_id != 1){
										$q->where('business_due_fees.added_by',Auth::id());
									}
								    //$q->groupBy('business_due_fees'.'business_id');
							})
							->leftJoin('users','business_due_fees.added_by','=','users.id');
							if($this->paymentOnlyDownload == "1")
							 {
								$query->Join('business_paid_fees',function($q){
									$q->on('business_due_fees.id','=','business_paid_fees.due_id');
									$q->where('business_paid_fees.deleted_at','=',null);
									if(Auth::user()->role_id != 1){
										$q->where('business_paid_fees.added_by',Auth::id());
									}
								    $q->groupBy('business_paid_fees'.'business_id');
							});
							 }
               if($this->paymentOnlyDownload == "2")
							 {
								 $query->Join('business_paid_fees',function($q){
									$q->on('business_due_fees.business_id','=','business_paid_fees.business_id');
                  $q->where('business_paid_fees.due_id','=',0);
									$q->where('business_paid_fees.deleted_at','=',null);
									if(Auth::user()->role_id != $this->loginId){
										$q->where('business_paid_fees.added_by',Auth::id());
									}
								    //$q->groupBy('business_paid_fees'.'business_id');
								});
							 }

						return	 $query->leftJoin('sectors','businesses.sector_id','=','sectors.id')
							->leftJoin('states','businesses.state_id','=','states.id')
							->leftJoin('cities','businesses.city_id','=','cities.id')
							->where(function($q){
								if(Auth::user()->role_id != 1){
									$q->where('businesses.added_by',Auth::user()->id);
								}
							})

							->where(function($q){
								if(($this->fromdate != 0) && ($this->todate != 0)){
									if($this->paymentOnlyDownload =="1")
										{

											if($this->dropDownType == "Payment" ){
												$q->where('business_paid_fees.paid_date', '>=', $this->fromdate);
												$q->where('business_paid_fees.paid_date', '<', $this->todate);
												$q->where('business_paid_fees.paid_amount', '!=', null);
											}

									}else{
										if($this->dropDownType == "Due")
										{
											$q->where('business_due_fees.due_date', '>=', $this->fromdate);
											$q->where('business_due_fees.due_date', '<', $this->todate);
										}
										if($this->dropDownType == "Reported" ){
											$q->where('business_due_fees.created_at', '>=', $this->fromdate);
											$q->where('business_due_fees.created_at', '<', $this->todate);
										}
										if($this->dropDownType == "Collections" ){
											$q->where('business_due_fees.collection_date', '>=', $this->fromdate);
											$q->where('business_due_fees.collection_date', '<', $this->todate);
										}
										if($this->dropDownType == "Invoice" ){
											$q->where('business_due_fees.invoice_date', '>=', $this->fromdate);
											$q->where('business_due_fees.invoice_date', '<', $this->todate);
										}

									}

								}else{

									if($this->dropDownType == "Reported" ){
										$q->where('business_due_fees.created_at', '!=', null);
									}else if($this->dropDownType == "Due" ){
										$q->where('business_due_fees.due_date', '!=', null);
									}else if($this->dropDownType == "Invoice" ){
										$q->where('business_due_fees.invoice_date', '!=', null);
									}else if($this->dropDownType == "Payment" ){
										$q->where('business_due_fees.paid_date', '!=', null);
									}else if($this->dropDownType == "Collections"){
										$q->where('business_due_fees.collection_date', '!=', null);
									}
								}
							})

							/*->where(function($q){
								if($this->date != 0){
									if($this->paymentOnlyDownload == "1"){
										$q->where('business_paid_fees.paid_date', '=', $this->date);
									}

								}
							})
							->where(function($q){
								if($this->paymentOnlyDownload == "1"){
									$q->where('business_paid_fees.paid_amount', '!=', null);
								}
							})*/
							->get();
							return $data;


        // Students::all();
				}

    }
}
