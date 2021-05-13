<?php

use Illuminate\Database\Seeder;
use App\PricingPlan;
use App\UserPricingPlan;
use Illuminate\Support\Facades\Log;

class UpdateExistingUsersPricingPlanDetails extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users_plan_details = UserPricingPlan::where('pricing_plan_id', '!=', 4)
        										->get();

        foreach ($users_plan_details as $key => $user_plan) {

        	if(!empty($user_plan)){

        		if ($user_plan->pricing_plan_id != 4 && $user_plan->pricing_plan_id != 0) {
	        		
	        		$PricingPlan = PricingPlan::find($user_plan->pricing_plan_id);
		        	$UserPricingPlan_data = UserPricingPlan::where('user_id', $user_plan->user_id)->first();

			        $UserPricingPlan_data->free_customer_limit = $PricingPlan->free_customer_limit;
			        $UserPricingPlan_data->membership_plan_price = $PricingPlan->membership_plan_price;
			        $UserPricingPlan_data->additional_customer_price = $PricingPlan->additional_customer_price;
			        $UserPricingPlan_data->consent_recordent_report_price = $PricingPlan->consent_recordent_report_price;
			        $UserPricingPlan_data->consent_comprehensive_report_price = $PricingPlan->consent_comprehensive_report_price;
			        $UserPricingPlan_data->recordent_report_business_price = $PricingPlan->recordent_report_business_price;
			        $UserPricingPlan_data->recordent_cmph_report_bussiness_price = $PricingPlan->recordent_cmph_report_bussiness_price;
			        $UserPricingPlan_data->collection_fee = $PricingPlan->collection_fee;
			        $UserPricingPlan_data->collection_fee_tier_1 = $PricingPlan->collection_fee_tier_1;
			        $UserPricingPlan_data->collection_fee_tier_2 = $PricingPlan->collection_fee_tier_2;
		        
		        	$UserPricingPlan_data->plan_status = 1;
		        	$UserPricingPlan_data->paid_status = 1;
		        	$UserPricingPlan_data->usa_b2b_credit_report = $PricingPlan->usa_b2b_credit_report;
		        	$UserPricingPlan_data->save();
		        }
        	}
        }
    }
}
