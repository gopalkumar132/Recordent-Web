<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Auth;
use Carbon\carbon;
use App\UserPricingPlan;
use App\PricingPlan;
use Log;

class UsersExport implements FromCollection, WithHeadings
{  
	protected $date;
      public function __construct($date) {
       $this->date = $date;
 }

    public function headings():array
    {
        return [
    		'Member Id',
    		'Member Name',
    		'Other Business Type',
    		'Member Address',
    		'Branch Name',
    		'Contact Person',
    		'Contact Phone',
    		'Contact Email',
    		'Registration Date',
    		'GSTIN/Business Pan',
    		'Pincode',
    		'State',
    		'City',
    		'Business Short',
    		'Country Code',
    		'Country',
    		'Business Type',		
    		'Updated At',
    		'Email Sent At',
    		'Membership Type',
    		'Email Verified',
    		'Mobile Verified',
    		'Status',
			'Referral Code',
			'Valid Referral Code',
			'Referral Transaction',
			'Subscription Date'
    		
    		
    		
		];
    }
	
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$authId = Auth::id();
		$data = User::select('users.id as member_id',
					'users.business_name as name',
					'users.type_of_business',
					'address',
					'branch_name',
					'users.name as contact_person',
					'mobile_number',
					'email',
					DB::raw('DATE_FORMAT(users.created_at, "%d/%m/%Y") As member_created_at'),
					'gstin_udise',
					'pincode',	
					'states.name as state_name',
					'cities.name as city_name',
					'business_short',
					'users.country_code',
					'countries.name as country_name',
					'user_types.name as business_type',
					'users.updated_at as updated_at',
					'email_sent_at',
					'pricing_plans.name as membershiptype',
					DB::raw("IF(email_verified_at, 'YES','NO' ) As email_verified"),
					DB::raw("IF(mobile_verified_at, 'YES','NO' ) As mobile_verified"),
					DB::raw("IF(users.status=1, 'ACTIVE','INACTIVE' ) As status"),
					'users_offer_codes.offer_code as referral_code',
					DB::raw('(CASE
					 	WHEN users_offer_codes.offer_code_status = "1"
					 	and users_offer_codes.offer_code!="" THEN "VALID" 
					 	WHEN users_offer_codes.offer_code_status = "0"
					 	and users_offer_codes.offer_code!="" THEN "INVALID"
					 	ELSE ""
					 	END) AS valid_referral'),
					DB::raw('(CASE
					 	WHEN users_offer_codes.offer_code_used = "1" and users_offer_codes.offer_code_status = "1"
					 	and users_offer_codes.offer_code!="" THEN "COMPLETED" 
					 	WHEN users_offer_codes.offer_code_used = "0" and users_offer_codes.offer_code_status = "1" 
					 		and users_offer_codes.offer_code != "" THEN "NOT COMPLETED" 
					 	ELSE ""
					 	END) AS referral_transaction'),
					DB::raw('(CASE
					 	WHEN user_pricing_plan.pricing_plan_id = "1"  THEN "" 
					 	WHEN user_pricing_plan.pricing_plan_id != "1"  THEN DATE_FORMAT(user_pricing_plan.start_date, "%d/%m/%Y")  
					 	ELSE ""
					 	END) AS start_date_plan'),
					)->where('users.created_at','>',$this->date)
					->where('users.is_deleted','=',0)
				->leftJoin('user_pricing_plan','users.id','=','user_pricing_plan.user_id')
				->leftJoin('pricing_plans','user_pricing_plan.pricing_plan_id','=','pricing_plans.id')
				
				->leftJoin('states','users.state_id','=','states.id')
				->leftJoin('cities','users.city_id','=','cities.id')
				->leftJoin('countries','users.country_id','=','countries.id')
				->leftJoin('user_types','users.user_type','=','user_types.id')
				->leftJoin('users_offer_codes','users.id','=','users_offer_codes.user_id')
				->orderBy('member_id')
				->get();
				return $data;
    }
}
