<?php

use Illuminate\Support\Facades\Session as Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Debug\Exception\FlattenException;
use Carbon\Carbon;
use App\Services\SmsService;
use App\CustomerKyc;
use App\UserType;
use App\Sector;
use App\State;
use App\City;
use App\User;
use App\PricingPlan;
use App\MembershipPayment;
use App\MembershipHistory;

class HomeHelper
{
	/*
	* Format user membership history details and return formatted output
	*/
	public static function getFormattedMembershipHistoryByMemberId($member_id){
		
		$output = array();
		$membership_data = MembershipHistory::where('customer_id', $member_id)->orderBy('id', 'DESC')->get();

		foreach ($membership_data as $key => $membership) {
			$output[$key]['plan_name'] = $membership->pricing_plan->name ?? '';
			$output[$key]['start_date'] = date('d-m-Y',strtotime($membership->start_date)) ?? '';
			$output[$key]['end_date'] = date('d-m-Y',strtotime($membership->end_date)) ?? '';
			$output[$key]['free_customer_limit'] = $membership->free_customer_limit ?? '';
			$output[$key]['plan_price'] = $membership->membership_price ?? '';

			if ($membership->membership_payment_id) {
				$output[$key]['invoice'] = route('get-membership-invoice', [$membership->membership_payment_id]);
			} else {
				$output[$key]['invoice'] = null;
			}
			
		}

		return $output;
	}

	/*
	* Format user pricing plan details and return formatted output
	*/
	public static function getFormattedUserPricingPlanDetails(){
		
		$output = array();

		Log::debug('getFormattedUserPricingPlanDetails');

		if (!Auth::user()->user_pricing_plan->plan_status && Auth::user()->user_pricing_plan->pricing_plan_id == 4) {
			Log::debug('plan_status');
			return self::getFormattedPreviousUserPricingPlanDetails();
		}

		$output['plan_name'] = self::getUserMembershipPlanName();
		$output['free_customer_limit'] = Auth::user()->user_pricing_plan->free_customer_limit ?? '';
		$output['additional_customer_price'] = Auth::user()->user_pricing_plan->additional_customer_price ?? '';
		$output['consent_comprehensive_report_price'] = Auth::user()->user_pricing_plan->consent_comprehensive_report_price ?? '';
		$output['recordent_cmph_report_bussiness_price'] = Auth::user()->user_pricing_plan->recordent_cmph_report_bussiness_price ?? '';
		$output['start_date'] = date('d-m-Y',strtotime(Auth::user()->user_pricing_plan->start_date)) ?? '';
		$output['end_date'] = date('d-m-Y',strtotime(Auth::user()->user_pricing_plan->end_date)) ?? '';
		$output['membership_plan_price'] = '-';
		$output['gst_price'] = '-';
		$output['invoice'] = null;

		if (Auth::user()->user_pricing_plan && !empty(Auth::user()->user_pricing_plan->membership_payment)) {
			// $output['free_customer_limit'] =
			$output['membership_plan_price'] = '₹ '.Auth::user()->user_pricing_plan->membership_plan_price ?? '';

			Log::debug("gst = " .self::getUserMembershipPlanGstPercentage());
			$output['gst_price'] = '₹ '.Auth::user()->user_pricing_plan->membership_plan_price * self::getUserMembershipPlanGstPercentage()/100;
		}

		if (Auth::user()->user_pricing_plan && Auth::user()->user_pricing_plan->pricing_plan_id != 1 && Auth::user()->user_pricing_plan->membership_payment_id ) {
			$output['invoice'] = route('invoice');
		}

		if (Auth::user()->user_pricing_plan->pricing_plan_id == 0 && self::getMemberPreviousPlanId() == 1) {
			$output['invoice'] = null;
		}

		$output['membership_history'] = self::getFormattedMembershipHistoryByMemberId(Auth::user()->id);

		return $output;
	}

