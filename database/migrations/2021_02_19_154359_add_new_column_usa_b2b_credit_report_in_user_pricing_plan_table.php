<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnUsaB2bCreditReportInUserPricingPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_pricing_plan', function (Blueprint $table) {
            $table->float('usa_b2b_credit_report')->default(0);
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
            $table->dropColumn('usa_b2b_credit_report');
        });
    }
}
