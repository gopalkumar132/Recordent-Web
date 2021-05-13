<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalFieldsInPricingPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->integer('additional_customer_price')->default(0);
            $table->integer('recordent_report_business_price')->default(0);
            $table->integer('recordent_cmph_report_bussiness_price')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->dropColumn('additional_customer_price');
            $table->dropColumn('recordent_report_business_price');
            $table->dropColumn('recordent_cmph_report_bussiness_price');
        });
    }
}