	/*
	* get member previous plan details and return formatted output
	*/
	public static function getFormattedPreviousUserPricingPlanDetails(){

		Log::debug('getFormattedPreviousUserPricingPlanDetails');

		$previous_plan = Auth::user()->get_member_previous_plans[0];

		$output['plan_name'] = self::getUserMembershipPlanName();
		$output['free_customer_limit'] = $previous_plan->free_customer_limit ?? '';
		$output['additional_customer_price'] = $previous_plan->pricing_plan->additional_customer_price ?? '';
		$output['consent_comprehensive_report_price'] = $previous_plan->pricing_plan->consent_comprehensive_report_price ?? '';
		$output['recordent_cmph_report_bussiness_price'] = $previous_plan->pricing_plan->recordent_cmph_report_bussiness_price ?? '';
		$output['start_date'] = date('d-m-Y',strtotime($previous_plan->start_date)) ?? '';
		$output['end_date'] = date('d-m-Y',strtotime($previous_plan->end_date)) ?? '';
		$output['membership_plan_price'] = '-';
		$output['gst_price'] = '-';
		$output['invoice'] = null;

		if ($previous_plan->pricing_plan_id != 1) {
			$output['membership_plan_price'] = '₹ '.$previous_plan->membership_price ?? '';

			$output['gst_price'] = '₹ '.$previous_plan->membership_price * self::getUserMembershipPlanGstPercentage()/100;
		}

		if ($previous_plan->pricing_plan_id != 1) {
			$output['invoice'] = route('invoice');
		}

		$output['membership_history'] = self::getFormattedMembershipHistoryByMemberId(Auth::user()->id);

		return $output;
	}

	/*
	* Format user membership details and return formatted output
	*/
	public static function getFormattedMembershipDetailsByPlanId($plan_id){

		$output = array();
		$pricing_plan_details = PricingPlan::find($plan_id);

		$output['plan_id'] = $plan_id;
		$output['plan_name'] = $pricing_plan_details->name;
		$output['plan_price'] = $pricing_plan_details->membership_plan_price;
		$output['collection_fee'] = $pricing_plan_details->collection_fee;

		$gst_percentage = $pricing_plan_details->consent_recordent_report_gst;
		$cgst = $pricing_plan_details->membership_plan_price * $gst_percentage/200;

		$output['cgst'] = $cgst;
		$output['sgst'] = $cgst;
		$output['igst'] = $cgst+$cgst;
		$output['state_id'] = isset(Auth::user()->state_id)?Auth::user()->state_id : 0;

		$output['total_price'] = $pricing_plan_details->membership_plan_price + $output['igst'];

		return $output;

	}

	/*
	* Insert a new record into membership history table when ever member subscribes/upgrade/renews a plan
	*/
	public static function InsertIntoUserMembershipHistory($PricingPlan_obj, $customer_id, $membership_payment_id=null){

		$start_date = date('Y-m-d H:i:s');
		$end_date = date('Y-m-d H:i:s', strtotime('+364 day'));
		$free_customer_limit = $PricingPlan_obj->free_customer_limit;
		$membership_plan_price = $PricingPlan_obj->membership_plan_price;

		if (!Auth::user() || Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'Sub Admin') {
			
			$member = User::find($customer_id);
			$start_date = $member->user_pricing_plan->start_date;
			$end_date = $member->user_pricing_plan->end_date;
			$free_customer_limit = $member->user_pricing_plan->free_customer_limit;
			$membership_plan_price = $member->user_pricing_plan->membership_plan_price;
		}

		$insert = [
			'pricing_plan_id' => $PricingPlan_obj->id,
			'start_date' => $start_date,
        	'end_date' => $end_date,
        	'free_customer_limit' => $free_customer_limit,
        	'membership_price' => $membership_plan_price,
        	'membership_payment_id' => $membership_payment_id,
        	'customer_id' => $customer_id,
		];

		Log::debug('inserting membership history...plan id = '.$PricingPlan_obj->id);

		try {
			MembershipHistory::create($insert);
		} catch (Exception $e) {
			Log::debug($e->getMessage());
		}
		
		Log::debug('inserted membership history for user = '.$customer_id);
		return true;
	}

