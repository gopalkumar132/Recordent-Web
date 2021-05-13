<?php

use Illuminate\Database\Seeder;
use App\PricingPlan;

class PricingPlanAddtionalFields extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
        public function run()
    {
        $free_trail = PricingPlan::find(1);
        if($free_trail!=null){
        	$free_trail->membership_plan_price = 0;
        	$free_trail->free_customer_limit = 10;
        	$free_trail->collection_fee = 1;
        	$free_trail->save();
        }

        $basic = PricingPlan::find(2);
        if($basic!=null){
        	$basic->membership_plan_price = 599;
        	$basic->free_customer_limit = 200;
        	$basic->collection_fee = 1;
        	$basic->save();
        }

        $executive = PricingPlan::find(3);
        if($executive!=null){
        	$executive->membership_plan_price = 2499;
        	$executive->free_customer_limit = 1000;
        	$executive->collection_fee = 0;
        	$executive->save();
        }

        $corporate = PricingPlan::find(4);
        if($corporate!=null){
        	$corporate->membership_plan_price = 0;
        	$corporate->free_customer_limit = 0;
        	$corporate->collection_fee = 0;
        	$corporate->save();
        }
    }
    
}
