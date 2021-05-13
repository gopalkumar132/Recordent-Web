<?php

use Illuminate\Support\Facades\Session as Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Debug\Exception\FlattenException;
use Carbon\Carbon;
use App\Services\SmsService;
use App\User;
use App\PricingPlan;
use App\MemberCustomerMapping;
/**
 * 
 */
class CustomerHelper
{
	/*
	* $member_id - Member ID - Integer
	* $customer_id - Customer ID - Integer - Individual|Business
	* $customer_type - Customer Type - Integer - 1-Individual, 2-Business
	*/
	public static function insertIntoMemberCustomerIdMappingTable($member_id, $customer_id, $customer_type){

		$is_already_existing_customer = MemberCustomerMapping::where('member_id', $member_id)
										->where('customer_id', $customer_id)
										->where('customer_type', $customer_type)
										->first();

		if (!$is_already_existing_customer) {
			MemberCustomerMapping::create([
				'member_id' => $member_id,
				'customer_id' => $customer_id,
				'customer_type' => $customer_type
			]);
		}

		return true;
	}


	public static function getTotalCustomersByMemberId($member_id){
		$total_customers = MemberCustomerMapping::where('member_id', $member_id)->count();

		Log::debug('total_customers, member_id = '.$total_customers.', '.$member_id);
		return $total_customers;
	}


	public static function getRemainingFreeCustomersDuesLimit($member_id){
		
		$total_existing_customers = self::getTotalCustomersByMemberId($member_id);

		$member = User::find($member_id);
        $free_customer_limit = $member->user_pricing_plan->free_customer_limit;

        if (!$member->user_pricing_plan->plan_status || $member->user_pricing_plan->pricing_plan_id == 0) {
            $free_customer_limit = HomeHelper::getMemberPreviousPlanFreeCustomerLimit();
        }

        $remainingCustomer = $free_customer_limit - $total_existing_customers;

        if ($remainingCustomer <= 0) {
            $remainingCustomer = 0;
        }

        return $remainingCustomer;
	}

	public static function isAlreadyExistingCustomer($member_id, $customer_id, $customer_type){

		$is_already_existing_customer = MemberCustomerMapping::where('member_id', $member_id)
										->where('customer_id', $customer_id)
										->where('customer_type', $customer_type)
										->first();

		if (!empty($is_already_existing_customer)) {
			return true;
		}

		return false;
	}
}