	/*
	* Handling pricing plans UI sections to show or hide
	*/
	public static function getShowOrHidePlanSectionClass($plan_id) {
		$show_hide_plan = '';

		$user_pricing_plan_id = Auth::user()->user_pricing_plan->pricing_plan_id ?? null;

		if (!empty(Auth::user()->user_pricing_plan)) {
			if (self::isPlanRenewable()) {
				$show_hide_plan = '';
			} else {

				if ($user_pricing_plan_id == 5 && $plan_id == 3) {
						$show_hide_plan = '';
				} else {
					if ($user_pricing_plan_id >= $plan_id || $user_pricing_plan_id == 3 && $plan_id == 5) {
		                $show_hide_plan = 'hide-plan';
		            }
				}
			}
		}

        return $show_hide_plan;
	}

	/*
	* check membership expiry date.
	*/
	public static function isPlanRenewable(){


		if (!empty(Auth::user()->user_pricing_plan)) {
			if(strtotime(Auth::user()->user_pricing_plan->end_date) < strtotime(date('Y-m-d H:i:s',strtotime('+10 days')))){
				return true;	
			} else if(Auth::user()->user_pricing_plan->pricing_plan_id == 4 && Auth::user()->user_pricing_plan->plan_status){

				$total_free_customer_limit = Auth::user()->user_pricing_plan->free_customer_limit;
				$remaining_free_customer_limit = General::getFreeCustomersDuesLimit(Auth::user()->id);

				$renewal_max_free_customer_limit_usage_percentage = config('membership_config.plan_renewal_max_free_customer_limit_usage_percentage');

				if ($remaining_free_customer_limit <= 0) {
		            $remaining_free_customer_limit = 0;
		        }

				$used_free_customer_limit = $total_free_customer_limit - $remaining_free_customer_limit;

				$used_free_customer_limit_percentage = round(($used_free_customer_limit/$total_free_customer_limit) * 100, 2);

				Log::debug("used_free_customer_limit_percentage = ".$used_free_customer_limit_percentage);

				if ($used_free_customer_limit_percentage >= $renewal_max_free_customer_limit_usage_percentage) {
					return true;
				}
			}	
		}

		
		return false;
	}

	/*
	* Calculate and return Member Remaining Membership plan price
	*/
	public static function getMembershipUpgradePlanPrice($member_id, $new_membership_plan_price){
		
		$member_data = User::find($member_id);

		$membership_remaining_balance_price = 0;

		Log::debug('new_membership_plan_price = '.$new_membership_plan_price);

		if ($member_data->user_pricing_plan && !self::isPlanRenewable()) {
			$membership_price = $member_data->user_pricing_plan->membership_plan_price;

			$membership_start_date = date_create($member_data->user_pricing_plan->start_date);
			$today_date = date_create(date('Y-m-d H:i:s'));

			$difference = date_diff($membership_start_date, $today_date);
			$no_of_days_consumed = $difference->format("%a");

			$remaining_days_left = 365 - $no_of_days_consumed;

			Log::debug('remaining_days_left = '.$remaining_days_left);

			$membership_remaining_balance_price = round(($remaining_days_left * $membership_price)/365);
			Log::debug('membership_remaining_balance_price = '.$membership_remaining_balance_price);
		}

		$membership_plan_price = $new_membership_plan_price - $membership_remaining_balance_price;
		Log::debug('membership_plan_price = '.$membership_plan_price);

		return $membership_plan_price;
	}

	/*
	* Get Membership upgrade plan price excluding gst or other charges.
	*/
	public static function getMembershipUpgradePlanPriceByPlanId($member_id, $plan_id){
		$pricing_plan_data = PricingPlan::find($plan_id);

		return self::getMembershipUpgradePlanPrice($member_id, $pricing_plan_data->membership_plan_price);
	}

	/*
	* Get Membership upgrade plan price excluding gst or other charges.
	*/
	public static function getMembershipUpgradeAdjustedAmount($member_id, $plan_id){
		$pricing_plan_data = PricingPlan::find($plan_id);

		$original_membership_plan_price = $pricing_plan_data->membership_plan_price;
		$new_membership_plan_price = self::getMembershipUpgradePlanPrice($member_id, $pricing_plan_data->membership_plan_price);

		$amount_adjusted = $original_membership_plan_price - $new_membership_plan_price;

		if ($amount_adjusted < 0) {
			$amount_adjusted = 0;
		}

		return $amount_adjusted;
	}

