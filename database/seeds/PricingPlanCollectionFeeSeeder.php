<?php

use Illuminate\Database\Seeder;
use App\PricingPlan;

class PricingPlanCollectionFeeSeeder extends Seeder
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
        	$free_trail->collection_fee_percent = 1;
        	$free_trail->save();
        }

        $basic = PricingPlan::find(2);
        if($basic!=null){
        	$basic->collection_fee_percent = 1;
        	$basic->save();
        }

        $executive = PricingPlan::find(3);
        if($executive!=null){
        	$executive->collection_fee_percent = 0.8;
        	$executive->save();
        }

        $corporate = PricingPlan::find(4);
        if($corporate!=null){
        	$corporate->collection_fee_percent = 0;
        	$corporate->save();
        }

        $standard = PricingPlan::find(5);
        if($standard!=null){
            $standard->collection_fee_percent = 1;
            $standard->save();
        }
    }
}
