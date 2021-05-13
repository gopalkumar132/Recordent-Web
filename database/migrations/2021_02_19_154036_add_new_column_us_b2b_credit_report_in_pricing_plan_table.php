<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnUsB2bCreditReportInPricingPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
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
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->dropColumn('usa_b2b_credit_report');
        });
    }
}