	/*
	* Get Member Previous pricing plan name from membership_history
	*/
	public static function getMemberPreviousPlanName(){
		
		$user = self::getUserObjectFromSessionOrAuth();

		$prev_plan_name = '';
		if (isset($user->get_member_previous_plans[0]->pricing_plan->name)) {
			$prev_plan_name = $user->get_member_previous_plans[0]->pricing_plan->name;
		}

		return $prev_plan_name;
	}

	public static function getMemberPreviousPlanGstPercentage(){
		
		$user = self::getUserObjectFromSessionOrAuth();

		$prev_plan_gst_percentage = 0;
		if (isset($user->get_member_previous_plans[0]->pricing_plan->consent_recordent_report_gst)) {
			$prev_plan_gst_percentage = $user->get_member_previous_plans[0]->pricing_plan->consent_recordent_report_gst;
		}

		return $prev_plan_gst_percentage;
	}

	/*
	* Get Member Previous pricing plan free_customer_limit from membership_history table
	*/
	public static function getMemberPreviousPlanFreeCustomerLimit(){
		
		$user = self::getUserObjectFromSessionOrAuth();

		$free_customer_limit = 0;
		if (isset($user->get_member_previous_plans[0]->free_customer_limit)) {
			$free_customer_limit = $user->get_member_previous_plans[0]->free_customer_limit;
		}

		return $free_customer_limit;
	}

	/*
	* Get user pricing plan name
	*/
	public static function getUserMembershipPlanName(){
		
		$user = self::getUserObjectFromSessionOrAuth();

		$plan_name = '';
		if ($user->user_pricing_plan) {
			if ($user->user_pricing_plan->pricing_plan_id == 0 || !$user->user_pricing_plan->plan_status) {
				$plan_name = self::getMemberPreviousPlanName();
				
			} else {
				$plan_name = $user->user_pricing_plan->pricing_plan->name ?? '';
			}
		}

		return $plan_name;
	}

	/*
	* Get user pricing plan consent_recordent_report_gst
	*/
	public static function getUserMembershipPlanGstPercentage(){
		
		$user = self::getUserObjectFromSessionOrAuth();

		$plan_gst_percentage = 0;
		if ($user->user_pricing_plan) {

			if ($user->user_pricing_plan->pricing_plan_id == 0 || !$user->user_pricing_plan->plan_status && $user->user_pricing_plan->pricing_plan_id != 4) {
				$plan_gst_percentage = self::getMemberPreviousPlanGstPercentage();
				
			} else {
				$plan_gst_percentage = $user->user_pricing_plan->pricing_plan->consent_recordent_report_gst;
			}
		}

		return $plan_gst_percentage;
	}

	/*
	* get Plan ID From Membership Hitory
	*/
	public static function getMemberPreviousPlanId(){
		$user = self::getUserObjectFromSessionOrAuth();

		$prev_plan_id = null;
		if (isset($user->get_member_previous_plans[0]->pricing_plan->id)) {
			$prev_plan_id = $user->get_member_previous_plans[0]->pricing_plan->id;
		}

		return $prev_plan_id;	
	}

	/*
	* Format user pricing plan data for additional dues popup
	*/
	public static function getImportOrSubmitDuesPopupData($user){

		$output = array();

		if($user->user_pricing_plan->pricing_plan_id == 0 || !$user->user_pricing_plan->plan_status && $user->user_pricing_plan->pricing_plan_id != 4){
            $output['free_customer_limit'] = $user->get_member_previous_plans[0]->free_customer_limit ?? 0;
            $output['plan_name'] = $user->get_member_previous_plans[0]->pricing_plan->name ?? 0;
            $output['additional_customer_price'] = $user->get_member_previous_plans[0]->pricing_plan->additional_customer_price ?? 0;
        } else {
        	$output['free_customer_limit'] = $user->user_pricing_plan->free_customer_limit;
            $output['plan_name'] = $user->user_pricing_plan->plan->name;
            $output['additional_customer_price'] = $user->user_pricing_plan->additional_customer_price;
        }

        return $output;
	}

