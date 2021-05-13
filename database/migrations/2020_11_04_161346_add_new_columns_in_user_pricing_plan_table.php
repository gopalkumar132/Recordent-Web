<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsInUserPricingPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('user_pricing_plan', function (Blueprint $table) {
            $table->integer('free_customer_limit')->default(0);
            $table->float('additional_customer_price')->default(0);
            $table->float('consent_recordent_report_price')->default(0);
            $table->float('consent_comprehensive_report_price')->default(0);
            $table->float('recordent_report_business_price')->default(0);
            $table->float('recordent_cmph_report_bussiness_price')->default(0);
            $table->float('collection_fee')->default(0);
            $table->float('membership_plan_price')->default(0);
            $table->integer('transaction_id')->nullable();
            $table->boolean('plan_status')->default(0);
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_pricing_plan', function (Blueprint $table) {
            $table->dropColumn('free_customer_limit');
            $table->dropColumn('additional_customer_price');
            $table->dropColumn('consent_recordent_report_price');
            $table->dropColumn('consent_comprehensive_report_price');
            $table->dropColumn('recordent_report_business_price');
            $table->dropColumn('recordent_cmph_report_bussiness_price');
            $table->dropColumn('collection_fee');
            $table->dropColumn('membership_plan_price');
            $table->dropColumn('transaction_id');
            $table->dropColumn('plan_status');
            $table->dropColumn('updated_by');
        });
    }
}
