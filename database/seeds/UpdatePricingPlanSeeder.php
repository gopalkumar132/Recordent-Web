<?php

use Illuminate\Database\Seeder;
use App\PricingPlan;

class UpdatePricingPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $free_plan = PricingPlan::find(1);

        if($free_plan != null){

            $free_plan->name = "FREE Trial";

            $free_plan->membership_plan_price = 0;
            $free_plan->free_customer_limit = 10;

            $free_plan->consent_comprehensive_report_price = 100;
            $free_plan->consent_recordent_report_price = 0;
            $free_plan->recordent_report_business_price = 0;
            $free_plan->consent_recordent_report_gst = 18;

            //new fields data
            $free_plan->additional_customer_price = 25;
            $free_plan->recordent_cmph_report_bussiness_price = 1200;
            $free_plan->collection_fee = 1;
            $free_plan->collection_fee_tier_1 = 0;
            $free_plan->collection_fee_tier_2 = 1;

            $free_plan->usa_b2b_credit_report = 6000;

            $free_plan->save();
        }

        $basic = PricingPlan::find(2);
        if($basic != null){

            // $premium->membership_plan_price = 599;
            // $premium->free_customer_limit = 200;

            // $premium->consent_comprehensive_report_price = 80;
            // $premium->consent_recordent_report_price = 0;
            // $premium->consent_recordent_report_gst = 18;

            // $premium->additional_customer_price = 20;
            // $premium->recordent_cmph_report_bussiness_price = 950;
            // $premium->collection_fee = 0.8;
            // $premium->collection_fee_tier_1 = 0;
            // $premium->collection_fee_tier_2 = 1;
            // $premium->save();

            $basic->name = "BASIC";

            $basic->membership_plan_price = 599;
            $basic->free_customer_limit = 200;

            $basic->consent_comprehensive_report_price = 100;
            $basic->consent_recordent_report_price = 0;
            $basic->recordent_report_business_price = 0;
            $basic->consent_recordent_report_gst = 18;

            $basic->additional_customer_price = 25;
            $basic->recordent_cmph_report_bussiness_price = 1200;

            $basic->collection_fee = 1;
            $basic->collection_fee_tier_1 = 0;
            $basic->collection_fee_tier_2 = 1;
            $basic->usa_b2b_credit_report = 4500;
            $basic->save();
        }

        $executive = PricingPlan::find(3);
        if($executive!=null){

            $executive->membership_plan_price = 2499;
            $executive->free_customer_limit = 1000;

            $executive->consent_comprehensive_report_price = 70;
            $executive->consent_recordent_report_price = 0;
            $executive->recordent_report_business_price = 0;
            $executive->consent_recordent_report_gst = 18;


            $executive->additional_customer_price = 15;
            $executive->recordent_cmph_report_bussiness_price = 800;
            // $executive->collection_fee = 0.5;
            $executive->collection_fee = 0.8;
            $executive->collection_fee_tier_1 = 0;
            $executive->collection_fee_tier_2 = 1;

            $executive->usa_b2b_credit_report = 4000;

            $executive->save();
        }

        $corporate = PricingPlan::find(4);
        if($corporate != null){

            $corporate->membership_plan_price = 0;
            $corporate->free_customer_limit = 0;

            $corporate->consent_comprehensive_report_price = 0;
            $corporate->consent_recordent_report_price = 0;
            $corporate->consent_recordent_report_gst = 18;

            $corporate->collection_fee_tier_1 = 0;
            $corporate->collection_fee_tier_2 = 0;
            $corporate->save();
        }


        $standard_pricing_plan = PricingPlan::find(5);

        if (empty($standard_pricing_plan)) {
            $standard_pricing_plan = new PricingPlan();
        }

        $standard_pricing_plan->name = "STANDARD";

        // $standard_pricing_plan->membership_plan_price = 1499;
        $standard_pricing_plan->membership_plan_price = 1199;
        $standard_pricing_plan->free_customer_limit = 500;

        $standard_pricing_plan->consent_recordent_report_price = 0;
        $standard_pricing_plan->recordent_report_business_price = 0;
        $standard_pricing_plan->consent_recordent_report_gst = 18;

        $standard_pricing_plan->additional_customer_price = 20;
        $standard_pricing_plan->consent_comprehensive_report_price = 80;
        $standard_pricing_plan->recordent_cmph_report_bussiness_price = 1000;

        $standard_pricing_plan->collection_fee = 1;
        $standard_pricing_plan->collection_fee_tier_1 = 0;
        $standard_pricing_plan->collection_fee_tier_2 = 1;

        $standard_pricing_plan->usa_b2b_credit_report = 4500;

        $standard_pricing_plan->save();
    }
}
