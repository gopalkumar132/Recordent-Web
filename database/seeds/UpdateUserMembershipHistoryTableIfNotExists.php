<?php

use Illuminate\Database\Seeder;
use App\UserPricingPlan;
use App\MembershipHistory;

class UpdateUserMembershipHistoryTableIfNotExists extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users_plan_details = UserPricingPlan::where('pricing_plan_id', '!=', 0)
        										->get();

        foreach ($users_plan_details as $key => $user_plan) {

        	if(!empty($user_plan)){
	        		
        		$membership_history = MembershipHistory::find($user_plan->user_id);

	        	if (empty($membership_history)) {
		            
		            $membership_history = new MembershipHistory();

		            $membership_history->pricing_plan_id = $user_plan->pricing_plan_id;
			        $membership_history->free_customer_limit = $user_plan->free_customer_limit;
			        $membership_history->membership_price = $user_plan->membership_plan_price;
			        $membership_history->start_date = $user_plan->start_date;
			        $membership_history->end_date = $user_plan->end_date;
			        $membership_history->customer_id = $user_plan->user_id;
			        $membership_history->membership_payment_id = $user_plan->membership_payment_id;

		        	$membership_history->save();
		        }
        	}
        }
    }
}