	/*
	* Get user pricing plan collection_fee percentage value for my records
	*/
	public static function getMyRecordsCollectionFeePercent(){

		$collection_fee = 1;
		if(!empty(Auth::user()->user_pricing_plan)){

			$collection_fee = Auth::user()->user_pricing_plan->collection_fee;
			if (Auth::user()->user_pricing_plan->pricing_plan_id == 0 || !Auth::user()->user_pricing_plan->plan_status && Auth::user()->user_pricing_plan->pricing_plan_id != 4) {
				$collection_fee = Auth::user()->get_member_previous_plans[0]->pricing_plan->collection_fee ?? 1;
			}
			
		}

		return $collection_fee;
	}


	/*
	* Get user pricing plan consent_comprehensive_report_price for consent payment value
	*/
	public static function getConsentComprehensiveReportPrice($report='',$user=null){
		$consent_comprehensive_report_price = 0;
		if(!isset($user)){
        $user = Auth::user();
		} 
			
		if(!empty($user->user_pricing_plan)){
			if($report=='3'){
			  $consent_comprehensive_report_price = $user->user_pricing_plan->recordent_cmph_report_bussiness_price;
			if ($user->user_pricing_plan->pricing_plan_id == 0 || !$user->user_pricing_plan->plan_status && $user->user_pricing_plan->pricing_plan_id != 4) {
				$consent_comprehensive_report_price = $user->get_member_previous_plans[0]->pricing_plan->recordent_cmph_report_bussiness_price ?? 0;
			}	
			}
			else{

			$consent_comprehensive_report_price = $user->user_pricing_plan->consent_comprehensive_report_price;
			if (Auth::user()->user_pricing_plan->pricing_plan_id == 0 || !$user->user_pricing_plan->plan_status && $user->user_pricing_plan->pricing_plan_id != 4) {
				$consent_comprehensive_report_price = $user->get_member_previous_plans[0]->pricing_plan->consent_comprehensive_report_price ?? 0;
			}
		}
			
		}

		return $consent_comprehensive_report_price;
	}

	/*
	* Get user pricing plan business_consent_comprehensive_report_price for consent payment value
	*/
	public static function getBusinessConsentComprehensiveReportPrice(){

		$consent_comprehensive_report_price = 0;
		if(!empty(Auth::user()->user_pricing_plan)){

			$consent_comprehensive_report_price = Auth::user()->user_pricing_plan->recordent_cmph_report_bussiness_price;
			
			
		}
		// Log::debug(print_r($consent_comprehensive_report_price,true));

		return $consent_comprehensive_report_price;
	}

	/*
	* Get user pricing plan consent_recordent_report_price for consent payment value
	*/
	public static function getConsentRecordentReportPrice($user=null){
        if(!isset($user)){
        $user = Auth::user();
		} 
		$consent_recordent_report_price = 0;
		if(!empty($user->user_pricing_plan)){

			$consent_comprehensive_report_price = $user->user_pricing_plan->consent_recordent_report_price;
			if ($user->user_pricing_plan->pricing_plan_id == 0 || !$user->user_pricing_plan->plan_status && $user->user_pricing_plan->pricing_plan_id != 4) {
				$consent_recordent_report_price = $user->get_member_previous_plans[0]->pricing_plan->consent_recordent_report_price ?? 0;
			}
			
		}

		return $consent_recordent_report_price;
	}

	/*
	* Get consent_recordent_report_gst from pricing plan for consent payment
	*/
	public static function getConsentRecordentReportGst($user=null){
         if(!isset($user)){
       $user = self::getUserObjectFromSessionOrAuth();
		} 
		
		$consent_recordent_report_gst = 18;
		if(!empty($user->user_pricing_plan)){

			$consent_recordent_report_gst = $user->user_pricing_plan->pricing_plan->consent_recordent_report_gst;

			if ($user->user_pricing_plan->pricing_plan_id == 0 || !$user->user_pricing_plan->plan_status && $user->user_pricing_plan->pricing_plan_id != 4) {
				$consent_recordent_report_gst = $user->get_member_previous_plans[0]->pricing_plan->consent_recordent_report_gst ?? 18;
			}
			
		}

		return $consent_recordent_report_gst;
	}

	/*
	* Get additional_customer_price from user pricing plan for Dues Payment
	*/
	public static function getAdditionalCustomerPrice(){

		$user = self::getUserObjectFromSessionOrAuth();

		$additional_customer_price = 25;
		if(!empty($user->user_pricing_plan)){

			$additional_customer_price = $user->user_pricing_plan->additional_customer_price;
			if ($user->user_pricing_plan->pricing_plan_id == 0 || !$user->user_pricing_plan->plan_status && $user->user_pricing_plan->pricing_plan_id != 4) {
				$additional_customer_price = $user->get_member_previous_plans[0]->pricing_plan->additional_customer_price ?? 25;
			}
			
		}

		return $additional_customer_price;
	}

	/*
	* Get user pricing_plan_id from user_pricng_plan table
	*/
	public static function getUserPricingPlanId(){

		$user = self::getUserObjectFromSessionOrAuth();

		$pricing_plan_id = 1;
		if(!empty($user->user_pricing_plan)){

			$pricing_plan_id = $user->user_pricing_plan->pricing_plan_id;
			if ($user->user_pricing_plan->pricing_plan_id == 0 || !$user->user_pricing_plan->plan_status && $user->user_pricing_plan->pricing_plan_id != 4) {
				$pricing_plan_id = self::getMemberPreviousPlanId();
			}
		}

		return $pricing_plan_id;
	}

	/*
	* check session for member id or return default logged in user object
	*/
	public static function getUserObjectFromSessionOrAuth(){
		if($member_id = session::get('member_id')){
            $user = User::find($member_id);
        } else {
            $user = Auth::user();
        }

        return $user;
	}

	public static function isPlanExpired(){
		if(!empty(Auth::user()->user_pricing_plan)){

			if (Auth::user()->user_pricing_plan->end_date != null && strtotime(Auth::user()->user_pricing_plan->end_date) < strtotime(date('Y-m-d'))) {
				return true;
			}
		}

		return false;
	}


	public static function getNumberOfDaysLeftForExpiry(){

		$remaining_days_left = 0;
		if(!empty(Auth::user()->user_pricing_plan)){
				
			$membership_start_date = date_create(Auth::user()->user_pricing_plan->start_date);
			$today_date = date_create(date('Y-m-d H:i:s'));

			$difference = date_diff($membership_start_date, $today_date);
			$no_of_days_consumed = $difference->format("%a");

			Log::debug('no_of_days_consumed'. $no_of_days_consumed);

			if ($no_of_days_consumed > 0) {
				$remaining_days_left = 365 - $no_of_days_consumed;	
			}

			Log::debug('remaining_days_left = '.$remaining_days_left);


			if ($remaining_days_left < 0) {
				$remaining_days_left = 0;
			}
        	return $remaining_days_left;
		}

		return $remaining_days_left;
	}

	public static function sendPlanUpgradeInvoiceSmsByMobileNo($user_mobile_number){

		$login_url = config('app.url')."admin/login";

		$message = "Dear member, Thank you for Upgrading your membership at Recordent. Click here ".$login_url." to check your invoice."; 

		$smsService = new SmsService();
		$smsResponse = $smsService->sendSms($user_mobile_number, $message);

 		if($smsResponse['sent'] == 1){
 			Log::debug('Invoice sms has been sent to '.$user_mobile_number);
        	return true;
    	}

    	Log::debug('Failed to send invoice sms to '.$user_mobile_number);
    	return false;
	}


	public static function showOrHidePlanUpgradeButton(){

		$show_or_hide = true;

		$user = self::getUserObjectFromSessionOrAuth();

		if ($user->user_pricing_plan->pricing_plan_id == 0 || $user->user_pricing_plan->pricing_plan_id == 4 && !$user->user_pricing_plan->plan_status) {
			$show_or_hide = false;
		}

		if (HomeHelper::isPlanRenewable()) {
			$show_or_hide = true;
		}

		return $show_or_hide;
	}

	public static function isShowPlanExpiryFlashMessage(){

		if (!empty(Auth::user()->user_pricing_plan)) {
			if(strtotime(Auth::user()->user_pricing_plan->end_date) < strtotime(date('Y-m-d H:i:s',strtotime('+10 days')))){
				return true;	
			}
		}
		
		return false; 
	}
}